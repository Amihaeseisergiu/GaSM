<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start a campaign!</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/upperPage.css">
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/campaign.css">
    <!--<link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/signup.css">-->
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/inputButtons.css">
</head>
<body>
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
            
        </div>
    </div>

<div class="greenContainerAllCampaigns"> <!--all campaigns part, ar trebui sa am un contor si daca max(id)>atunci sa am un buton cu next page-->

<div class="greyContainer">
  
    <h1 class="textHCampaign">All campaigns</h1>

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

  <form id="like' . $row['id'] . '"name="likeForm" action="http://localhost/proiect/GaSM/public/Campaign/like/' . $row['id'] . '" method="post" class="greyContainerAllCampaigns">    
    <button  class="submitButton" type="submit" id="like" name="Like">Like</button>
    </form>

  <form id="comment' . $row['id'] . '" name="commentForm" action="http://localhost/proiect/GaSM/public/Campaign/comment/' . $row['id'] . '" method="post" class="greyContainerAllCampaigns">
    <input max="250" title="max 250 alphanumeric and ,.?: etc chars" required pattern='.' \'[A-Za-z0-9 .,!?:\[\]()"-+]+\' ' .'class="inputBox" type="text" id="comment" name="CommentContent" placeholder="write here">
    <button  class="submitButton" type="submit" id="commentB" name="Comment">Comment</button>
  </form>';
////////////////////////////////////////////////////////////////////////////////////////////////
  // script pt a nu da refresh la like
  echo '<script type="text/javascript">$(\'#like' .  $row['id'] .'\').submit(function(e){  
    e.preventDefault();
    var oneLike = $(\'#like\').val();
    $.ajax({
     type: \'POST\',
     url: "http://localhost/proiect/GaSM/public/Campaign/index/' . $row['id'] . '",
     data: { 
       data: oneLike   
     },
      success: function(msg){
      alert(\'You liked this campaign!\');
         }
      });
   return false;
   });</script>';

   //script pt a nu da refresh la comment
   echo '<script type="text/javascript">$(\'#comment' .  $row['id'] .'\').submit(function(e){
    e.preventDefault();
    var oneComment = $(\'#CommentContent\').val();
    $.ajax({
     type: \'POST\',
     url: "http://localhost/proiect/GaSM/public/Campaign/index/' . $row['id'] . '",
     data: { 
       data: oneComment   
     },
      success: function(msg){
      alert(\'Comment sent.\');
         }
      });
   return false;
   });</script>';
///////////////////////M A J O R T E S T ////////////////////////////////////////
echo '<script type="text/javascript">$(\'#details' .  $row['id'] .'\').submit(function(e){
    e.preventDefault();
    var oneComment = $(\'#details\').val();
    $.ajax({
     type: \'POST\',
     url: "http://localhost/proiect/GaSM/app/controllers/test.php",
     data: { 
       data: oneComment   
     },
      success: function(msg){
      alert(\'fabulos test.\');
         }
      });
   return false;
   });</script>';

//////////////////////////////////////////////////////////////////////////////////////////////
  if(($counter%2==1 && $row['id']>2)||($counter+1==sizeof($data)-1))//daca suntem la elementul 3(0,1,2,3) si id-ul e mai mare de 2, sau urm element e capatul sirului($fostulIndex)
  {
    echo '<form action="http://localhost/proiect/GaSM/public/Campaign/index/' . $row['id']. '" method="post" class="greyContainerAllCampaigns">    
        <button  class="submitButton" type="prevPage" id="prevPage" name="PreviousPage"><</button>
        </form>  ';
  } 
    $counter++;
  if($row['id']-$fostulIndex>1 && $counter<sizeof($data)-1) //daca urmatorul element din array e fostul index se opreste=>s-au terminat campaniile
  {
    
                     
    echo '<form action="http://localhost/proiect/GaSM/public/Campaign/index/' . $row['id']. '" method="post" class="greyContainerAllCampaigns">    
            <button  class="submitButton" type="nextPage" id="nextPage" name="NextPage">></button>
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