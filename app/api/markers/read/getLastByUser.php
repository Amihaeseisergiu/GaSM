<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/x-www-form-urlencoded");

include_once '../../../config/Database.php';
include_once '../../../models/Marker.php';

$database = new Database();
$db = $database->connect();

$marker = new Marker($db);

session_start();
$userId = $_SESSION['userID'];
$result = $marker->getMarkersByUser($userId);
$num = $result->rowCount();

if($num > 0)
{
    while($row = $result->fetch(PDO::FETCH_ASSOC))
    {
        echo json_encode($row);
        break;
    }
}
else
{
    echo json_encode(
        array('message' => 'No Markers Found')
    );
}
