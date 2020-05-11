<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All campaigns</title>
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/upperPage.css">
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/campaign.css">
    <!--<link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/signup.css">-->
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/inputButtons.css">
</head>
<body class="bodyClass">
    <div class="top">      <!--top part-->
        <div>
            <img src="http://localhost:80/proiect/GaSM/app/images/logo.jpg" alt="Logo">
            <p>
                GaSM
            </p>
        </div>

        <div>
            
                <button onclick= "location.href = 'http://localhost/proiect/GaSM/public/'" class="D3Button" >
                    Home
                </button>
            
                
           
                <button onclick= "location.href = 'http://localhost/proiect/GaSM/public/Map'" class="D3Button">
                    Map
                </button>
            

            
                <button onclick= "location.href = 'http://localhost/proiect/GaSM/public/Statistics'" class="D3Button">
                    Statistics
                </button>

                <button onclick= "location.href = 'http://localhost/proiect/GaSM/public/Campaign/index/0'" class="D3Button">
                    Campaigns
                </button>
            
        </div>
    </div>

<div class="greenContainerAllCampaigns"> <!--all campaigns part, ar trebui sa am un contor si daca max(id)>atunci sa am un buton cu next page-->

<div id="greyContainer" class="greyContainer">
  
    

 <?php

 foreach($data as $row)
 {
     if(is_numeric($row))  ///am gasit indexul precedent unde s-a oprit pagina
         {
             $fostulIndex=$row;
            //unset($row);
         }
 }

 if(!isset($fostulIndex))
    $fostulIndex=0;
$counter=0;

if($_SESSION['loggedIn'])  echo '<script>   var loggedIN=1;  </script>';
    else  echo '<script>   var loggedIN=0;  </script>';
?>
<script>
var url='http://localhost/proiect/GaSM/public/api/campaigns/allcampaignsfrom/';
var idValue=<?php echo $fostulIndex; ?>;
var counter=0;
url=url.concat(idValue);


