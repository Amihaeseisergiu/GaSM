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
        $plastics = array();
        $papers = array();
        $metals = array();
        $glasses = array();
        if (!array_key_exists('message',$markers)) {
            foreach ($markers as $marker) {
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
        $Pmarkers = json_decode($Pmarkers, true); //because of true, it's in an array
        $err = curl_error($curl);

        curl_close($curl);

        $Pplastics = array();
        $Ppapers = array();
        $Pmetals = array();
        $Pglasses = array();
        if (!array_key_exists('message',$Pmarkers)) {
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
        }


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
        $nrOfCurrentReports = count($markers);
        $nrOfPrecedentReports = count($Pmarkers);
        $changes = array();
        if ($nrOfCurrentReports > $nrOfPrecedentReports) {
            $dif = $nrOfCurrentReports - $nrOfPrecedentReports;
            $dif = '+ ' . $dif . ' Reports';
            $arrow = 'downarrow';
        } else {
            $dif = $nrOfPrecedentReports - $nrOfCurrentReports;
            $dif = '- ' . $dif . ' Reports';
            $arrow = 'uparrow';
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://localhost:80/proiect/GaSM/app/api/markers/read/getByCounty.php?filter=" . $filter,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));

        $markersByCounty = curl_exec($curl);
        $markersByCounty = json_decode($markersByCounty, true); //because of true, it's in an array
        $err = curl_error($curl);

        curl_close($curl);
        usort($markersByCounty, function ($a, $b) {
            return $a['quantity'] - $b['quantity'];
        });

        $markersByRegion = array();
        if ($_SESSION['userID'] != -1) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://localhost:80/proiect/GaSM/app/api/markers/read/getByRegion.php?filter=" . $filter . '&county=' . $_SESSION['county'],
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
            $markersByRegion = json_decode($markersByRegion, true); //because of true, it's in an array
            $err = curl_error($curl);

            curl_close($curl);
        }
        usort($markersByRegion, function ($a, $b) {
            return $a['quantity'] - $b['quantity'];
        });


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://localhost:80/proiect/GaSM/app/api/markers/read/getCSV.php?filter=" . $filter . '&country=' . $_SESSION['country'] . '&city=' . $_SESSION['city'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));
        $CSVString = curl_exec($curl);
        $CSVString = json_decode($CSVString, true);
        $err = curl_error($curl);

        curl_close($curl);


        array_push($changes, array("arrow" => $arrow, "diff" => $dif));
        $this->view('statistics', ['plastics' => $plastics, 'papers' => $papers, 'glasses' => $glasses, 'metals' => $metals, 'garbageToShow' => $shownGarbage, 'timeFilter' => $filter, 'changes' => $changes, 'markersByCounty' => $markersByCounty, 'markersByRegion' => $markersByRegion, 'CSVString' => $CSVString]);
    }
}
