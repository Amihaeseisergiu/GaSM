<?php
$json = file_get_contents("php://input");
$data = json_decode($json, true);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$con = mysqli_connect("Localhost", "root", "", "tw");
$markers = array();
$filter = $data['filter'];
if ($filter == "Last Week") {
    $query = $con->prepare("SELECT * FROM markers
            WHERE time <= CURDATE() AND time >= CURDATE() - INTERVAL 7 DAY");
} else if ($filter == "Last Month") {
    $query = $con->prepare("SELECT * FROM markers
            WHERE time <= CURDATE() AND time >= CURDATE() - INTERVAL 31 DAY");
} else if ($filter == "Today") {
    $query = $con->prepare("SELECT * FROM markers
            WHERE time >= CURDATE() AND time < CURDATE() + INTERVAL 1 DAY");
} else {
    $query = $con->prepare("SELECT * from markers");
}
$query->execute();
$result = $query->get_result();

for ($i = 1; $i <= $result->num_rows; $i++) {
    $row = $result->fetch_assoc();
    array_push($markers, array("id" => $row['id'], "latitude" => $row['latitude'], "longitude" => $row['longitude'], "trash_type" => $row['trash_type'], "user_id" => $row['user_id'], "time" => $row['time'], "country" => $row['country'], "county" => $row['county'], "city" => $row['city']));
}
$con->close();
echo json_encode($markers);
