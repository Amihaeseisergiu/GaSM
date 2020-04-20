<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$json = file_get_contents("php://input");
$data = json_decode($json, true);

include_once '../../../config/Database.php';
include_once '../../../models/Marker.php';

$database = new Database();
$db = $database->connect();

$marker = new Marker($db);

$marker->insert($data);

?>