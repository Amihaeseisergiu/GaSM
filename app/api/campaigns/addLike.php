<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/x-www-form-urlencoded");

include_once '../../config/Database.php';
include_once '../../models/CampaignModel.php';

                                                                             //in postman trebuie dati param astia in body si type=form/data ca sa mearga
if(isset($_POST['campaignID']))  //daca se dau acesti 3 parametri
{
    $aCampaign=new CampaignModel();

    $campaignID=$_POST['campaignID'];
    

    //echo var_dump($_POST);
    
         if($aCampaign->doesItExist($campaignID))
         {
             $aCampaign->addLike($campaignID);
             echo "Like added succesfully to the given campaign";
         }
         else echo "Campania nu exista";
       
     
    
}
else
{
    echo "The specified parameters were not sent";

}

?>