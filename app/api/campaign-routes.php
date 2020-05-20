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
                Response::status(200);  
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
                        Response::status(200);
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

              
                $campaignID=$req['payload']['campaignID'];
                //print_r($req);
        
                
                     if($aCampaign->doesItExist($campaignID))
                     {
                         $aCampaign->addLike($campaignID);
                         Response::status(200);
                         Response::text("Like added succesfully to the given campaign");
                     }
                     else
                     {
                      Response::status(400);
                      Response::text("Campaign doesn't exist");
                     }
             
            }

         ],

         [
            "route" => "campaigns/comment",
            "method" => "POST",
            "middlewares" => ["IsLoggedIn"],
            "handler" => function ($req)
            {

                $aCampaign=new CampaignModel();

                $campaignID=$req['payload']['campaignID'];
                $content=$req['payload']['CommentContent'];
                $userID=$req['payload']['userID'];
            
                //echo var_dump($_POST);
                if($aCampaign->isCommentValid($content))
                     {
                         if($aCampaign->doesItExist($campaignID))
                               {
                                   
                                   $aCampaign->addComment($campaignID,$content,$userID);
                                   Response::status(200);
                                   Response::text("Comment added to the given campaign");

                               }    
                         else {
                                Response::status(400);
                                Response::text("The given campaign does not exist");
                              }        
                     }
                else {
                     Response::status(401);
                     Response::text("Comment is not valid");
                    }

            }

        ],

        [
            "route" => "campaigns",
            "method" => "POST",
            "handler" => function ($req)
            {
                $aCampaign=new CampaignModel();
                
                $aCampaign->name=$req['payload']['name'];
                $aCampaign->location=$req['payload']['location'];
                $aCampaign->description=$req['payload']['description'];

                if($aCampaign->isCampaignDataValid()) 
                {
                   if($aCampaign->isTheNameAvailable())
                   {
                      $aCampaign->storeIntoDB();  
                      Response::status(200);
                      Response::text("Campaign added succesfully");
                   }    
               else {
                     Response::text("There's already a campaign with that name");
                     Response::status(400);
                    }
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
                        Response::status(200);
                        Response::text("No campaigns found from that index onwards");
                    
                  }
                else
                {
                    Response::status(200);
                    Response::json($returnedArray);
                }  

            }

        ],

        [
            "route" => "campaigns",
            "method" => "GET",
            "handler" => function ($req)
            {
                $aCampaign=new CampaignModel();

                $returnedArray = array();
                $returnedArray=$aCampaign->getAllCampaigns();
            
                if(!$returnedArray)
                  {
                    Response::status(200);
                    Response::text("No campaings found");
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