<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GaSM</title>
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/upperPage.css">
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/midPart.css">
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/bottomStyle.css">
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/inputButtons.css">
</head>

<body>
    <div class="top">
        <!--top part-->
        <div>
            <img src="http://localhost:80/proiect/GaSM/app/images/logo.jpg" alt="Logo">
            <p>
                GaSM
            </p>
        </div>

        <div>
            <button>
                Home
            </button>

            <button onclick= "location.href = 'Map'" class="D3Button">
                Map
            </button>

            <button onclick= "location.href = 'Statistics'" class="D3Button">
                Statistics
            </button>

            <button onclick= "location.href = 'http://localhost/proiect/GaSM/public/Campaign/index/0'" class="D3Button">
                    Campaigns
            </button>
        </div>
    </div>


    <div class="parentDiv">
        <!--mid part-->
        <div class="leftDiv">
            <h2 class="textH">Recycle better with us.</h2>
            <div>
                <h2 class="textH">Join today.</h2>
                <button onclick= "location.href = 'Signup'" type="button" name="Signup" id="signup" class="signupButton">Sign
                        up</button>
            </div>
        </div>

        <div class="rightDiv">
            <form  action="http://localhost/proiect/GaSM/public/" method="post" class="inputButtonsContainer">
                <div class="divInput">
                    <h2 class="textH">Welcome back</h2>

                </div>

                <button  type="submit" name="LogoutButton" id="login" class="loginButton">Logout</button>

            </form>
           
        </div>

    </div>

    <div class="bottom">
        <!--bottom part-->
        <div class="bottom-text">
            <h3>Statistics</h3>
            <p>
            Visualize different charts based on your account's region. Filter the charts according to your preferences. View how much your city has improved recently, and download useful reports
             in HTML, PDF and CSV formats. 
            </p>
        </div>
        <div class="vl"></div>
        <div class="hl"></div>
        <div class="bottom-text">
            <h3>Visualize the map</h3>
            <p>
            Report garbage in your area and check county level statistics on the map page.
            </p>
        </div>
        <div class="vl"></div>
        <div class="hl"></div>
        <div class="bottom-text">
            <h3>
                Help us improve
            </h3>
            <p>
            Use our website and give us your feedback. Your opinion helps us improve. Let's make the world a cleaner place!
            </p>
        </div>
    </div>

   </body>

   </html>