<?php
$conn = new mysqli("localhost", "root", "", "tsquare");

$result = $conn->query("SELECT id, name, dept FROM staff");

$staff = [];

while($row = $result->fetch_assoc()){
    $staff[] = $row;
}

echo json_encode($staff);
?>