<?php
    if(isset($_POST['lat']) && isset($_POST['lng']) && strcmp(strval($_POST['type']), "") != 0)
    {
        $lat = doubleval($_POST['lat']);
        $lng = doubleval($_POST['lng']);
        $trashType = strval($_POST['type']);
        $country = $_POST['country'];
        $city = $_POST['city'];
        $county = $_POST['county'];
        session_start();
        $userId = $_SESSION['userID'];

        if($userId > 0)
        {
            $con = mysqli_connect("Localhost", "root", "", "tw");

            $query = $con->prepare("INSERT INTO `markers`(`latitude`,
            `longitude`, `trash_type`, `user_id`, `time`, `country`, `county`, `city`) 
            VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?)");
            $query->bind_param('ddsisss', $lat, $lng, $trashType, $userId, $country, $county, $city);
            $query->execute();

            $query->close();
            $con->close();
        }
    }
?>
