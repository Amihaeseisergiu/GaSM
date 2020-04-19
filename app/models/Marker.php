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

    public function getTrash($filter = '')
    {
        session_start();
        if ($_SESSION['userID'] == -1) {
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
            $query->bindParam(1,  $_SESSION['country'], PDO::PARAM_STR, 50);
            $query->bindParam(2,  $_SESSION['city'], PDO::PARAM_STR, 50);
        }
        $query->execute();
        return $query;

       /* $result = $query->get_result();

        for ($i = 1; $i <= $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $marker = array();
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
        return $markers;*/
    }

    public function getPrecedentTrash($filter = '')
    {
        $con = $this->con;
        $markers = array();
        session_start();
        if ($_SESSION['userID'] == -1) {
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
                $query = $con->prepare("SELECT * from markers");
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
            $query->bindParam(1,  $_SESSION['country'], PDO::PARAM_STR, 50);
            $query->bindParam(2,  $_SESSION['city'], PDO::PARAM_STR, 50);
        }
        $query->execute();
        return $query;
     /*   $result = $query->get_result();

        for ($i = 1; $i <= $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $marker = array();
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
        return $markers;*/
    }

    public function getMarkersByCounty($filter = '')
    {
        $markersByCounty = array();
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

       /* $result = $query->get_result();
        for ($i = 1; $i <= $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
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

        return $markersByCounty;*/
    }

    public function getMarkersByRegion($filter = '')
    {
        $markersByRegion = array();
        session_start();
        $con = $this->con;
        if ($filter == "Weekly") {
            $query = $con->prepare("SELECT * FROM tw.markers
            WHERE county = ? and time <= CURDATE() AND time >= CURDATE() - INTERVAL 7 DAY");
        } else if ($filter == "Monthly") {
            $query = $con->prepare("SELECT * FROM tw.markers
            WHERE county = ? and time <= CURDATE() AND time >= CURDATE() - INTERVAL 31 DAY");
        } else if ($filter == "Today") {
            $query = $con->prepare("SELECT * FROM tw.markers
            WHERE county = ? and time >= CURDATE() AND time < CURDATE() + INTERVAL 1 DAY");
        } else {
            $query = $con->prepare("SELECT * from tw.markers where county = ?");
        }
        $query->bindParam(1,  $_SESSION['county'], PDO::PARAM_STR, 50);
        $query->execute();
        return $query;
       /* $result = $query->get_result();
        for ($i = 1; $i <= $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $ok = 0;
            for ($j = 0; $j < count($markersByRegion); $j++) {
                if ($markersByRegion[$j]['city'] == $row['city']) {
                    $markersByRegion[$j]['quantity']++;
                    $ok = 1;
                }
            }
            if ($ok == 0) {
                array_push($markersByRegion, array("city" => $row['city'], "quantity" => 1));
            }
        }
        return $markersByRegion;*/
    }

    public function getCSVString($filter = '')
    {
        $con = $this->con;
        session_start();
        if ($_SESSION['userID'] == -1) {
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
                $query = $con->prepare("SELECT * from markers");
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
            $query->bindParam(1,  $_SESSION['country'], PDO::PARAM_STR, 50);
            $query->bindParam(2,  $_SESSION['city'], PDO::PARAM_STR, 50);
        }
        $query->execute();
        return $query;

        /*$CSVString = '';
        $result = $query->get_result();
        for ($i = 1; $i <= $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            $CSVString = $CSVString . $row['id'] . ', ' . $row['latitude'] . ', ' . $row['longitude'] . ', ' . $row['trash_type'] . ', ' . $row['user_id'] . ', ' . $row['time'] . ', ' . $row['country'] . ', ' . $row['county'] . ', ' . $row['city'] . "\r\n";
        }
        return $CSVString;*/
    }

    public function insert($marker)
    {
        session_start();
        $userId = $_SESSION['userID'];

        $con = $this->con;
        if($userId > 0)
        {
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