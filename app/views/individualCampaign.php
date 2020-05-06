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

        echo '<div id="oneCampaignContainer" class="greyContainerOneCampaign">';
        echo '<div id="infoCampaign" class="infoOneCampaign"></div>';
        echo '<div id="commentsCampaign" class="infoOneCampaign"></div>';
        
?>
        <script>
            var url='http://localhost/proiect/GaSM/app/api/campaigns/getFullDetailsOfACampaign.php?id=';
            var idValue=<?php echo $data['id'] ?>;
            url=url.concat(idValue);

            fetch(url).
               then(response=>response.json()).then(data=>{
                                                               var string = "";
                                                               string+='<h3 class="campaignNameText">' + data['name'] + '</h3>';
                                                               string+='<h3 class="campaignDescText">Location:' + data['location'] + '</h3>';
                                                               string+='<h3 class="campaignDescText">Description:' + data['description'] + '</h3>';
                                                               string+='<h3 class="campaignDescText">No. of likes:' + data['likes'] + '</h3>';
                                                               document.getElementById("infoCampaign").innerHTML=string;  
                                                               //console.log(data['name']);  
                                                          })



            url='http://localhost/proiect/GaSM/app/api/campaigns/getCommentsOfACampaign.php?id=';
            url=url.concat(idValue);

            fetch(url).
               then(response=>response.json()).then(data=>{
                                                              //console.log(data.length); 
                                                              var string="";
                                                              if(data.length==1)//nu sunt comentarii
                                                              {
                                                                string+='<h3 class="campaignDescText">No comments yet</h3>';
                                                                document.getElementById("commentsCampaign").innerHTML=string;
                                                              }
                                                              else
                                                              {
                                                              for (i = 0; i < data.length; i=i+3)
                                                              {
                                                            
                                                                string+='<div class="commentBox">';
                                                                string+='<h3 class="campaignDescText">' + data[i+2] + '</h3>';
                                                                string+='<h3 class="campaignDescText">Author:' + data[i+1] + '</h3>';
                                                                string+='<h3 class="campaignDescText">' + data[i] + '</h3>';
                                                                string+='</div>';

                                                              }
                                                              document.getElementById("commentsCampaign").innerHTML=string;
                                                              }
                                                          })

        </script>    
<?php
        
       
        if($data['id']%2==1)
        {

            $index=$data['id']-1;
            echo '<form  id="back" name="backToCampaignList" action="http://localhost/proiect/GaSM/public/Campaign/index/'. $index .'" method="post" class="greyContainerAllCampaigns">
                <button  class="controlButton" type="submit" id="details" name="startYourCampaign">Back</button>';
        }
        else 
        {   
            $index=($data['id'])-2;
    
            echo '<form  id="back" name="backToCampaignList" action="http://localhost/proiect/GaSM/public/Campaign/index/'. $index .'" method="post" class="greyContainerAllCampaigns">
                <button  class="controlButton" type="submit" id="details" name="startYourCampaign">Back</button>';

        }

       echo '</div>';  //inchidem divul greyContainer  

  ?>
     
</div>  


</div>


</body>
</html>