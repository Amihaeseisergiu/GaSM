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

    $result = $marker->getMarkersByCounty($_GET['filter'], $_GET['country']);
    $num = $result->rowCount();

    if($num > 0)
    {
        $markersByCounty = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            $ok = 0;
            for ($j = 0; $j < count($markersByCounty); $j++) {
                if ($markersByCounty[$j]['county'] == $row['county']) {
                    $markersByCounty[$j]['quantity']++;
                    $ok = 1;
                }
            }
            if ($ok == 0) {
                array_push($markersByCounty, array("county" => $row['county'], "quantity" => 1));
            }
        }

        echo json_encode($markersByCounty);
    }
  /*  else
    {
        echo json_encode(
            array('message' => 'No Markers Found')
        );
    }*/
}

?>