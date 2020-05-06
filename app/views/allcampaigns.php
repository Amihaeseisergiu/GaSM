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

<div class="greyContainer">
  
    <h1 class="textHCampaign">All campaigns</h1>

    <form  id="back" name="backToCreateCampaign" action="http://localhost/proiect/GaSM/public/Campaign/" method="post" class="greyContainerAllCampaigns">
    <button  class="controlButton" type="submit" id="details" name="startYourCampaign">Start yours!</button>
    
  </form>

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
   foreach($data as $row)  
    {
    if(!is_numeric($row))    
    {   
        echo '<h3 class="campaignNameText">' . $row['name'] . '</h3>';
        
    echo '<form  id="details' . $row['id'] . '"name="detailsForm" action="http://localhost/proiect/GaSM/public/Campaign/details/' . $row['id'] . '" method="post" class="greyContainerAllCampaigns">
    <button  class="submitButton" type="submit" id="details" name="Details">Details</button>
    
  </form>  

  <form id="like"  name="likeForm" method="post" class="greyContainerAllCampaigns">    
    <button  class="submitButton" type="button" id="like' . $row['id'] . '" name="Like">Like</button>
    </form>

  <form id="commentForm' . $row['id'] . '" name="commentForm"  method="post"  class="greyContainerAllCampaigns">
    <input id="comment' . $row['id'] . '" name="CommentContent" max="250" title="max 250 alphanumeric and ,.?: etc chars" required pattern='.' \'[A-Za-z0-9 .,!?:\[\]()"-+]+\' ' .'class="inputBox" type="text"  placeholder="write here">
    <button  id="commentB' . $row['id'] . '" class="submitButton" type="submit"  name="Comment">Comment</button>
  </form>';
////////////////////////////////////////////////////////////////////////////////////////////////
  // script pt a nu da refresh la like
  echo '<script type="text/javascript">
              document.getElementById("like' . $row['id'] . '").addEventListener("click", likeFunction);
                 
              function likeFunction() {';

                  

                  if($_SESSION['loggedIn']) {
                                              echo 'alert ("You liked this campaign!");';
                                              echo '
                                                fetch(\'http://localhost/proiect/GaSM/app/api/campaigns/addLike.php' . '\', {
                                                method: \'POST\',
                                                headers: {\'Content-Type\':\'application/x-www-form-urlencoded\'}, 
                                                body: \'campaignID='  . $row['id'] . '\' });';
                                            }  
                  else echo 'alert ("Trebuie sa fiti logat pt. da like-uri!");';

                  echo '
                return false;
              }

              </script>';      
  

            //listen-ul va fi facut pe form, la submit-ul ei aici.
            //la submit pt comment-uri valide va goli campul de comentarii, la cele invalide il va lasa asa  
            echo '<script>

            document.getElementById("commentForm' . $row['id'] . '").addEventListener("submit", commentFunction);
            
              function commentFunction(e) {e.preventDefault();';

                
                  echo 'var dataString=\'campaignID='  . $row['id'] .   '&userID=' . $_SESSION['userID'] . '&CommentContent=\';
                  dataString=dataString.concat(document.getElementById(\'comment' . $row['id'] . '\').value);';

                  if($_SESSION['loggedIn']) {
                                              echo 'alert ("You left a comment!");';
                                              echo '
                                                fetch(\'http://localhost/proiect/GaSM/app/api/campaigns/addCommentToCampaign.php' . '\', {
                                                method: \'POST\',
                                                headers: {\'Content-Type\':\'application/x-www-form-urlencoded\'}, 
                                                body: dataString });
                                                document.getElementById(\'comment' . $row['id'] . '\').value="";';
                                            }  
                  else echo 'alert ("Trebuie sa fiti logat pt. a comenta!");';

                  echo '
                return false;
              
              
            };
            
        </script>';

 
//////////////////////////////////////////////////////////////////////////////////////////////
  if(($counter%2==1 && $row['id']>2)||($counter+1==sizeof($data)-1))//daca suntem la elementul 3(0,1,2,3) si id-ul e mai mare de 2, sau urm element e capatul sirului($fostulIndex)
  {
    if($row['id']%2==0)     // ca sa nu ajunga la index -1 si sa dea not found
       $valParam=$row['id']-4;
      else $valParam=$row['id']-3; 

    echo '<form action="http://localhost/proiect/GaSM/public/Campaign/index/' . $valParam . '" method="post" class="greyContainerAllCampaigns">    
        <button  class="controlButton" type="prevPage" id="prevPage" name="PreviousPage"><</button>
        </form>  ';
  } 
    $counter++;
  if($row['id']-$fostulIndex>1 && $counter<sizeof($data)-1) //daca urmatorul element din array e fostul index se opreste=>s-au terminat campaniile
  {
    
                     
    echo '<form action="http://localhost/proiect/GaSM/public/Campaign/index/' . $row['id'] . '" method="post" class="greyContainerAllCampaigns">    
            <button  class="controlButton" type="nextPage" id="nextPage" name="NextPage">></button>
          </form>  ';
    break;
                   } 
                }
               
    }               
  ?>
     
</div>  


</div>


</body>
</html>