<?php

class Marker
{
    private $con;
    public $id;
    public $latitude;
    public $longitude;
    public $trashType;
    public $userId;
    public $time;
    public $country;
    public $county;
    public $city;


    public function __construct($db)
    {
        $this->con = $db;
    }

    public function getAll()
    {
        $query = $this->con->prepare("SELECT * from tw.markers");
        $query->execute();
        return $query;
    }

    public function getTrash($filter = '', $city, $country)
    {
        if ($city == 'none') {
            if ($filter == "Last Week") {
                $query = $this->con->prepare("SELECT * FROM tw.markers
                WHERE time <= CURDATE() AND time >= CURDATE() - INTERVAL 7 DAY");
            } else if ($filter == "Last Month") {
                $query = $this->con->prepare("SELECT * FROM tw.markers
                WHERE time <= CURDATE() AND time >= CURDATE() - INTERVAL 31 DAY");
            } else if ($filter == "Today") {
                $query = $this->con->prepare("SELECT * FROM tw.markers
                WHERE time >= CURDATE() AND time < CURDATE() + INTERVAL 1 DAY");
            } else {
                $query = $this->con->prepare("SELECT * from tw.markers");
            }
        } else {
            if ($filter == "Last Week") {
                $query = $this->con->prepare("SELECT * FROM tw.markers
                WHERE time <= CURDATE() AND time >= CURDATE() - INTERVAL 7 DAY and country = ? and city = ?");
            } else if ($filter == "Last Month") {
                $query = $this->con->prepare("SELECT * FROM tw.markers
                WHERE time <= CURDATE() AND time >= CURDATE() - INTERVAL 31 DAY and country = ? and city = ?");
            } else if ($filter == "Today") {
                $query = $this->con->prepare("SELECT * FROM tw.markers
                WHERE time >= CURDATE() AND time < CURDATE() + INTERVAL 1 DAY and country = ? and city = ?");
            } else {
                $query = $this->con->prepare("SELECT * from tw.markers where country = ? and city = ?");
            }
            $query->bindParam(1,  $country, PDO::PARAM_STR, 50);
            $query->bindParam(2,  $city, PDO::PARAM_STR, 50);
        }
        $query->execute();
        return $query;
    }

    public function getPrecedentTrash($filter = '', $city, $country)
    {
        $con = $this->con;
        if ($city == 'none') {
            if ($filter == "Last Week") {
                $query = $con->prepare("SELECT * FROM tw.markers
                WHERE time <= CURDATE() - INTERVAL 7 DAY AND time >= CURDATE() - INTERVAL 14 DAY");
            } else if ($filter == "Last Month") {
                $query = $con->prepare("SELECT * FROM tw.markers
                WHERE time <= CURDATE() - INTERVAL 31 DAY AND time >= CURDATE() - INTERVAL 62 DAY");
            } else if ($filter == "Today") {
                $query = $con->prepare("SELECT * FROM tw.markers
                WHERE time <= CURDATE() AND time >= CURDATE() - INTERVAL 1 DAY");
            } else {
                $query = $con->prepare("SELECT * from tw.markers");
            }
        } else {
            if ($filter == "Last Week") {
                $query = $con->prepare("SELECT * FROM tw.markers
                 WHERE time <= CURDATE() - INTERVAL 7 DAY AND time >= CURDATE() - INTERVAL 14 DAY and country = ? and city = ?");
            } else if ($filter == "Last Month") {
                $query = $con->prepare("SELECT * FROM tw.markers
               WHERE time <= CURDATE() - INTERVAL 31 DAY AND time >= CURDATE() - INTERVAL 62 DAY and country = ? and city = ?");
            } else if ($filter == "Today") {
                $query = $con->prepare("SELECT * FROM tw.markers
                WHERE time <= CURDATE() AND time >= CURDATE() + INTERVAL 1 DAY and country = ? and city = ?");
            } else {
                $query = $con->prepare("SELECT * from tw.markers where country = ? and city = ?");
            }
            $query->bindParam(1,  $country, PDO::PARAM_STR, 50);
            $query->bindParam(2,  $city, PDO::PARAM_STR, 50);
        }
        $query->execute();
        return $query;
    }

    public function getMarkersByCounty($filter = '')
    {
        $con = $this->con;
        if ($filter == "Last Week") {
            $query = $con->prepare("SELECT * FROM tw.markers
            WHERE time <= CURDATE() AND time >= CURDATE() - INTERVAL 7 DAY");
        } else if ($filter == "Last Month") {
            $query = $con->prepare("SELECT * FROM tw.markers
            WHERE time <= CURDATE() AND time >= CURDATE() - INTERVAL 31 DAY");
        } else if ($filter == "Today") {
            $query = $con->prepare("SELECT * FROM tw.markers
            WHERE time >= CURDATE() AND time < CURDATE() + INTERVAL 1 DAY");
        } else {
            $query = $con->prepare("SELECT * from tw.markers");
        }
        $query->execute();
        return $query;
    }

    public function getMarkersByRegion($filter = '', $county)
    {
        $con = $this->con;
        if ($county != 'none') {
            if ($filter == "Last Week") {
                $query = $con->prepare("SELECT * FROM tw.markers
            WHERE county = ? and time <= CURDATE() AND time >= CURDATE() - INTERVAL 7 DAY");
            } else if ($filter == "Last Month") {
                $query = $con->prepare("SELECT * FROM tw.markers
            WHERE county = ? and time <= CURDATE() AND time >= CURDATE() - INTERVAL 31 DAY");
            } else if ($filter == "Today") {
                $query = $con->prepare("SELECT * FROM tw.markers
            WHERE county = ? and time >= CURDATE() AND time < CURDATE() + INTERVAL 1 DAY");
            } else {
                $query = $con->prepare("SELECT * from tw.markers where county = ?");
            }
            $query->bindParam(1,  $county, PDO::PARAM_STR, 50);
            $query->execute();
            return $query;
        }
    }

    public function getCSVString($filter = '', $country, $city)
    {
        $con = $this->con;
        if ($city == 'none') {
            if ($filter == "Weekly") {
                $query = $con->prepare("SELECT * FROM markers
                WHERE time <= CURDATE() AND time >= CURDATE() - INTERVAL 7 DAY");
            } else if ($filter == "Monthly") {
                $query = $con->prepare("SELECT * FROM markers
                WHERE time <= CURDATE() AND time >= CURDATE() - INTERVAL 31 DAY");
            } else if ($filter == "Today") {
                $query = $con->prepare("SELECT * FROM markers
                WHERE time >= CURDATE() AND time < CURDATE() + INTERVAL 1 DAY");
            } else {
                $query = $con->prepare("SELECT * from tw.markers");
            }
        } else {
            if ($filter == "Weekly") {
                $query = $con->prepare("SELECT * FROM tw.markers
                WHERE time <= CURDATE() AND time >= CURDATE() - INTERVAL 7 DAY and country = ? and city = ?");
            } else if ($filter == "Monthly") {
                $query = $con->prepare("SELECT * FROM tw.markers
                WHERE time <= CURDATE() AND time >= CURDATE() - INTERVAL 31 DAY and country = ? and city = ?");
            } else if ($filter == "Today") {
                $query = $con->prepare("SELECT * FROM tw.markers
                WHERE time >= CURDATE() AND time < CURDATE() + INTERVAL 1 DAY and country = ? and city = ?");
            } else {
                $query = $con->prepare("SELECT * from tw.markers where country = ? and city = ?");
            }
            $query->bindParam(1,  $country, PDO::PARAM_STR, 50);
            $query->bindParam(2,  $city, PDO::PARAM_STR, 50);
        }
        $query->execute();
        return $query;
    }

    public function insert($marker)
    {
        session_start();
        $userId = $_SESSION['userID'];

        $con = $this->con;
        if ($userId > 0) {
            $query = $con->prepare("INSERT INTO tw.markers(`latitude`,
            `longitude`, `trash_type`, `user_id`, `time`, `country`, `county`, `city`, `neighborhood`) 
            VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, ?)");
            $query->bindParam(1, $marker['latitude'], PDO::PARAM_STR, 8);
            $query->bindParam(2, $marker['longitude'], PDO::PARAM_STR, 8);
            $query->bindParam(3, $marker['trashType'], PDO::PARAM_STR, 11);
            $query->bindParam(4, $userId, PDO::PARAM_INT);
            $query->bindParam(5, $marker['country'], PDO::PARAM_STR, 50);
            $query->bindParam(6, $marker['county'], PDO::PARAM_STR, 50);
            $query->bindParam(7, $marker['city'], PDO::PARAM_STR, 50);
            $query->bindParam(8, $marker['neighborhood'], PDO::PARAM_STR, 50);
            $query->execute();
        }
    }
}
