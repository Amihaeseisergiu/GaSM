<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-Up Page</title>
    <link rel="stylesheet" type = "text/css" href="http://localhost:80/proiect/GaSM/app/css/upperPage.css">
    <link rel="stylesheet" type = "text/css" href="http://localhost:80/proiect/GaSM/app/css/signup.css">
    <link rel="stylesheet" type = "text/css" href="http://localhost:80/proiect/GaSM/app/css/inputButtons.css">
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
            
                <button onclick= "location.href = 'http://localhost:80/proiect/GaSM/public/'" class="D3Button" >  <!--merge redirectarea-->
                    Home
                </button>
            
                
           
                <button onclick= "location.href = 'http://localhost/proiect/GaSM/public/Map'" class="D3Button">
                    Map
                </button>
            

            
                <button onclick= "location.href = 'http://localhost/proiect/GaSM/public/Statistics'" class="D3Button">
                    Statistics
                </button>

                <button onclick= "location.href = 'http://localhost/proiect/GaSM/public/Campaign/index/1'" class="D3Button">
                    Campaigns
                </button>
            
        </div>
    </div>

<div class="greenContainer"> <!--signupPart-->


  <form  action="http://localhost/proiect/GaSM/public/" method="post" class="greyContainer">

    <h1 class="textH">Sign-Up</h1>

    <input required  max="16" pattern="[a-zA-Z\d]+" class="inputBox" type="text" id="name" name="Name" placeholder="Name*" >
    <input required title="320 chars max" max="320" class="inputBox" type="email" id="email" name="Email" placeholder="Email*" >
    <input required title="max 16 alphanumeric chars" max="16" pattern="[a-zA-Z\d]+" class="inputBox" type="password" id="pw" name="Password" placeholder="Password*" >
    <input required title="max 50 chars" max="50" pattern="[a-zA-Z]+" class="inputBox" type="text" id="country" name="Country" placeholder="Country*">
    <input required title="max 50 alphanumeric chars" max="50" pattern="[a-zA-Z\d]+" class="inputBox" type="text" id="county" name="County" placeholder="County*">
    <input required title="max 50 alphanumeric chars" max="50" pattern="[a-zA-Z\d]+" class="inputBox" type="text" id="city" name="City" placeholder="City*">
    
    <button  class="submitButton" type="submit" id="submitB" name="SubmitButton">Submit</button>
    <?php
      if(isset($data['mesaj'])) echo '<p class="error">' . $data['mesaj'] .'</p>';
    ?>


  </form>


</div>


</body>
</html>