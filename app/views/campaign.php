<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start a campaign!</title>
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/upperPage.css">
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/signup.css">
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

                <button onclick= "location.href = 'http://localhost/proiect/GaSM/public/Campaign/index/0'" class="D3Button">
                    Campaigns
                </button>
            
        </div>
    </div>

<div class="greenContainer"> <!--strart campaign part-->

<div class="greyContainer">
  <form action="http://localhost/proiect/GaSM/public/Campaign/index/0" method="post" class="greyContainerInside">

    <h1 class="textHCampaign">Start a campaign!</h1>

    <input maxlength="25" title="max 25 alphanumeric chars" required pattern="[a-zA-Z\d ]+$" class="inputBox" type="text" id="name" name="Name" placeholder="Name">
    <input maxlength="50" title="max 50 alphanumeric chars" required pattern="[a-zA-Z\d ]+$" class="inputBox" type="text" id="location" name="Location" placeholder="Location">
    <input maxlength="250" title="max 250 alphanumeric and ,.?: etc chars" required pattern='[A-Za-z0-9 .,!?:\[\]()"-+]+' class="inputBox" type="text" id="description" name="Description" placeholder="Description">

    <?php
      if(isset($data['mesaj'])) echo '<p class="error">' . $data['mesaj'] .'</p>';
    ?>
    
    <button  class="submitButton" type="submit" id="startCampaignB" name="StartCampaign">Start Campaign!</button>
  </form>


    <form action="http://localhost/proiect/GaSM/public/Campaign/index/0" method="post" class="greyContainerInside">
    <button  class="submitButton" type="submit" id="viewAllCampaigns" name="ViewAllCampaigns">View all campaigns</button>
    </form>    
</div>  


</div>


</body>
</html>