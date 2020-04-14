<?php
    if(isset($_POST['lat']) && isset($_POST['lng']) && strcmp(strval($_POST['type']), "") != 0)
    {
        $lat = doubleval($_POST['lat']);
        $lng = doubleval($_POST['lng']);
        $trashType = strval($_POST['type']);
        session_start();
        $userId = $_SESSION['userID'];

        if($userId > 0)
        {
            $con = mysqli_connect("Localhost", "root", "", "tw");

            $query = $con->prepare("INSERT INTO `markers`(`latitude`,
            `longitude`, `trash_type`, `user_id`, `time`) 
            VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)");
            $query->bind_param('ddsi', $lat, $lng, $trashType, $userId);
            $query->execute();

            $query->close();
            $con->close();
        }
    }
?>
