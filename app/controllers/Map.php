<?php

class Map extends Controller
{
    public function index()
    {
        $markers = $this->model('Marker')->getTrash();
        $this->view('map', ['markers' => $markers]);
    }
}
?>