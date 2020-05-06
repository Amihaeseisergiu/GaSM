<?php

include_once '../config/Database.php';
include_once '../models/Marker.php';
include_once '../config/Response.php';

$markerRoutes =
    [
        [
            "route" => "markers/active",
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

                    Response::status(200);
                    Response::json($markers);
                } else {
                    Response::text("No Markers Found");
                }
            }
        ],
        [
            "route" => "markers/quantity/:filter/:country",
            "method" => "GET",
            "handler" => function ($req) {
                $database = new Database();
                $db = $database->connect();

                $marker = new Marker($db);

                $result = $marker->getMarkersByCounty($req['params']['filter'], $req['params']['country']);
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

                    Response::status(200);
                    Response::json($markersByCounty);
                } else {
                    Response::text("No Markers Found");
                }
            }
        ],
        [
            "route" => "markers/quantity/:filter/:country/:county",
            "method" => "GET",
            "handler" => function ($req) {
                $database = new Database();
                $db = $database->connect();

                $marker = new Marker($db);

                $result = $marker->getMarkersByRegion($req['params']['filter'], $req['params']['country'], $req['params']['county']);
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

                    Response::status(200);
                    Response::json($markerByRegion);
                } else {
                    Response::text("No Markers Found");
                }
            }
        ],
        [
            "route" => "markers/precedent/:filter/:country/:city",
            "method" => "GET",
            "handler" => function ($req) {
                $database = new Database();
                $db = $database->connect();

                $marker = new Marker($db);

                $result = $marker->getPrecedentTrash($req['params']['filter'], $req['params']['country'], $req['params']['city']);

                $num = $result->rowCount();

                if ($num > 0) {
                    $markers = array();
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        array_push($markers, $row);
                    }

                    Response::status(200);
                    Response::json($markers);
                } else {
                    Response::text("No Markers Found");
                }
            }
        ],
        [
            "route" => "markers/precedent/:filter",
            "method" => "GET",
            "handler" => function ($req) {
                $database = new Database();
                $db = $database->connect();

                $marker = new Marker($db);

                $result = $marker->getPrecedentTrash($req['params']['filter'], 'none', 'none');

                $num = $result->rowCount();

                if ($num > 0) {
                    $markers = array();
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        array_push($markers, $row);
                    }

                    Response::status(200);
                    Response::json($markers);
                } else {
                    Response::text("No Markers Found");
                }
            }
        ],
        [
            "route" => "markers/:filter/:country/:city",
            "method" => "GET",
            "handler" => function ($req) {
                $database = new Database();
                $db = $database->connect();
                $marker = new Marker($db);
                $result = $marker->getTrash($req['params']['filter'], $req['params']['country'], $req['params']['city']);
                $num = $result->rowCount();

                if ($num > 0) {
                    $markers = array();
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        array_push($markers, $row);
                    }

                    Response::status(200);
                    Response::json($markers);
                } else {
                    Response::text("No Markers Found");
                }
            }
        ],
        [
            "route" => "markers/lastbyuser",
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
                        Response::status(200);
                        Response::json($row);
                        break;
                    }
                } else {
                    Response::text("No Markers Found");
                }
            }
        ],
        [
            "route" => "markers/:filter",
            "method" => "GET",
            "handler" => function ($req) {
                $database = new Database();
                $db = $database->connect();
                $marker = new Marker($db);
                $result = $marker->getTrash($req['params']['filter'], '', '');
                $num = $result->rowCount();

                if ($num > 0) {
                    $markers = array();
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        array_push($markers, $row);
                    }

                    Response::status(200);
                    Response::json($markers);
                } else {
                    Response::text("No Markers Found");
                }
            }
        ],
        [
            "route" => "markers",
            "method" => "POST",
            "handler" => function ($req) {

                $database = new Database();
                $db = $database->connect();

                $marker = new Marker($db);

                $marker->insert($req['payload']);

                Response::status(200);
            }
        ],
        [
            "route" => "markers",
            "method" => "PUT",
            "handler" => function ($req) {
                $json = file_get_contents("php://input");
                $data = json_decode($json, true);

                $database = new Database();
                $db = $database->connect();

                $marker = new Marker($db);

                $marker->update($req['payload'][0], $req['payload'][1]);
            }
        ]
    ];
