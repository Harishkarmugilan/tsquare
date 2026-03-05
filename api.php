<?php

header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ================= DB CONNECT =================
$conn = mysqli_connect("localhost","user","","tsquare");

if (!$conn) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit;
}

// ================= GET INPUT =================
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
    exit;
}

$class = $data['class'];
$dept = $data['dept'];
$year = $data['year'];
$sem = $data['sem'];
$academic_year = $data['academic_year'];
$subjects = $data['subjects'];

$days = ["Mon","Tue","Wed","Thu","Fri"];
$maxHours = 7;
$totalSlots = 35;

$timetable = [];
$unplaced = 0;

// ================= HELPER FUNCTIONS =================

function isTaken($tt, $day, $hour){
    return isset($tt[$day][$hour]);
}

function isStaffBusy($staff, $day, $hour, $academic_year){
    global $conn;
    $q = mysqli_query($conn,"
        SELECT id FROM timetable
        WHERE staff_id='$staff'
        AND day='$day'
        AND hour_no='$hour'
        AND academic_year='$academic_year'
    ");
    return mysqli_num_rows($q) > 0;
}

function assign(&$tt, $day, $hour, $sub){
    $tt[$day][$hour] = $sub;
}

// ================= SCHEDULING =================

foreach($subjects as $sub){

    $remaining = $sub['periods'];
    $attempts = 0;

    // ---- NORMAL TRY (random placement) ----
    while($remaining > 0 && $attempts < 500){

        $day = $days[array_rand($days)];
        $hour = rand(1,$maxHours);

        if(
            !isTaken($timetable,$day,$hour) &&
            !isStaffBusy($sub['staff_id'],$day,$hour,$academic_year)
        ){
            assign($timetable,$day,$hour,$sub);
            $remaining--;
        }

        $attempts++;
    }

    // ---- SAFE OVERRIDE (STILL KEEP STAFF CLASH RULE) ----
    $overrideAttempts = 0;

    while($remaining > 0 && $overrideAttempts < 200){

        $placed = false;

        foreach($days as $day){
            for($hour=1;$hour<=$maxHours;$hour++){

                if(
                    !isTaken($timetable,$day,$hour) &&
                    !isStaffBusy($sub['staff_id'],$day,$hour,$academic_year)
                ){
                    assign($timetable,$day,$hour,$sub);
                    $remaining--;
                    $placed = true;
                    break 2;
                }
            }
        }

        if(!$placed){
            break;
        }

        $overrideAttempts++;
    }

    if($remaining > 0){
        $unplaced += $remaining;
    }
}

// ================= FILL EMPTY SLOTS =================

foreach($days as $day){
    for($hour=1;$hour<=$maxHours;$hour++){
        if(!isset($timetable[$day][$hour])){
            $timetable[$day][$hour] = [
                "staff_id" => null,
                "subject" => null
            ];
        }
    }
}

// ================= RETURN RESPONSE =================

echo json_encode([
    "status" => "success",
    "class" => $class,
    "academic_year" => $academic_year,
    "unplaced_due_to_staff_clash" => $unplaced,
    "timetable" => $timetable
]);