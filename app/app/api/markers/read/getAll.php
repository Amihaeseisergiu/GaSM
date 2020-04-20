<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/x-www-form-urlencoded");

include_once '../../../config/Database.php';
include_once '../../../models/Marker.php';

$database = new Database();
$db = $database->connect();

$marker = new Marker($db);

$result = $marker->getAll();
$num = $result->rowCount();

if($num > 0)
{
    $markers = array();
    while($row = $result->fetch(PDO::FETCH_ASSOC))
    {
        array_push($markers, $row);
    }

    echo json_encode($markers);
}
else
{
    echo json_encode(
        array('message' => 'No Markers Found')
    );
}


?>