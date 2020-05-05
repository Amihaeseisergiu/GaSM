<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/x-www-form-urlencoded");

include_once '../../config/Database.php';
include_once '../../models/CampaignModel.php';

                                                                             //in postman trebuie dati param astia in body si type=form/data ca sa mearga
if(isset($_POST['campaignID'],$_POST['userID'],$_POST['contentOfComment']))  //daca se dau acesti 3 parametri
{
    $aCampaign=new CampaignModel();

    $campaignID=$_POST['campaignID'];
    $content=$_POST['contentOfComment'];
    $userID=$_POST['userID'];

    echo var_dump($_POST);
    $aCampaign->addComment($campaignID,$content,$userID);  //asta o fac dupa ce fac validare pt nume si pt toate datele in controller
    
    echo "Comment added succesfully to the given campaign";
}
else
{
    echo "The specified parameters were not sent";

}

?>