<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/x-www-form-urlencoded");

include_once '../../../config/Database.php';
include_once '../../../models/Marker.php';

if(isset($_GET['filter']))
{
    $database = new Database();
    $db = $database->connect();

    $marker = new Marker($db);

    if(array_key_exists('city', $_GET)){
        $result = $marker->getPrecedentTrash($_GET['filter'], $_GET['city'], $_GET['country']);
        }
        else {
            $result = $marker->getPrecedentTrash($_GET['filter'], '', '');
        }
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
}

?>