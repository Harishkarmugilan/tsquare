<?php
header("Content-Type: application/json");

// ================= DB CONNECT =================
$conn = mysqli_connect("localhost","root","","timetable_db");
if(!$conn){
    echo json_encode(["status"=>"error","message"=>"DB connection failed"]);
    exit;
}

// ================= GET INPUT =================
$data = json_decode(file_get_contents("php://input"), true);

if(!$data){
    echo json_encode(["status"=>"error","message"=>"Invalid JSON"]);
    exit;
}

$class = $data['class'];
$dept = $data['dept'];
$year = $data['year'];
$sem = $data['sem'];
$academic_year = $data['academic_year'];
$subjects = $data['subjects'];

$days = ["Mon","Tue","Wed","Thu","Fri"];
$pairs = [[1,2],[2,3],[3,4],[4,5],[5,6],[6,7]];

$timetable = [];

// ================= HELPER FUNCTIONS =================

// Check slot already taken in this generated timetable
function isTaken($tt,$day,$hour){
    return isset($tt[$day][$hour]);
}

// Check staff clash from DB
function isStaffBusy($staff,$day,$hour,$academic_year){
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

// Assign slot
function assign(&$tt,$day,$hour,$sub){
    $tt[$day][$hour] = $sub;
}

// ================= LAB SCHEDULING =================

foreach($subjects as $sub){

if($sub['type'] == "lab"){

$pairsNeeded = floor($sub['periods']/2);
$single = $sub['periods'] % 2;
$usedDays = [];

// Place 2 consecutive periods
while($pairsNeeded > 0){

$day = $days[array_rand($days)];
if(in_array($day,$usedDays)) continue;

$pair = $pairs[array_rand($pairs)];

if(
!isTaken($timetable,$day,$pair[0]) &&
!isTaken($timetable,$day,$pair[1]) &&
!isStaffBusy($sub['staff_id'],$day,$pair[0],$academic_year) &&
!isStaffBusy($sub['staff_id'],$day,$pair[1],$academic_year)
){
assign($timetable,$day,$pair[0],$sub);
assign($timetable,$day,$pair[1],$sub);
$usedDays[] = $day;
$pairsNeeded--;
}
}

// Place single period if odd
if($single == 1){
while(true){

$day = $days[array_rand($days)];
$hour = rand(1,7);

if(
!isTaken($timetable,$day,$hour) &&
!isStaffBusy($sub['staff_id'],$day,$hour,$academic_year)
){
assign($timetable,$day,$hour,$sub);
break;
}
}
}

}
}

// ================= THEORY SCHEDULING =================

foreach($subjects as $sub){

if($sub['type'] == "theory"){

$remaining = $sub['periods'];

while($remaining > 0){

$day = $days[array_rand($days)];
$hour = rand(1,7);

if(
!isTaken($timetable,$day,$hour) &&
!isStaffBusy($sub['staff_id'],$day,$hour,$academic_year)
){
assign($timetable,$day,$hour,$sub);
$remaining--;
}
}
}
}

// ================= SAVE TO DB =================

// Remove old timetable for same class & academic year
mysqli_query($conn,"
DELETE FROM timetable
WHERE class_name='$class'
AND academic_year='$academic_year'
");

foreach($timetable as $day => $hrs){
foreach($hrs as $hour => $sub){

mysqli_query($conn,"
INSERT INTO timetable
(class_name,dept,year,sem,academic_year,day,hour_no,staff_id,subject)
VALUES
('$class','$dept','$year','$sem','$academic_year',
 '$day','$hour','".$sub['staff_id']."','".$sub['subject']."')
");

}
}

// ================= RETURN FINAL TIMETABLE (JOIN STAFF) =================

$result = mysqli_query($conn,"
SELECT t.day,t.hour_no,t.subject,s.name AS staff_name
FROM timetable t
JOIN staff s ON t.staff_id = s.id
WHERE t.class_name='$class'
AND t.academic_year='$academic_year'
ORDER BY FIELD(t.day,'Mon','Tue','Wed','Thu','Fri'), t.hour_no
");

$output = [];

while($row = mysqli_fetch_assoc($result)){
    $output[] = $row;
}

echo json_encode([
    "status"=>"success",
    "class"=>$class,
    "academic_year"=>$academic_year,
    "timetable"=>$output
]);
?>