<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map</title>
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/upperPage.css">
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/inputButtons.css">
    <link rel="stylesheet" type="text/css" href="http://localhost:80/proiect/GaSM/app/css/mapStyle.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
    
    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet@2.2.3/dist/esri-leaflet.js" integrity="sha512-YZ6b5bXRVwipfqul5krehD9qlbJzc6KOGXYsDjU9HHXW2gK57xmWl2gU6nAegiErAqFXhygKIsWPKbjLPXVb2g==" crossorigin=""></script>

    <!-- Load Esri Leaflet Geocoder from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.2.13/dist/esri-leaflet-geocoder.css" integrity="sha512-v5YmWLm8KqAAmg5808pETiccEohtt8rPVMGQ1jA6jqkWVydV5Cuz3nJ9fQ7ittSxvuqsvI9RSGfVoKPaAJZ/AQ==" crossorigin="">
    <script src="https://unpkg.com/esri-leaflet-geocoder@2.2.13/dist/esri-leaflet-geocoder.js" integrity="sha512-zdT4Pc2tIrc6uoYly2Wp8jh6EPEWaveqqD3sT0lf5yei19BC1WulGuh5CesB0ldBKZieKGD7Qyf/G0jdSe016A==" crossorigin=""></script>

    <!-- Load Leaflet Marker Clustering from CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.Default.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/leaflet.markercluster-src.js"></script>

    <script src="http://localhost:80/proiect/GaSM/app/javascript/countrycodes.js"></script>
    <script src="http://localhost:80/proiect/GaSM/app/javascript/romaniacounties.js"></script>

    <style>
        .info {
            padding: 6px 8px;
            font: 14px/16px Arial, Helvetica, sans-serif;
            background: white;
            background: rgba(255,255,255,0.8);
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            border-radius: 5px;
        }
        .info h4 {
            margin: 0 0 5px;
            color: #777;
        }

        .legend {
            line-height: 18px;
            color: #555;
        }
        .legend i {
            width: 18px;
            height: 18px;
            float: left;
            margin-right: 8px;
            opacity: 0.7;
        }
    </style>
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
            <button onclick="location.href = 'http://localhost/proiect/GaSM/public/'" class="D3Button">
                Home
            </button>

            <button>
                Map
            </button>

            <button onclick="location.href = location.href = 'Statistics'" class="D3Button">
                Statistics
            </button>
        </div>
    </div>

    <div class="main">
        <div class="leftSide">
            <button class="brightGreen2DButton buttons" onclick="selectMarker('plastic')">Plastic</button>
            <button class="brightGreen2DButton buttons" onclick="selectMarker('paper')">Paper</button>
            <button class="brightGreen2DButton buttons" onclick="selectMarker('metal')">Metal</button>
        </div>

        <div class="middle">
            <div id="garbageMap">
            </div>
        </div>

        <div class="rightSide">
            <button class="brightGreen2DButton buttons" onclick="selectMap('statistics')">Statistics Map</button>
            <button class="brightGreen2DButton buttons" onclick="selectMap('markers')">Markers Map</button>
            <button class="brightGreen2DButton buttons">Replace 3</button>
        </div>
    </div>

    <script src="http://localhost:80/proiect/GaSM/app/javascript/map.js"></script>
    <script>
    
        function onMapClick(e)
        {
            if(currentMapType.toString().localeCompare("markers") == 0)
            {
                var latlng = garbageMap.mouseEventToLatLng(e.originalEvent);
                var loggedIn = <?php echo json_encode($_SESSION['loggedIn']); ?>;

                if(loggedIn.toString().localeCompare('true') == 0)
                {
                    var currentdate = new Date();
                    var marker = {
                        latitude: latlng.lat,
                        longitude: latlng.lng,
                        trash_type: currentGarbageType,
                        userId: parseInt(<?php echo json_encode($_SESSION['userID']); ?>),
                        time: currentdate.getFullYear() + "-"
                            + currentdate.getMonth()  + "-" 
                            + currentdate.getDate() + " "  
                            + currentdate.getHours() + ":"  
                            + currentdate.getMinutes() + ":" 
                            + currentdate.getSeconds(),
                        userName: <?php echo json_encode($_SESSION['name']); ?>,
                        userCountry: <?php echo json_encode($_SESSION['country']); ?>,
                        userCity: <?php echo json_encode($_SESSION['city']); ?>
                    };
                    addMarker(marker);
                }

                var geocodeService = L.esri.Geocoding.geocodeService();
                geocodeService.reverse().latlng(latlng).run(function(error, result) {
                    var locationData = {
                        neighborhood : result.address.Neighborhood.normalize("NFD").replace(/[\u0300-\u036f]/g, ""),
                        city : result.address.City.normalize("NFD").replace(/[\u0300-\u036f]/g, ""),
                        county: result.address.Region.normalize("NFD").replace(/[\u0300-\u036f]/g, ""),
                        country : getCountryNameIso3(result.address.CountryCode)
                    }
                    
                    var urlString ="lat=" + latlng.lat+"&lng=" + latlng.lng + "&type=" + currentGarbageType 
                                    + "&country=" + locationData.country + "&city=" + locationData.city
                                     + "&county=" + locationData.county + "&neighborhood=" + locationData.neighborhood;

                    var marker = {
                        "latitude": latlng.lat,
                        "longitude": latlng.lng,
                        "trashType": currentGarbageType,
                        "country": locationData.country,
                        "city": locationData.city,
                        "county": locationData.county,
                        "neighborhood": locationData.neighborhood
                    }

                    fetch('http://localhost:80/proiect/GaSM/app/api/markers/write/insert.php', {
                        method: 'POST',
                        headers: {'Content-Type':'application/json'},
                        body: JSON.stringify(marker)
                    });
                });
            }
        }

        loadMarkers();
        garbageMap.on('click', onMapClick);
        
    </script>
</body>

</html>