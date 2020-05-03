<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/x-www-form-urlencoded");

include_once '../../config/Database.php';
include_once '../../models/CampaignModel.php';

 if(isset($_GET['id']))
{
    $database = new Database();
    $db = $database->connect();

    $aCampaign=new CampaignModel();

    $campaignId=$_GET['id'];

    $returnedArray = array();
    $returnedArray=$aCampaign->getFullDetailsOfACampaign($campaignId);

    if(!$returnedArray)
      {
        echo json_encode(
            array('campaign' => 'No such campaign found')
        );
      }
    else
    {
        echo json_encode($returnedArray);
    }  

}

?>