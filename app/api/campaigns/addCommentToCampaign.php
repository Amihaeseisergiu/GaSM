<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/x-www-form-urlencoded");

include_once '../../config/Database.php';
include_once '../../models/CampaignModel.php';

                                                                             //in postman trebuie dati param astia in body si type=form/data ca sa mearga
if(isset($_POST['campaignID'],$_POST['userID'],$_POST['CommentContent']))  //daca se dau acesti 3 parametri
{
    $aCampaign=new CampaignModel();

    $campaignID=$_POST['campaignID'];
    $content=$_POST['CommentContent'];
    $userID=$_POST['userID'];

    //echo var_dump($_POST);
    if($aCampaign->isCommentValid($content))
         {
             if($aCampaign->doesItExist($campaignID))
                   $aCampaign->addComment($campaignID,$content,$userID);
             else echo "The given campaign does not exist";        
         }
    else echo "Comment is not valid";
}
else
{
    echo "The specified parameters were not sent";

}

?>