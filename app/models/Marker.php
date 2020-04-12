<?php

class Marker
{
    public $latitude;
    public $longitude;
    public $trashType;
    public $userId;
    public $time;

    public function getTrash()
    {
        $con = mysqli_connect("Localhost", "root", "", "tw");
        $markers = array();
        $query = $con->prepare("SELECT * from markers");
        $query->execute();
        $result = $query->get_result();

        for ($i = 1; $i <= $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $marker = new Marker;
            $marker->latitude = $row['latitude'];
            $marker->longitude = $row['longitude'];
            $marker->trashType = $row['trash_type'];
            $marker->time = $row['time'];
            $markers[$i] = $marker;
        }
        $con->close();
        return $markers;
    }
}
