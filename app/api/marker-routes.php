<?php

include_once '../config/Database.php';
include_once '../models/Marker.php';
include_once '../config/Response.php';

session_start();
$database = new Database();
$db = $database->connect();

$marker = new Marker($db);

$markerRoutes =
    [
        [
            "route" => "markers/active",
            "method" => "GET",
            "handler" => function ($req) {
                global $marker;

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
                    Response::status(200);
                    Response::text("No Markers Found");
                }
            }
        ],
        [
            "route" => "markers/quantity/:country/:county",
            "method" => "GET",
            "handler" => function ($req) {
                global $marker;
                if (isset($req['query']['filter']) && isset($req['query']['type'])) {
                    $result = $marker->getMarkersByRegionAndTrashType($req['query']['filter'], $req['params']['country'], $req['params']['county'], $req['query']['type']);
                } else if (isset($req['query']['filter'])) {
                    $result = $marker->getMarkersByRegion($req['query']['filter'], $req['params']['country'], $req['params']['county']);
                } else if (isset($req['query']['type'])) {
                    $result = $marker->getMarkersByRegionAndTrashType("AllTime", $req['params']['country'], $req['params']['county'], $req['query']['type']);
                } else {
                    $result = $marker->getMarkersByRegion("AllTime", $req['params']['country'], $req['params']['county']);
                }
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
                    Response::status(200);
                    Response::text("No Markers Found");
                }
            }
        ],
        [
            "route" => "markers/quantity/:country",
            "method" => "GET",
            "handler" => function ($req) {
                global $marker;

                if (isset($req['query']['filter']) && isset($req['query']['type'])) {
                    $result = $marker->getMarkersByCountyAndTrashType($req['query']['filter'], $req['params']['country'], $req['query']['type']);
                } else if (isset($req['query']['filter'])) {
                    $result = $marker->getMarkersByCounty($req['query']['filter'], $req['params']['country']);
                } else if (isset($req['query']['type'])) {
                    $result = $marker->getMarkersByCountyAndTrashType("AllTime", $req['params']['country'], $req['query']['type']);
                } else {
                    $result = $marker->getMarkersByCounty("AllTime", $req['params']['country']);
                }

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
                    Response::status(200);
                    Response::text("No Markers Found");
                }
            }
        ],
        [
            "route" => "markers/precedent/:country/:city",
            "method" => "GET",
            "handler" => function ($req) {
                global $marker;
                if (isset($req['query']['filter'])) {
                    $result = $marker->getPrecedentTrash($req['query']['filter'], $req['params']['country'], $req['params']['city']);
                } else {
                    $result = $marker->getPrecedentTrash("AllTime", $req['params']['country'], $req['params']['city']);
                }
                $num = $result->rowCount();

                if ($num > 0) {
                    $markers = array();
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        array_push($markers, $row);
                    }

                    Response::status(200);
                    Response::json($markers);
                } else {
                    Response::status(200);
                    Response::text("No Markers Found");
                }
            }
        ],
        [
            "route" => "markers/precedent",
            "method" => "GET",
            "handler" => function ($req) {
                global $marker;

                if (isset($req['query']['filter'])) {
                    $result = $marker->getPrecedentTrash($req['query']['filter'], '', '');
                } else {
                    $result = $marker->getPrecedentTrash("AllTime", '', '');
                }

                $num = $result->rowCount();

                if ($num > 0) {
                    $markers = array();
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        array_push($markers, $row);
                    }

                    Response::status(200);
                    Response::json($markers);
                } else {
                    Response::status(200);
                    Response::text("No Markers Found");
                }
            }
        ],
        [
            "route" => "markers/:country/:city",
            "method" => "GET",
            "handler" => function ($req) {
                global $marker;

                if (isset($req['query']['filter'])) {
                    $result = $marker->getTrash($req['query']['filter'], $req['params']['country'], $req['params']['city']);
                } else {
                    $result = $marker->getTrash("AllTime", $req['params']['country'], $req['params']['city']);
                }
                $num = $result->rowCount();

                if ($num > 0) {
                    $markers = array();
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        array_push($markers, $row);
                    }

                    Response::status(200);
                    Response::json($markers);
                } else {
                    Response::status(200);
                    Response::text("No Markers Found");
                }
            }
        ],
        [
            "route" => "markers/lastbyuser",
            "middlewares" => ["IsLoggedIn"],
            "method" => "GET",
            "handler" => function ($req) {
                global $marker;

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
                    Response::status(200);
                    Response::text("No Markers Found");
                }
            }
        ],
        [
            "route" => "markers",
            "method" => "GET",
            "handler" => function ($req) {
                global $marker;
                if (isset($req['query']['filter'])) {
                    $result = $marker->getTrash($req['query']['filter'], '', '');
                } else {
                    $result = $marker->getTrash("AllTime", '', '');
                }
                $num = $result->rowCount();

                if ($num > 0) {
                    $markers = array();
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        array_push($markers, $row);
                    }

                    Response::status(200);
                    Response::json($markers);
                } else {
                    Response::status(200);
                    Response::text("No Markers Found");
                }
            }
        ],
        [
            "route" => "markers",
            "middlewares" => ["IsLoggedIn"],
            "method" => "POST",
            "handler" => function ($req) {
                global $marker;

                $marker->insert($req['payload']);

                Response::status(200);
            }
        ],
        [
            "route" => "markers",
            "middlewares" => ["IsLoggedIn", "isAdmin"],
            "method" => "PUT",
            "handler" => function ($req) {
                global $marker;

                $marker->update($req['payload'][0], $req['payload'][1]);

                Response::status(200);
            }
        ]
    ];

function isLoggedIn()
{
    if (isset($_SESSION['userID']) && $_SESSION['userID'] > 0)
        return true;

    Response::status(402);
    Response::text("You can only access this route if you're authenticated!");

    return false;
}

function isAdmin()
{
    if (isset($_SESSION['privileges']) && strcmp($_SESSION['privileges'], "admin") == 0)
        return true;

    Response::status(401);
    Response::text("You can only access this route if you're an admin!");

    return false;
}
