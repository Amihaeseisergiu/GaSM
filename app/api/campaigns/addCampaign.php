<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/x-www-form-urlencoded");

include_once '../../config/Database.php';
include_once '../../models/CampaignModel.php';


if(isset($_POST['name'],$_POST['location'],$_POST['description']))  //daca se dau acesti 3 parametri
{
    $aCampaign=new CampaignModel();

    $aCampaign->name=$_POST['name'];
    $aCampaign->location=$_POST['location'];
    $aCampaign->description=$_POST['description'];

    if($aCampaign->isCampaignDataValid()) 
      {
        if($aCampaign->isTheNameAvailable())
          {
              $aCampaign->storeIntoDB();  //asta o fac dupa ce fac validare pt nume si pt toate datele in controller
              echo "Campaign added succesfully";
          }    
        else echo "There's already a campaign with that name";  
      }
    else echo "The data entered is not valid";
    
}
else
{
    echo "The specified parameters were not sent";

}

?>