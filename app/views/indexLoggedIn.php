<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GaSM</title>
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/public/css/upperPage.css">
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/public/css/midPart.css">
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/public/css/bottomStyle.css">
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/public/css/inputButtons.css">
</head>

<body>
    <div class="top">
        <!--top part-->
        <div>
            <img src="http://localhost:80/proiect/GaSM/public/images/logo.jpg" alt="Logo">
            <p>
                GaSM
            </p>
        </div>

        <div>
            <button>
                Home
            </button>

            <button onclick= "location.href = 'http://localhost/proiect/GaSM/app/views/map.php'" class="D3Button">
                Map
            </button>

            <button onclick= "location.href = 'http://localhost/proiect/GaSM/app/views/statistics.php'" class="D3Button">
                Statistics
            </button>
        </div>
    </div>


    <div class="parentDiv">
        <!--mid part-->
        <div class="leftDiv">
            <h2 class="textH">Recycle better with us.</h2>
            <div>
                <h2 class="textH">Join today.</h2>
                <button onclick= "location.href = 'http://localhost:80/proiect/GaSM/app/views/signup.php'" type="button" name="Signup" id="signup" class="signupButton">Sign
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
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Veritatis a cupiditate explicabo perferendis
                soluta sunt consectetur odio aliquid vitae ullam error eius reprehenderit nisi, magnam consequatur,
                eligendi pariatur assumenda! Sit.</p>
        </div>
        <div class="vl"></div>
        <div class="hl"></div>
        <div class="bottom-text">
            <h3>Visualize the map</h3>
            <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur, excepturi placeat quaerat commodi
                ipsam ad ducimus iste, sint debitis non modi nihil quia ratione aspernatur incidunt nesciunt
                exercitationem veritatis corrupti?
            </p>
        </div>
        <div class="vl"></div>
        <div class="hl"></div>
        <div class="bottom-text">
            <h3>
                Help us improve
            </h3>
            <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis eum rerum incidunt saepe mollitia
                sequi facere ipsa, exercitationem sed omnis? Id, incidunt qui laborum impedit debitis quidem temporibus
                rem quae.
            </p>
        </div>
    </div>

   </body>

   </html>