<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign Details</title>
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/upperPage.css">
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/campaign.css">
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

<div class="greenContainerAllCampaigns"> <!--individual campaign part, ar trebui sa am un contor si daca max(id)>atunci sa am un buton cu next page-->
                             <!--greenContainerAllCampaigns lasa spatiu in jos daca e putin scris-->

    
  </form>
 <?php  

        echo '<div class="greyContainerOneCampaign">';
        

        echo '<div class="infoOneCampaign">';
        echo '<h3 class="campaignNameText">' . $data[0]['name'] . '</h3>';
        echo '<h3 class="campaignDescText"><p>Location:</p><p>' . $data[0]['location'] . '</p></h3>';
        echo '<h3 class="campaignDescText"><p>Description:</p><p>' . $data[0]['description'] . '</p></h3>';
        echo '<h3 class="campaignDescText">No. of likes:' . $data[0]['likes'] . '</h3>';
        echo '</div>';

        //print_r($data[0]);
        for($i=0;$i+1<sizeof($data[1]);$i=$i+3)
        {
          echo '<div class="commentBox">';  

          echo '<h3 class="campaignDescText">' . $data[1][$i+2] . '</h3>';  //timestamp-ul comentariului
          echo '<h3 class="campaignDescText">Author:' . $data[1][$i+1] . '</h3>';  //numele
          echo '<h3 class="campaignDescText">Comment:' . $data[1][$i] . '</h3>';    //$data[1][0], comentariul postat de el 

          echo '</div>';
        }

        if($data[0]['id']%2==1)
        {

            $index=$data[0]['id']-1;
            echo '<form  id="back" name="backToCampaignList" action="http://localhost/proiect/GaSM/public/Campaign/index/'. $index .'" method="post" class="greyContainerAllCampaigns">
                <button  class="controlButton" type="submit" id="details" name="startYourCampaign">Back</button>';
        }
        else 
        {   
            $index=($data[0]['id'])-2;
    
            echo '<form  id="back" name="backToCampaignList" action="http://localhost/proiect/GaSM/public/Campaign/index/'. $index .'" method="post" class="greyContainerAllCampaigns">
                <button  class="controlButton" type="submit" id="details" name="startYourCampaign">Back</button>';

        }

       echo '</div>';  //inchidem divul greyContainer  

  ?>
     
</div>  


</div>


</body>
</html>