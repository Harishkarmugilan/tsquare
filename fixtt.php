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
$override = !empty($payload['override']);

$conn = mysqli_connect("localhost", "root", "", "tsquare");
if (!$conn) {
    echo json_encode(['error' => 'db connection failed']);
    exit;
}

if (!$class || !$dept || $year === null || $sem === null || !$academic_year || !is_array($timetable)) {
    echo json_encode(['error' => 'missing required input']);
    exit;
}

/* ===========================
   CONFLICT CHECK FUNCTION
   =========================== */

function checkConflicts($conn, $class, $dept, $year, $sem, $academic_year, $timetable)
{
    $conflicts = [];

    foreach ($timetable as $day => $hours) {
        if (!is_array($hours)) continue;

        foreach ($hours as $hour_no => $entry) {

            if (!is_array($entry) || empty($entry['staff_id'])) continue;

            $staff_id = intval($entry['staff_id']);
            $hour_no = intval($hour_no);

            $day_esc = mysqli_real_escape_string($conn, $day);
            $ay_esc = mysqli_real_escape_string($conn, $academic_year);

            // 🔥 Check staff double booking (real conflict)
            $q = mysqli_query($conn, "
                SELECT class_name FROM timetable
                WHERE staff_id = $staff_id
                AND day = '$day_esc'
                AND hour_no = $hour_no
                AND academic_year = '$ay_esc'
                LIMIT 1
            ");

            if ($q && mysqli_num_rows($q) > 0) {
                $row = mysqli_fetch_assoc($q);

                $conflicts[] = [
                    'day' => $day,
                    'hour_no' => $hour_no,
                    'staff_id' => $staff_id,
                    'conflict_with_class' => $row['class_name']
                ];
            }
        }
    }

    return $conflicts;
}

/* ===========================
   UPDATE / INSERT FUNCTION
   =========================== */

function updateTimetable($conn, $class, $dept, $year, $sem, $academic_year, $timetable)
{
    $class_esc = mysqli_real_escape_string($conn, $class);
    $dept_esc = mysqli_real_escape_string($conn, $dept);
    $ay_esc = mysqli_real_escape_string($conn, $academic_year);

    foreach ($timetable as $day => $hours) {
        if (!is_array($hours)) continue;

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

            $sql = "
                INSERT INTO timetable
                (class_name, dept, year, sem, academic_year, day, hour_no, staff_id, subject)
                VALUES
                ('$class_esc', '$dept_esc', $year, $sem, '$ay_esc', '$day_esc', $hour, $staff_val, $sub_val)
                ON DUPLICATE KEY UPDATE
                staff_id = VALUES(staff_id),
                subject = VALUES(subject)
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

// If NOT override → check conflicts first
if (!$override) {

    $conflicts = checkConflicts($conn, $class, $dept, $year, $sem, $academic_year, $timetable);

    if (count($conflicts) > 0) {
        echo json_encode([
            'status' => 'conflicts',
            'conflicts' => $conflicts
        ]);
        exit;
    }
}

// If override OR no conflicts → update
$success = updateTimetable($conn, $class, $dept, $year, $sem, $academic_year, $timetable);

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