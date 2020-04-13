<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map</title>
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/upperPage.css">
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/inputButtons.css">
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/mapStyle.css">
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
            <button onclick= "location.href = 'http://localhost/proiect/GaSM/public/'" class="D3Button">
                Home
            </button>

            <button>
                Map
            </button>

            <button onclick= "location.href = 'http://localhost/proiect/GaSM/app/views/statistics.php'" class="D3Button">
                Statistics
            </button>
        </div>
    </div>

    <div class="main">
        <div class="leftSide">
            <button class="brightGreen2DButton buttons">Replace 1</button>
            <button class="brightGreen2DButton buttons">Replace 2</button>
            <button class="brightGreen2DButton buttons">Replace 3</button>
        </div>
    
        <div class="middle">
            <div id="googleMap" style="width:100%;height:100%;"></div>

            <script>
            function myMap() {
            var mapProp= {
            center:new google.maps.LatLng(51.508742,-0.120850),
            zoom:5,
            };
            var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
            }
            </script>

            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDj0TvdM96fKxTWKOQWS4e2TAXD25AUsxA&callback=myMap"></script>
        </div>
    
        <div class="rightSide">
            <button class="brightGreen2DButton buttons">Replace 1</button>
            <button class="brightGreen2DButton buttons">Replace 2</button>
            <button class="brightGreen2DButton buttons">Replace 3</button>
        </div>
    </div>

   </body>

   </html>