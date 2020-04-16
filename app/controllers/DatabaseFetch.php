<?php
if(isset($_POST['type']) && !empty($_POST['type']))
{
    if(strcmp(strval($_POST['type']), "markers") == 0)
    {
        $con = mysqli_connect("Localhost", "root", "", "tw");
        $markers = array();

        $query = $con->prepare("SELECT * from markers");
        $query->execute();
        $result = $query->get_result();

        for ($i = 1; $i <= $result->num_rows; $i++) {
            $row = $result->fetch_assoc();

            $latitude = $row['latitude'];
            $longitude = $row['longitude'];
            $trashType = $row['trash_type'];
            $userId = $row['user_id'];
            $time = $row['time'];
            $country = $row['country'];
            $county = $row['county'];
            $city = $row['city'];

            $query2 = $con->prepare("SELECT * FROM users WHERE id = ?");
            $query2->bind_param('i', $userId);
            $query2->execute();
            $row2 = $query2->get_result()->fetch_assoc();

            $userName = $row2['name'];
            $userCountry = $row2['country'];
            $userCity = $row2['city'];

            $marker = array(
                "latitude" => $latitude,
                "longitude" => $longitude,
                "trashType" => $trashType,
                "userId" => $userId,
                "time" => $time,
                "country" => $country,
                "county" => $county,
                "city" => $city,
                "userName" => $userName,
                "userCountry" => $userCountry,
                "userCity" => $userCity,
            );

            array_push($markers, $marker);
        }

        $con->close();
        echo json_encode($markers);
    }
}
?>