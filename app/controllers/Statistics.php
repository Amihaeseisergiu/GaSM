<?php

class Statistics extends Controller
{
    public function index($filter = '')
    {
        $marker = $this->model('Marker');
        if ($filter == "filterByWeek") {
            $markers = $marker->getTrash("weekly");
        } else if($filter == "filterByMonth") {
            $markers = $marker->getTrash("monthly");
        } else if($filter == "filterByDay") {
            $markers = $marker->getTrash("daily");
        }
         else {
            $markers = $marker->getTrash();
        }
        $plastics = array();
        $papers = array();
        $metals = array();
        $glasses = array();
        foreach ($markers as $marker) {
            $time = substr($marker->time, 0, strpos($marker->time, ' '));
            if($filter == 'filterByDay') {
                $time = date("Y-m-d H:00:00",strtotime($marker->time));
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
        $this->view('statistics', ['plastics' => $plastics, 'papers' => $papers, 'glasses' => $glasses, 'metals' => $metals]);
    }

}
