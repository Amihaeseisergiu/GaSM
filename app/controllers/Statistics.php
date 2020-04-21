<?php

class Statistics extends Controller
{
    public function index($filter = '', $shownGarbageTypes = '')
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://localhost:80/proiect/GaSM/app/api/markers/read/getTrash.php?filter=" . $filter . '&country=' . $_SESSION['country'] . '&city=' . $_SESSION['city'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));
        //  echo curl_exec($curl);
        $markers = curl_exec($curl);
        $markers = json_decode($markers, true);
        $err = curl_error($curl);

        curl_close($curl);
        $markerCoordinates = array();
        $plastics = array();
        $papers = array();
        $metals = array();
        $glasses = array();
        if (!array_key_exists('message', $markers)) {
            foreach ($markers as $marker) {
                array_push($markerCoordinates, array("longitude" => $marker['longitude'], 'latitude' => $marker['latitude']));
                $time = substr($marker['time'], 0, strpos($marker['time'], ' '));
                if ($filter == 'Today') {
                    $time = date("Y-m-d H:i:00", strtotime($marker['time']));
                }
                if ($marker['trash_type'] == 'plastic') {
                    $ok = 0;
                    for ($i = 0; $i < count($plastics); $i++) {
                        if ($plastics[$i]['time'] == $time) {
                            $plastics[$i]['quantity']++;
                            $ok = 1;
                        }
                    }
                    if ($ok == 0) {
                        array_push($plastics, array("time" => $time, "quantity" => 1));
                    }
                } else if ($marker['trash_type'] == 'paper') {
                    $ok = 0;
                    for ($i = 0; $i < count($papers); $i++) {
                        if ($papers[$i]['time'] == $time) {
                            $papers[$i]['quantity']++;
                            $ok = 1;
                        }
                    }
                    if ($ok == 0) {
                        array_push($papers, array("time" => $time, "quantity" => 1));
                    }
                } else  if ($marker['trash_type'] == 'glass') {
                    $ok = 0;
                    for ($i = 0; $i < count($glasses); $i++) {
                        if ($glasses[$i]['time'] == $time) {
                            $glasses[$i]['quantity']++;
                            $ok = 1;
                        }
                    }
                    if ($ok == 0) {
                        array_push($glasses, array("time" => $time, "quantity" => 1));
                    }
                } else if ($marker['trash_type'] == 'metal') {
                    $ok = 0;
                    for ($i = 0; $i < count($metals); $i++) {
                        if ($metals[$i]['time'] == $time) {
                            $metals[$i]['quantity']++;
                            $ok = 1;
                        }
                    }
                    if ($ok == 0) {
                        array_push($metals, array("time" => $time, "quantity" => 1));
                    }
                }
            }
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://localhost:80/proiect/GaSM/app/api/markers/read/getPrecedentTrash.php?filter=" . $filter . '&country=' . $_SESSION['country'] . '&city=' . $_SESSION['city'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));

        $Pmarkers = curl_exec($curl);
        $Pmarkers = json_decode($Pmarkers, true);
        $err = curl_error($curl);

        curl_close($curl);

        $PmarkerCoordinates = array();
        if (!array_key_exists('message', $Pmarkers)) {
            foreach ($Pmarkers as $Pmarker) {
                array_push($PmarkerCoordinates, array("longitude" => $Pmarker['longitude'], "latitude" => $Pmarker['latitude']));
            }
        }

        function distance($lat1, $lon1, $lat2, $lon2, $unit)
        {
            if (($lat1 == $lat2) && ($lon1 == $lon2)) {
                return 0;
            } else {
                $theta = $lon1 - $lon2;
                $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;
                $unit = strtoupper($unit);

                if ($unit == "K") {
                    return ($miles * 1.609344);
                } else if ($unit == "N") {
                    return ($miles * 0.8684);
                } else {
                    return $miles;
                }
            }
        }
        $averageCurrentMarkerDistance = 0;
        if (count($markerCoordinates) > 0) {
            for ($i = 0; $i < count($markerCoordinates) - 1; $i++) {
                for ($j = $i + 1; $j < count($markerCoordinates); $j++) {
                    $averageCurrentMarkerDistance = $averageCurrentMarkerDistance + distance($markerCoordinates[$i]['latitude'], $markerCoordinates[$i]['longitude'], $markerCoordinates[$j]['latitude'], $markerCoordinates[$j]['longitude'], 'K');
                }
            }
            $averageCurrentMarkerDistance = $averageCurrentMarkerDistance / count($markerCoordinates);
        }
        $averagePrecedentMarkerDistance = 0;
        if (count($PmarkerCoordinates) > 0) {
            for ($i = 0; $i < count($PmarkerCoordinates) - 1; $i++) {
                for ($j = $i + 1; $j < count($PmarkerCoordinates); $j++) {
                    $averagePrecedentMarkerDistance = $averagePrecedentMarkerDistance + distance($PmarkerCoordinates[$i]['latitude'], $PmarkerCoordinates[$i]['longitude'], $PmarkerCoordinates[$j]['latitude'], $PmarkerCoordinates[$j]['longitude'], 'K');
                }
            }
            $averagePrecedentMarkerDistance = $averagePrecedentMarkerDistance / count($PmarkerCoordinates);
        }

        /* $Pplastics = array();
        $Ppapers = array();
        $Pmetals = array();
        $Pglasses = array();
        if (!array_key_exists('message', $Pmarkers)) {
            foreach ($Pmarkers as $marker) {
                $time = substr($marker['time'], 0, strpos($marker['time'], ' '));
                if ($filter == 'Today') {
                    $time = date("Y-m-d H:i:00", strtotime($marker['time']));
                }
                if ($marker['trash_type'] == 'plastic') {
                    $ok = 0;
                    for ($i = 0; $i < count($Pplastics); $i++) {
                        if ($Pplastics[$i]['time'] == $time) {
                            $Pplastics[$i]['quantity']++;
                            $ok = 1;
                        }
                    }
                    if ($ok == 0) {
                        array_push($Pplastics, array("time" => $time, "quantity" => 1));
                    }
                } else if ($marker['trash_type'] == 'paper') {
                    $ok = 0;
                    for ($i = 0; $i < count($Ppapers); $i++) {
                        if ($Ppapers[$i]['time'] == $time) {
                            $Ppapers[$i]['quantity']++;
                            $ok = 1;
                        }
                    }
                    if ($ok == 0) {
                        array_push($Ppapers, array("time" => $time, "quantity" => 1));
                    }
                } else  if ($marker['trash_type'] == 'glass') {
                    $ok = 0;
                    for ($i = 0; $i < count($Pglasses); $i++) {
                        if ($Pglasses[$i]['time'] == $time) {
                            $Pglasses[$i]['quantity']++;
                            $ok = 1;
                        }
                    }
                    if ($ok == 0) {
                        array_push($Pglasses, array("time" => $time, "quantity" => 1));
                    }
                } else if ($marker['trash_type'] == 'metal') {
                    $ok = 0;
                    for ($i = 0; $i < count($Pmetals); $i++) {
                        if ($Pmetals[$i]['time'] == $time) {
                            $Pmetals[$i]['quantity']++;
                            $ok = 1;
                        }
                    }
                    if ($ok == 0) {
                        array_push($Pmetals, array("time" => $time, "quantity" => 1));
                    }
                }
            }
        }*/


        if ($shownGarbageTypes === "") {
            $shownGarbage['plastic'] = true;
            $shownGarbage['paper'] = true;
            $shownGarbage['glass'] = true;
            $shownGarbage['metal'] = true;
        } else {
            $shownGarbage = array();
            if (strpos($shownGarbageTypes, "plastic") !== false) {
                $shownGarbage['plastic'] = true;
            } else {
                $shownGarbage['plastic'] = false;
            }
            if (strpos($shownGarbageTypes, "paper") !== false) {
                $shownGarbage['paper'] = true;
            } else {
                $shownGarbage['paper'] = false;
            }
            if (strpos($shownGarbageTypes, "glass") !== false) {
                $shownGarbage['glass'] = true;
            } else {
                $shownGarbage['glass'] = false;
            }
            if (strpos($shownGarbageTypes, "metal") !== false) {
                $shownGarbage['metal'] = true;
            } else {
                $shownGarbage['metal'] = false;
            }
        }
        if (!array_key_exists('message', $Pmarkers)) {
            $nrOfPrecedentReports = count($Pmarkers);
            //echo count($Pmarkers);
        } else {
            $nrOfPrecedentReports = 0;
        }
        if (!array_key_exists('message', $markers)) {
            //echo count($markers);
            $nrOfCurrentReports = count($markers);
        } else {
            $nrOfCurrentReports = 0;
        }
        $changes = array();
        if ($nrOfCurrentReports > $nrOfPrecedentReports) {
            $dif = $nrOfCurrentReports - $nrOfPrecedentReports;
            $dif = '+ ' . $dif . ' Reports';
            $arrow = 'downarrow';
        } else if ($nrOfCurrentReports < $nrOfPrecedentReports) {
            $dif = $nrOfPrecedentReports - $nrOfCurrentReports;
            $dif = '- ' . $dif . ' Reports';
            $arrow = 'uparrow';
        } else {
            $dif = 0;
            $dif = $dif . ' Reports';
            $arrow = 'uparrow';
        }

        array_push($changes, array("arrow" => $arrow, "diff" => $dif));

        if ($averageCurrentMarkerDistance != 0) {
            $dif = (1 - $averagePrecedentMarkerDistance / $averageCurrentMarkerDistance) * 100;

            $dif = number_format($dif, 0);
            if ($dif[0] == '-') {
                $dif = substr($dif, 1);
            }
        }
        else {
            $dif = '100';
        }
        if ($averageCurrentMarkerDistance > $averagePrecedentMarkerDistance) {
            $dif = '+ ' . $dif . '% Congestion';
            $arrow = 'downarrow';
        } else if ($averageCurrentMarkerDistance < $averagePrecedentMarkerDistance) {
            $dif = '- ' . $dif . '% Congestion';
            $arrow = 'uparrow';
        } else {
            $dif = $dif . '% Congestion';
            $arrow = 'uparrow';
        }
        array_push($changes, array("arrow" => $arrow, "diff" => $dif));

        $markersByCounty = array();
        if ($_SESSION['userID'] != -1) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://localhost:80/proiect/GaSM/app/api/markers/read/getByCounty.php?filter=" . $filter . '&country=' . $_SESSION['country'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache"
                ),
            ));

            $markersByCounty = curl_exec($curl);
            $markersByCounty = json_decode($markersByCounty, true);
            $err = curl_error($curl);

            curl_close($curl);
            if ($markersByCounty != null) {
                usort($markersByCounty, function ($a, $b) {
                    return $a['quantity'] - $b['quantity'];
                });
            }
        }
        $markersByRegion = array();
        if ($_SESSION['userID'] != -1) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://localhost:80/proiect/GaSM/app/api/markers/read/getByRegion.php?filter=" . $filter . '&county=' . $_SESSION['county'] . '&country=' . $_SESSION['country'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache"
                ),
            ));
            // echo curl_exec($curl);
            $markersByRegion = curl_exec($curl);
            $markersByRegion = json_decode($markersByRegion, true);
            $err = curl_error($curl);

            curl_close($curl);
            if ($markersByRegion != null) {
                usort($markersByRegion, function ($a, $b) {
                    return $a['quantity'] - $b['quantity'];
                });
            }
        }

        $this->view('statistics', ['plastics' => $plastics, 'papers' => $papers, 'glasses' => $glasses, 'metals' => $metals, 'garbageToShow' => $shownGarbage, 'timeFilter' => $filter, 'changes' => $changes, 'markersByCounty' => $markersByCounty, 'markersByRegion' => $markersByRegion]);
    }
}
