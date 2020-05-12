<?php

include_once '../config/Database.php';
include_once '../models/CampaignModel.php';
include_once '../config/Response.php';


$campaignRoutes=
[

    [
        "route" => "campaigns/details/:id",
        "method" => "GET",
        "handler" => function ($req)
           {
            $aCampaign=new CampaignModel();

            $campaignId=$req['params']['id'];  
        
            $returnedArray = array();
            $returnedArray=$aCampaign->getFullDetailsOfACampaign($campaignId);
            

            if(!$returnedArray)
              {
                Response::text("No such campaign found");
                
              }
            else
            {
                Response::status(200);
                Response::json($returnedArray);
            }  
           }
        ],

        [
            "route" => "campaigns/comments/:id",
            "method" => "GET",
            "handler" => function ($req)
            {
                $aCampaign=new CampaignModel();

                $campaignId=$req['params']['id'];
            
                $returnedArray = array();
                $returnedArray=$aCampaign->getCommentsOfACampaign($campaignId);
            
                if(!$returnedArray)
                  {
                   
                        Response::text("The specified campaign has no comments yet.");
                   
                  }
                else
                {
                    Response::status(200);
                    Response::json($returnedArray);
                } 
            }

        ],

         [
             
            "route" => "campaigns/like",
            "method" => "POST",
            "middlewares" => ["IsLoggedIn"],
            "handler" => function ($req)
            {
                $aCampaign=new CampaignModel();

               if(isset($_POST['campaignID'])) 
                {
                $campaignID=$_POST['campaignID'];
                //print_r($req);
        
                
                     if($aCampaign->doesItExist($campaignID))
                     {
                         $aCampaign->addLike($campaignID);
                         Response::status(200);
                         Response::text("Like added succesfully to the given campaign");
                     }
                     else
                     Response::text("Campania nu exista");
            
                }
                else  Response::text("Not enough parameters supplied");        
            }

         ],

         [
            "route" => "campaigns/comment",
            "method" => "POST",
            "middlewares" => ["IsLoggedIn"],
            "handler" => function ($req)
            {

                $aCampaign=new CampaignModel();

                if(isset($_POST['campaignID'],$_POST['CommentContent'],$_POST['userID']))
                {
                $campaignID=$_POST['campaignID'];
                $content=$_POST['CommentContent'];
                $userID=$_POST['userID'];
            
                //echo var_dump($_POST);
                if($aCampaign->isCommentValid($content))
                     {
                         if($aCampaign->doesItExist($campaignID))
                               {
                                   
                                   $aCampaign->addComment($campaignID,$content,$userID);
                                   Response::status(200);
                                   Response::text("Comment added to the given campaign");

                               }    
                         else Response::text("The given campaign does not exist");        
                     }
                else Response::text("Comment is not valid");

                }
                else Response::text("Not enough parameters supplied");
            }

        ],

        [
            "route" => "campaigns/add",
            "method" => "POST",
            "handler" => function ($req)
            {
                $aCampaign=new CampaignModel();
                

                if(isset($_POST['name'],$_POST['location'],$_POST['description']))
                {
                $aCampaign->name=$_POST['name'];
                $aCampaign->location=$_POST['location'];
                $aCampaign->description=$_POST['description'];

                if($aCampaign->isCampaignDataValid()) 
                {
                   if($aCampaign->isTheNameAvailable())
                   {
                      $aCampaign->storeIntoDB();  
                      Response::status(200);
                      Response::text("Campaign added succesfully");
                   }    
               else Response::text("There's already a campaign with that name");  
               }
              }
            else Response::text("Not enough parameters supplied");

            }
        ],

        [
            "route" => "campaigns/Allcampaigns",
            "method" => "GET",
            "handler" => function ($req)
            {
                $aCampaign=new CampaignModel();

                $returnedArray = array();
                $returnedArray=$aCampaign->getAllCampaigns();
            
                if(!$returnedArray)
                  {
                    Response::text("No campaings found");
                  }
                else
                {
                    Response::status(200);
                    Response::json($returnedArray);
                }  
            }
        ],


        [
            "route" => "campaigns/allcampaignsfrom/:id",
            "method" => "GET",
            "handler" => function ($req)
            {
                $aCampaign=new CampaignModel();

                $campaignId=$req['params']['id'];
                

                $returnedArray = array();
                $returnedArray=$aCampaign->getAllCampaignsFromIndexOnwards($campaignId);
            
                if(!$returnedArray)
                  {
                    
                        Response::text("No campaigns found from that index onwards");
                    
                  }
                else
                {
                    Response::status(200);
                    Response::json($returnedArray);
                }  

            }

        ]

        ];





?>