<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/x-www-form-urlencoded");

include_once '../../../config/Database.php';
include_once '../../../models/Marker.php';

if (isset($_GET['filter'])) {
    if (array_key_exists('country', $_GET) && array_key_exists('county', $_GET) && $_GET['country'] != 'none' && $_GET['county'] != 'none') {
        $database = new Database();
        $db = $database->connect();

        $marker = new Marker($db);

        $result = $marker->getMarkersByRegion($_GET['filter'], $_GET['county'], $_GET['country']);
        $num = $result->rowCount();

        if ($num > 0) {
            $markerByRegion = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $ok = 0;
                for ($j = 0; $j < count($markerByRegion); $j++) {
                    if ($markerByRegion[$j]['city'] == $row['city']) {
                        $markerByRegion[$j]['quantity']++;
                        $ok = 1;
                    }
                }
                if ($ok == 0) {
                    array_push($markerByRegion, array("city" => $row['city'], "quantity" => 1));
                }
            }

            echo json_encode($markerByRegion);
        } else {
            echo json_encode(
                array('message' => 'No Markers Found')
            );
        }
    } else {
        echo json_encode(
            array('message' => 'No Markers Found')
        );
    }
}
