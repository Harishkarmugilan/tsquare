<?php
header('Content-Type: application/json');

$raw = file_get_contents('php://input');
if (!$raw) {
    echo json_encode(['error' => 'no input received']);
    exit;
}

$payload = json_decode($raw, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'invalid JSON', 'message' => json_last_error_msg()]);
    exit;
}

$class = $payload['class'] ?? null;
$dept = $payload['dept'] ?? null;
$year = isset($payload['year']) ? intval($payload['year']) : null;
$sem = isset($payload['sem']) ? intval($payload['sem']) : null;
$academic_year = $payload['academic_year'] ?? null;
$timetable = $payload['timetable'] ?? null;
$override = $payload['override'];

$conn = mysqli_connect("localhost", "user", "", "tsquare");

if (!$conn) {
    echo json_encode(['error' => 'db connection failed']);
    exit;
}

if (!$class || !$dept || $year === null || $sem === null || !$academic_year || !is_array($timetable)) {
    echo json_encode(['error' => 'missing required input']);
    exit;
}

$class_esc = mysqli_real_escape_string($conn, $class);
$dept_esc = mysqli_real_escape_string($conn, $dept);
$ay_esc = mysqli_real_escape_string($conn, $academic_year);


/* ===========================
   CONFLICT CHECK
   =========================== */

function checkConflicts($conn, $timetable, $academic_year, $year, $sem, $dept_esc, $class_esc)
{

    $conflicts = [];
    $staffconflicts = [];
    $classconflicts = [];

    foreach ($timetable as $day => $hours) {

        if (!is_array($hours))
            continue;

        foreach ($hours as $hour => $entry) {

            if (!is_array($entry) || empty($entry['staff_id']))
                continue;

            $staff_id = intval($entry['staff_id']);
            $hour = intval($hour);

            $day = mysqli_real_escape_string($conn, $day);
            $ay = mysqli_real_escape_string($conn, $academic_year);

            $q = mysqli_query($conn, "
                SELECT class_name
                FROM timetable
                WHERE staff_id=$staff_id
                AND day='$day'
                AND hour_no=$hour
                AND academic_year='$ay'
                LIMIT 1
            ");

            if ($q && mysqli_num_rows($q) > 0) {

                $row = mysqli_fetch_assoc($q);

                $staffconflicts[] = [
                    'day' => $day,
                    'hour_no' => $hour,
                    'staff_id' => $staff_id,
                    'conflict_with_class' => $row['class_name']
                ];

            }

            $z = mysqli_query($conn, "
                SELECT *
                FROM timetable
                WHERE class_name='$class_esc'
                AND dept='$dept_esc'
                AND year=$year
                AND sem=$sem
                AND day='$day'
                AND hour_no=$hour
                AND academic_year='$ay'
                LIMIT 1
            ");

            if ($z && mysqli_num_rows($z) > 0) {

                $row = mysqli_fetch_assoc($z);

                $classconflicts[] = [
                    'day' => $day,
                    'hour_no' => $hour,
                    'staff_id' => $staff_id,
                    'conflict_with_class' => $row['class_name']
                ];

            }

        }

    }

    $conflicts['staffconflicts'] = $staffconflicts;
    $conflicts['classconflicts'] = $classconflicts;

    return $conflicts;

}


/* ===========================
   UPDATE TIMETABLE
   =========================== */

function updateTimetable($conn, $class, $dept, $year, $sem, $academic_year, $timetable, $override)
{

    $class_esc = mysqli_real_escape_string($conn, $class);
    $dept_esc = mysqli_real_escape_string($conn, $dept);
    $ay_esc = mysqli_real_escape_string($conn, $academic_year);


    /* remove old timetable of the class */

    mysqli_query($conn, "
        DELETE FROM timetable
        WHERE class_name='$class_esc'
        AND dept='$dept_esc'
        AND year=$year
        AND sem=$sem
        AND academic_year='$ay_esc'
        ");


    foreach ($timetable as $day => $hours) {

        if (!is_array($hours))
            continue;

        foreach ($hours as $hour => $entry) {

            $hour = intval($hour);
            $day_esc = mysqli_real_escape_string($conn, $day);

            $staff_val = "NULL";
            $sub_val = "NULL";

            if (is_array($entry)) {

                if (!empty($entry['staff_id'])) {
                    $staff_val = intval($entry['staff_id']);
                }

                if (!empty($entry['subject'])) {
                    $subject = mysqli_real_escape_string($conn, $entry['subject']);
                    $sub_val = "'$subject'";
                }

            }

            /* override staff conflict */

            if ($staff_val != "NULL") {

                mysqli_query($conn, "
            DELETE FROM timetable
            WHERE staff_id=$staff_val
            AND day='$day_esc'
            AND hour_no=$hour
            AND academic_year='$ay_esc'
            ");

            }


            $sql = "
        INSERT INTO timetable
        (class_name,dept,year,sem,academic_year,day,hour_no,staff_id,subject)
        VALUES
        ('$class_esc','$dept_esc',$year,$sem,'$ay_esc','$day_esc',$hour,$staff_val,$sub_val)
        ";

            if (!mysqli_query($conn, $sql)) {
                return false;
            }

        }

    }

    return true;

}



/* ===========================
   MAIN LOGIC
   =========================== */

if (!$override) {

    $conflicts = checkConflicts($conn, $timetable, $academic_year, $year, $sem, $dept_esc, $class_esc);

    if ((count($conflicts['staffconflicts']) > 0) || (count($conflicts['classconflicts']) > 0)) {

        echo json_encode([
            'status' => 'conflicts',
            'conflicts' => $conflicts
        ]);

        exit;

    }

}


$success = updateTimetable($conn, $class, $dept, $year, $sem, $academic_year, $timetable, $override);


if ($success) {

    echo json_encode([
        'status' => 'ok',
        'message' => $override ? 'override applied' : 'timetable updated'
    ]);

} else {

    echo json_encode([
        'status' => 'error',
        'message' => 'database operation failed'
    ]);

}
?>