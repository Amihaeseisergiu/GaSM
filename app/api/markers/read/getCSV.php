<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/x-www-form-urlencoded");

include_once '../../../config/Database.php';
include_once '../../../models/Marker.php';

if (isset($_GET['filter'])) {
    $database = new Database();
    $db = $database->connect();

    $marker = new Marker($db);

    if (array_key_exists('city', $_GET)) {
        $result = $marker->getCSVString($_GET['filter'], $_GET['country'], $_GET['city']);
    } else {
        $result = $marker->getCSVString($_GET['filter'], '', '');
    }
    $num = $result->rowCount();

    if ($num > 0) {
        $CSVString = "";
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $CSVString = $CSVString . $row['id'] . ', ' . $row['latitude'] . ', ' . $row['longitude'] . ', ' . $row['trash_type'] . ', ' . $row['user_id'] . ', ' . $row['time'] . ', ' . $row['country'] . ', ' . $row['county'] . ', ' . $row['city'] . " newline ";
        }

        echo json_encode($CSVString);
    } else {
        echo json_encode(
            array('message' => 'No Markers Found')
        );
    }
}
