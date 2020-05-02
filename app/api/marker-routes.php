<?php

include_once '../config/Database.php';
include_once '../models/Marker.php';

$markerRoutes =
    [
        [
            "url" => "/markers/active",
            "method" => "GET",
            "handler" => function ($req) {
                $database = new Database();
                $db = $database->connect();

                $marker = new Marker($db);

                $result = $marker->getAll();
                $num = $result->rowCount();
                if ($num > 0) {
                    $markers = array();
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        array_push($markers, $row);
                    }

                    echo json_encode($markers);
                } else {
                    echo json_encode(
                        array('message' => 'No Markers Found')
                    );
                }
            }
        ],
        [
            "url" => "/markers/quantity/:filter/:country",
            "method" => "GET",
            "handler" => function ($req) {
                $database = new Database();
                $db = $database->connect();

                $marker = new Marker($db);

                $result = $marker->getMarkersByCounty($req['params'][0]['filter'], $req['params'][1]['country']);
                $num = $result->rowCount();

                if ($num > 0) {
                    $markersByCounty = array();
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
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
                } else {
                    echo json_encode(
                        array('message' => 'No Markers Found')
                    );
                }
            }
        ],
        [
            "url" => "/markers/quantity/:filter/:county/:country",
            "method" => "GET",
            "handler" => function ($req) {
                $database = new Database();
                $db = $database->connect();

                $marker = new Marker($db);

                $result = $marker->getMarkersByRegion($req['params'][0]['filter'], $req['params'][1]['county'], $req['params'][2]['country']);
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
            }
        ],
        [
            "url" => "/markers/precedent/:filter/:country/:city",
            "method" => "GET",
            "handler" => function ($req) {
                $database = new Database();
                $db = $database->connect();

                $marker = new Marker($db);

                $result = $marker->getPrecedentTrash($req['params'][0]['filter'], $req['params'][1]['country'], $req['params'][2]['city']);

                $num = $result->rowCount();

                if ($num > 0) {
                    $markers = array();
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        array_push($markers, $row);
                    }

                    echo json_encode($markers);
                } else {
                    echo json_encode(
                        array('message' => 'No Markers Found')
                    );
                }
            }
        ],
        [
            "url" => "/markers/:filter/:country/:city",
            "method" => "GET",
            "handler" => function ($req) {
                $database = new Database();
                $db = $database->connect();
                $marker = new Marker($db);
                $result = $marker->getTrash($req['params'][0]['filter'], $req['params'][1]['country'], $req['params'][2]['city']);
                $num = $result->rowCount();

                if ($num > 0) {
                    $markers = array();
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        array_push($markers, $row);
                    }

                    echo json_encode($markers);
                } else {
                    echo json_encode(
                        array('message' => 'No Markers Found')
                    );
                }
            }
        ],
        [
            "url" => "/markers/lastbyuser",
            "method" => "GET",
            "handler" => function ($req) {
                $database = new Database();
                $db = $database->connect();

                $marker = new Marker($db);

                session_start();
                $userId = $_SESSION['userID'];
                $result = $marker->getMarkersByUser($userId);
                $num = $result->rowCount();

                if ($num > 0) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo json_encode($row);
                        break;
                    }
                } else {
                    echo json_encode(
                        array('message' => 'No Markers Found')
                    );
                }
            }
        ],
        [
            "url" => "/markers",
            "method" => "POST",
            "handler" => function ($req) {

                $json = file_get_contents("php://input");
                $data = json_decode($json, true);

                $database = new Database();
                $db = $database->connect();

                $marker = new Marker($db);

                $marker->insert($data);
            }
        ],
        [
            "url" => "/markers",
            "method" => "PUT",
            "handler" => function ($req) {
                $json = file_get_contents("php://input");
                $data = json_decode($json, true);

                $database = new Database();
                $db = $database->connect();

                $marker = new Marker($db);

                $marker->update($data[0], $data[1]);
            }
        ],
        [
            "url" => "/markers/:id",
            "method" => "GET",
            "handler" => function ($req) {
                
            }
        ]
    ];
