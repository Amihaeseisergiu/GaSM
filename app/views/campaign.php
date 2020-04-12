<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start a campaign!</title>
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/public/css/upperPage.css">
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/public/css/signup.css">
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/public/css/inputButtons.css">
</head>
<body>
    <div class="top">      <!--top part-->
        <div>
            <img src="http://localhost:80/proiect/GaSM/public/images/logo.jpg" alt="Logo">
            <p>
                GaSM
            </p>
        </div>

        <div>
            
                <button onclick= "location.href = 'http://localhost/proiect/GaSM/public/'" class="D3Button" >
                    Home
                </button>
            
                
           
                <button onclick= "location.href = 'http://localhost/proiect/GaSM/app/map.html'" class="D3Button">
                    Map
                </button>
            

            
                <button onclick= "location.href = 'http://localhost/proiect/GaSM/app/statistics.php'" class="D3Button">
                    Statistics
                </button>
            
        </div>
    </div>

<div class="greenContainer"> <!--strart campaign part-->


  <form class="greyContainer">

    <h1 class="textHCampaign">Start a campaign!</h1>

    <input class="inputBox" type="text" id="name" name="Name" placeholder="Name">
    <input class="inputBox" type="email" id="email" name="Email" placeholder="Email">
    <input class="inputBox" type="text" id="location" name="Location" placeholder="Location">

    <script>   /* script JS pentru a redirecta pe pagina indexLoggedIn dupa apasarea butonului de Start Campaign ce are ca tip submit */
    function foo() {
        window.location = "./indexLoggedIn.html";
        return false;
     }
    </script>
    <button onclick= "return foo();" class="submitButton" type="submit" id="startCampaignB" name="StartCampaign">Start Campaign!</button>

  </form>


</div>


</body>
</html>