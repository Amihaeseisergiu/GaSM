<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/x-www-form-urlencoded");

include_once '../../config/Database.php';
include_once '../../models/CampaignModel.php';


    $database = new Database();
    $db = $database->connect();

    $aCampaign=new CampaignModel();

    $returnedArray = array();
    $returnedArray=$aCampaign->getAllCampaigns();

    if(!$returnedArray)
      {
        echo json_encode(
            array('campaign' => 'No campaigns found')
        );
      }
    else
    {
        echo json_encode($returnedArray);
    }  



?>