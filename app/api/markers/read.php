<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if(isset($_GET['filter']))
{
    $con = mysqli_connect("Localhost", "root", "", "tw");
    $markers = array();
    $query = $con->prepare("SELECT * from markers where country = ?");
    $query->bind_param("s", $_GET['country']);

    $query->execute();
    $result = $query->get_result();

    for ($i = 1; $i <= $result->num_rows; $i++) {
        $row = $result->fetch_assoc();
        array_push($markers, $row);
    }

    $con->close();
    echo json_encode($markers);
}

?>