fetch(url).
               then(response=>response.json()).then(data=>{    var string="";
                                                               string+='<h1 class="textHCampaign">All campaigns</h1><form  id="back" name="backToCreateCampaign" action="http://localhost/proiect/GaSM/public/Campaign/" method="post" class="greyContainerAllCampaigns"><button  class="controlButton" type="submit" id="details" name="startYourCampaign">Start yours!</button></form>';
                                                               for(var oneCampaign of data)
                                                                 {
                                                                   //console.log(oneCampaign['name']);
                                                                   string+='<h3 class="campaignNameText">' + oneCampaign['name'] + '</h3>';

                                                                   string+='<form  id="details';
                                                                   string=string.concat(oneCampaign['id']);
                                                                   string+='"name="detailsForm" action="http://localhost/proiect/GaSM/public/Campaign/details/';
                                                                   string=string.concat(oneCampaign['id']);
                                                                    
                                                                   string+='" method="post" class="greyContainerAllCampaigns"> <button  class="submitButton" type="submit"  name="Details">Details</button></form>  <form   name="likeForm" method="post" class="greyContainerAllCampaigns">    <button  class="submitButton" type="button" id="like';
                                                                   string=string.concat(oneCampaign['id']);
                                                                   string+='" name="Like">Like</button></form>';

                                                                   string+='<form id="commentForm';
                                                                   string=string.concat(oneCampaign['id']);
                                                                   string+='" name="commentForm"  method="post"  class="greyContainerAllCampaigns"><input id="comment';
                                                                   string=string.concat(oneCampaign['id']);
                                                                   string+='" name="CommentContent" maxlength="250" title="max 250 alphanumeric and ,.?: etc chars" required pattern=' +  '\'[A-Za-z0-9 .,!?:\\[\\]()\"\\-+]+\''   +'class="inputBox" type="text"  placeholder="write here"><button  id="commentB';
                                                                   string=string.concat(oneCampaign['id']);
                                                                   string+='" class="submitButton" type="submit"  name="Comment">Comment</button></form>';

                                                                   var idCampanie=oneCampaign['id'];
                                                                   
                                                                   //console.log(data.length);
                                                                   if((counter%2==1 && oneCampaign['id']>2)||(counter==data.length-1 && oneCampaign['id']>=3)) // si ca sa nu apara butonul de back daca am doar 1 campanie 
                                                                  {
                                                                    if(oneCampaign['id']%2==0)     // ca sa nu ajunga la index -1 si sa dea not found
                                                                       var valParam=oneCampaign['id']-4;
                                                                    else {
                                                                           
                                                                           var valParam=oneCampaign['id']-3; 

                                                                         }  

                                                                   string+='<form action="http://localhost/proiect/GaSM/public/Campaign/index/';
                                                                   string=string.concat(valParam);
                                                                   string+='" method="post" class="greyContainerAllCampaigns">    <button  class="controlButton" type="submit" id="prevPage" name="PreviousPage"><</button></form>  ';
                                                                  }
                                                                   
                                                                  counter++;

                                                                   if(oneCampaign['id']-idValue>1 && counter<=data.length-1)
                                                                   {
                                                                     string+='<form action="http://localhost/proiect/GaSM/public/Campaign/index/';
                                                                     string=string.concat(oneCampaign['id']);
                                                                     string+='" method="post" class="greyContainerAllCampaigns">    <button  class="controlButton" type="submit" id="nextPage" name="NextPage">></button></form>  ';
                                                                     break;
                                                                   }
                                                                 
                                                                 
                                                                 }

                                                                 
                                                                 document.getElementById("greyContainer").innerHTML=string;

                                                                 if(counter==1)  //counter numara daca am 1 sau 2 campanii pe pagina, daca am 1 am nevoie doar de cate 1 script de like si 1 de comentarii
                                                                 {
                                                                   var idGenerat=idValue+1;

                                                                   var url;
                                                                   
                                                                   document.getElementById('like'+idGenerat).addEventListener("click", likeFunction);
                 
                                                                   function likeFunction() 
                                                                   {

                                                                        url='http://localhost/proiect/GaSM/public/api/campaigns/like';
                                                                        if(loggedIN==1) {
                                                                        alert ("You liked this campaign!");
                                             
                                                                        fetch(url, {
                                                                        method: 'POST',
                                                                        headers: {'Content-Type':'application/x-www-form-urlencoded'}, 
                                                                        body: 'campaignID='  + idGenerat });
                                                                              }  
                                                                   else alert ("Trebuie sa fiti logat pt. a da like-uri!");
  
                                                                   return false;
                                                                    }

                                                                    //////////////////////////////////////////
                                                                    var dataString='campaignID='  + idGenerat +   '&userID=' + <?php echo $_SESSION['userID']; ?>+ '&CommentContent=';
                                                                  
                                                                   
                                                                   document.getElementById('commentForm'+idGenerat).addEventListener("submit", commentFunction);
                 
                                                                   function commentFunction(e) 
                                                                   {
                                                                    url='http://localhost/proiect/GaSM/public/api/campaigns/comment';
                                                                    dataString=dataString.concat(document.getElementById('comment' + idGenerat ).value);
                                                                    e.preventDefault();
                                                                        if(loggedIN==1) {
                                                                        alert ("You left a comment!");
                                             
                                                                        fetch(url, {
                                                                        method: 'POST',
                                                                        headers: {'Content-Type':'application/x-www-form-urlencoded'}, 
                                                                        body: dataString });
                                                                              }  
                                                                   else alert ("Trebuie sa fiti logat pt. a comenta!");
  
                                                                   return false;
                                                                    }
                                                                 }
                                                                 else
                                                                 {
                                                                   var idGenerat=idValue+1;
                                                                   
                                                                   var url;
                                                                   
                                                                   document.getElementById('like'+idGenerat).addEventListener("click", likeFunction1);
                                                                   var idGeneratPrim=idGenerat;  //idGenerat va fi cu 2 nr mai mare tot timpul dupa ce se incarca JS, dar daca dau like la prima chestie de pe pag vreau sa dau like la campania cu id-ul mai mic ca 1 ca ultima campanie de pe pagina afisata
                 
                                                                   function likeFunction1() 
                                                                   {
                                                                        url='http://localhost/proiect/GaSM/public/api/campaigns/like';
                                                                        
                                                                        if(loggedIN==1) {
                                                                        alert ("You liked this campaign!");
                                             
                                                                        fetch(url, {
                                                                        method: 'POST',
                                                                        headers: {'Content-Type':'application/x-www-form-urlencoded'}, 
                                                                        body: 'campaignID='  + idGeneratPrim });
                                                                              }  
                                                                   else alert ("Trebuie sa fiti logat pt. a da like-uri!");
  
                                                                   return false;
                                                                    }
                                                                    //////////////////////////////

                                                                    var dataString;
                                                                    
                                                                   document.getElementById('commentForm'+idGenerat).addEventListener("submit", commentFunction1);
                 
                                                                   function commentFunction1(e) 
                                                                   {
                                                                    dataString='campaignID='  + idGeneratPrim +   '&userID=' + <?php echo $_SESSION['userID']; ?> + '&CommentContent='; 
                                                                    dataString=dataString.concat(document.getElementById('comment' + idGeneratPrim ).value);
                                                                    url='http://localhost/proiect/GaSM/public/api/campaigns/comment';
                                                                    //console.log(dataString);
                                                                    e.preventDefault();
                                                                        if(loggedIN==1) {
                                                                        
                                        
                                                                        fetch(url, {
                                                                        method: 'POST',
                                                                        headers: {'Content-Type':'application/x-www-form-urlencoded'}, 
                                                                        body: dataString });
                                                                        alert ("You left a comment!");
                                                                        document.getElementById('comment' + idGeneratPrim ).value="";   
                                                                            }  
                                                                   else alert ("Trebuie sa fiti logat pt. a comenta!");
  
                                                                   return false;
                                                                    }
                                                                    
                                                                    //////////////////////////////
                                                                    idGenerat=idGenerat+1;
                                                                   
                                                                    
                                                                   
                                                                   document.getElementById('like'+idGenerat).addEventListener("click", likeFunction2);
                 
                                                                   function likeFunction2() 
                                                                   {
                                                                        url='http://localhost/proiect/GaSM/public/api/campaigns/like';
                                                                        if(loggedIN==1) {
                                                                        alert ("You liked this campaign!");
                                             
                                                                        fetch(url, {
                                                                        method: 'POST',
                                                                        headers: {'Content-Type':'application/x-www-form-urlencoded'}, 
                                                                        body: 'campaignID='  + idGenerat });
                                                                              }  
                                                                   else alert ("Trebuie sa fiti logat pt. a da like-uri!");
  
                                                                   return false;
                                                                    }

                                                                  //////////////////////////////////////////////////////

                                                                  document.getElementById('commentForm'+idGenerat).addEventListener("submit", commentFunction2);
                 
                                                                function commentFunction2(e) 
                                                                {
                                                                  dataString='campaignID='  + idGenerat +   '&userID=' + <?php echo $_SESSION['userID']; ?> + '&CommentContent='; 
                                                                 dataString=dataString.concat(document.getElementById('comment' + idGenerat ).value);
                                                                 url='http://localhost/proiect/GaSM/public/api/campaigns/comment';
                                                                 //console.log(dataString);
                                                                 e.preventDefault();
                                                                 if(loggedIN==1) {
                      

                                                                 fetch(url, {
                                                                 method: 'POST',
                                                                 headers: {'Content-Type':'application/x-www-form-urlencoded'}, 
                                                                 body: dataString });
                                                                 alert ("You left a comment!");
                                                                 document.getElementById('comment' + idGenerat ).value="";
                                                                 }  
                                                                  else alert ("Trebuie sa fiti logat pt. a comenta!");

                                                                  return false;
                                                                }
                                                                 }      
                                                          })

</script>

     
</div>  


</div>


</body>
</html>