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

<div class="greenContainerAllCampaigns"> <!--individual campaign part, ar trebui sa am un contor si daca max(id)>atunci sa am un buton cu next page-->
                             <!--greenContainerAllCampaigns lasa spatiu in jos daca e putin scris-->

<div class="greyContainer">

 <?php  

        echo '<h3 class="campaignNameText">' . $data[0]['name'] . '</h3>';
        echo '<h3 class="campaignDescText"><p>Description:</p><p>' . $data[0]['description'] . '</p></h3>';
        echo '<h3 class="campaignDescText">No. of likes:' . $data[0]['likes'] . '</h3>';

        //print_r($data[1]);
        for($i=0;$i+1<sizeof($data[1]);$i=$i+2)
        {
          echo '<h3 class="campaignDescText">Author:' . $data[1][$i+1] . '</h3>';  //numele
          echo '<h3 class="campaignDescText">Comment:' . $data[1][$i] . '</h3>';    //$data[1][0], comentariul postat de el 
        }
  

  ?>
     
</div>  


</div>


</body>
</html>