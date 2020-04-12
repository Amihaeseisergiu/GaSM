<?php

class Statistics extends Controller
{
    public function index()
    {
        $marker = $this->model('Marker');
        $markers = $marker->getTrash();
        $plastics = array();
        $papers = array();
        $metals = array();
        $glasses = array();
        foreach ($markers as $marker) {
            if ($marker->trashType == 'plastic') {
                $ok = 0;
                for ($i = 0; $i < count($plastics); $i++) {
                    if ($plastics[$i]['time'] == substr($marker->time, 0, strpos($marker->time, ' ')) ) {
                        $plastics[$i]['quantity']++;
                        $ok = 1;
                    }
                }
                if ($ok == 0) {
                    array_push($plastics, array("time" => substr($marker->time, 0, strpos($marker->time, ' ')) , "quantity" => 1));
                }
            }
            else if ($marker->trashType == 'paper') {
                $ok = 0;
                for ($i = 0; $i < count($papers); $i++) {
                    if ($papers[$i]['time'] == substr($marker->time, 0, strpos($marker->time, ' ')) ) {
                        $papers[$i]['quantity']++;
                        $ok = 1;
                    }
                }
                if ($ok == 0) {
                    array_push($papers, array("time" => substr($marker->time, 0, strpos($marker->time, ' ')) , "quantity" => 1));
                }
            }
            else  if ($marker->trashType == 'glass') {
                $ok = 0;
                for ($i = 0; $i < count($glasses); $i++) {
                    if ($glasses[$i]['time'] == substr($marker->time, 0, strpos($marker->time, ' ')) ) {
                        $glasses[$i]['quantity']++;
                        $ok = 1;
                    }
                }
                if ($ok == 0) {
                    array_push($glasses, array("time" => substr($marker->time, 0, strpos($marker->time, ' ')) , "quantity" => 1));
                }
            }
            else if ($marker->trashType == 'metal') {
                $ok = 0;
                for ($i = 0; $i < count($metals); $i++) {
                    if ($metals[$i]['time'] == substr($marker->time, 0, strpos($marker->time, ' ')) ) {
                        $metals[$i]['quantity']++;
                        $ok = 1;
                    }
                }
                if ($ok == 0) {
                    array_push($metals, array("time" => substr($marker->time, 0, strpos($marker->time, ' ')) , "quantity" => 1));
                }
            }
        }
        $this->view('statistics', ['plastics' => $plastics, 'papers' => $papers, 'glasses' => $glasses, 'metals' => $metals]);
    }
}
