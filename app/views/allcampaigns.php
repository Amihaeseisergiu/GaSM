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

  <form id="like"  name="likeForm" method="post" class="greyContainerAllCampaigns">    
    <button  class="submitButton" type="button" id="like' . $row['id'] . '" name="Like">Like</button>
    </form>

  <form id="commentForm' . $row['id'] . '" name="commentForm"  enctype="multipart/form-data" method="post"  class="greyContainerAllCampaigns">
    <input id="comment' . $row['id'] . '" name="CommentContent" max="250" title="max 250 alphanumeric and ,.?: etc chars" required pattern='.' \'[A-Za-z0-9 .,!?:\[\]()"-+]+\' ' .'class="inputBox" type="text"  placeholder="write here">
    <button  id="commentB' . $row['id'] . '" class="submitButton" type="button"  name="Comment">Comment</button>
  </form>';
////////////////////////////////////////////////////////////////////////////////////////////////
  // script pt a nu da refresh la like
  echo '<script type="text/javascript">
              document.getElementById("like' . $row['id'] . '").addEventListener("click", likeFunction);
                 
              function likeFunction() {

                fetch(\'http://localhost:80/proiect/GaSM/public/Campaign/like/'. $row['id'] . '\', {
                  method: \'POST\',
                  headers: {\'Content-Type\':\'application/x-www-form-urlencoded\'}});
                
                alert ("You liked this campaign!");
                return false;
              }
  
              document.getElementById("commentB' . $row['id'] . '").addEventListener("click", commentFunction);
              
              function commentFunction() {

                fetch(\'http://localhost:80/proiect/GaSM/public/Campaign/comment/'. $row['id'] . '\', {
                  method: \'POST\',
                  body : new FormData(document.getElementById("commentForm' . $row['id'] . '")),
                  headers: {\'Content-Type\':\'multipart/form-data\'}
                  
                
                });
                
                alert ("You left a comment!");
              }

        </script>';

        //var_dump($_POST);

//                        http://localhost:80/proiect/GaSM/public/Campaign/comment/'. $row['id'] . '\
// body : new FormData(document.getElementById("comment' . $row['id'] . '")),

   /*//script pt a nu da refresh la comment
   echo '<script type="text/javascript">$(\'#comment' .  $row['id'] .'\').submit(function(e){
    e.preventDefault();
    var oneComment = $(\'#CommentContent\').val();
    $.ajax({
     type: \'POST\',
     url: "http://localhost/proiect/GaSM/public/Campaign/comment/' . $row['id'] . '",
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
/*echo '<script type="text/javascript">$(\'#details' .  $row['id'] .'\').submit(function(e){
    e.preventDefault();
    var oneComment = $(\'#details\').val();
    $.ajax({
     type: \'POST\',
     url: "http://localhost/proiect/GaSM/app/controllers/test.php",
     data: { 
       data: oneComment   
     },
      success: function(msg){
        var newdiv = document.createElement(\'div\');
        //newdiv.id = dynamicInput[counter];
        newdiv.innerHTML = "Entry <br><input type=\'text\' name=\'myInputs[]\'> <input type=\'button\' value=\'-\' onClick=\'removeInput(this);\'>";
        document.getElementById(\'formulario\').appendChild(newdiv);
      alert(\'fabulos test.\');
      
         }
      });
   return false;
   });</script>';
   */

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