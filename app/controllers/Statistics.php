<?php

class Statistics extends Controller
{
    public function index($filter = '', $shownGarbageTypes = '')
    {
        $marker = $this->model('Marker');
        $markers = $marker->getTrash($filter);
        $plastics = array();
        $papers = array();
        $metals = array();
        $glasses = array();
        foreach ($markers as $marker) {
            $time = substr($marker->time, 0, strpos($marker->time, ' '));
            if ($filter == 'Today') {
                $time = date("Y-m-d H:i:00", strtotime($marker->time));
            }
            if ($marker->trashType == 'plastic') {
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
            } else if ($marker->trashType == 'paper') {
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
            } else  if ($marker->trashType == 'glass') {
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
            } else if ($marker->trashType == 'metal') {
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


        $Pmarkers = $marker->getPrecedentTrash($filter);
        $Pplastics = array();
        $Ppapers = array();
        $Pmetals = array();
        $Pglasses = array();
        foreach ($Pmarkers as $marker) {
            $time = substr($marker->time, 0, strpos($marker->time, ' '));
            if ($filter == 'Today') {
                $time = date("Y-m-d H:i:00", strtotime($marker->time));
            }
            if ($marker->trashType == 'plastic') {
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
            } else if ($marker->trashType == 'paper') {
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
            } else  if ($marker->trashType == 'glass') {
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
            } else if ($marker->trashType == 'metal') {
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
        $markersByCounty = $marker->getMarkersByCounty($filter);
        usort($markersByCounty, function ($a, $b) {
            return $a['quantity'] - $b['quantity'];
        });
        $markersByRegion = array();
        if ($_SESSION['userID'] != -1) {
            $markersByRegion = $marker->getMarkersByRegion($filter);
        }
        usort($markersByRegion, function ($a, $b) {
            return $a['quantity'] - $b['quantity'];
        });
        $CSVString = $marker->getCSVString($filter);
        array_push($changes, array("arrow" => $arrow, "diff" => $dif));
        $this->view('statistics', ['plastics' => $plastics, 'papers' => $papers, 'glasses' => $glasses, 'metals' => $metals, 'garbageToShow' => $shownGarbage, 'timeFilter' => $filter, 'changes' => $changes, 'markersByCounty' => $markersByCounty, 'markersByRegion' => $markersByRegion, 'CSVString' =>$CSVString]);
    }
}
