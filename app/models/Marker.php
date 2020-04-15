<?php

class Marker
{
    public $latitude;
    public $longitude;
    public $trashType;
    public $userId;
    public $time;

    public function getTrash($filter = '')
    {
        $con = mysqli_connect("Localhost", "root", "", "tw");
        $markers = array();
        if ($_SESSION['userID'] == -1) {
            if ($filter == "weekly") {
                $query = $con->prepare("SELECT * FROM markers
                WHERE time <= CURDATE() AND time >= CURDATE() - INTERVAL 7 DAY");
            } else if ($filter == "monthly") {
                $query = $con->prepare("SELECT * FROM markers
                WHERE time <= CURDATE() AND time >= CURDATE() - INTERVAL 31 DAY");
            } else if ($filter == "daily") {
                $query = $con->prepare("SELECT * FROM markers
                WHERE time >= CURDATE() AND time < CURDATE() + INTERVAL 1 DAY");
            } else {
                $query = $con->prepare("SELECT * from markers");
            }
        } else {
            if ($filter == "weekly") {
                $query = $con->prepare("SELECT * FROM markers
                WHERE time <= CURDATE() AND time >= CURDATE() - INTERVAL 7 DAY and country = ? and city = ?");
            } else if ($filter == "monthly") {
                $query = $con->prepare("SELECT * FROM markers
                WHERE time <= CURDATE() AND time >= CURDATE() - INTERVAL 31 DAY and country = ? and city = ?");
            } else if ($filter == "daily") {
                $query = $con->prepare("SELECT * FROM markers
                WHERE time >= CURDATE() AND time < CURDATE() + INTERVAL 1 DAY and country = ? and city = ?");
            } else {
                $query = $con->prepare("SELECT * from markers where country = ? and city = ?");
            }
            $query->bind_param("ss",  $_SESSION['country'], $_SESSION['city']);
        }
        $query->execute();
        $result = $query->get_result();

        for ($i = 1; $i <= $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $marker = new Marker;
            $marker->latitude = $row['latitude'];
            $marker->longitude = $row['longitude'];
            $marker->trashType = $row['trash_type'];
            $marker->userId = $row['user_id'];
            $marker->time = $row['time'];

            $query2 = $con->prepare("SELECT * FROM users WHERE id = ?");
            $query2->bind_param('i', $marker->userId);
            $query2->execute();
            $row2 = $query2->get_result()->fetch_assoc();

            $marker->userName = $row2['name'];
            $marker->userCountry = $row2['country'];
            $marker->userCity = $row2['city'];

            $markers[$i] = $marker;
        }

        $con->close();
        return $markers;
    }
}
