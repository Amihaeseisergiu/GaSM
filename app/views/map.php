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
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet@2.2.3/dist/esri-leaflet.js" integrity="sha512-YZ6b5bXRVwipfqul5krehD9qlbJzc6KOGXYsDjU9HHXW2gK57xmWl2gU6nAegiErAqFXhygKIsWPKbjLPXVb2g==" crossorigin=""></script>


    <!-- Load Esri Leaflet Geocoder from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.2.13/dist/esri-leaflet-geocoder.css" integrity="sha512-v5YmWLm8KqAAmg5808pETiccEohtt8rPVMGQ1jA6jqkWVydV5Cuz3nJ9fQ7ittSxvuqsvI9RSGfVoKPaAJZ/AQ==" crossorigin="">
    <script src="https://unpkg.com/esri-leaflet-geocoder@2.2.13/dist/esri-leaflet-geocoder.js" integrity="sha512-zdT4Pc2tIrc6uoYly2Wp8jh6EPEWaveqqD3sT0lf5yei19BC1WulGuh5CesB0ldBKZieKGD7Qyf/G0jdSe016A==" crossorigin=""></script>

    <script src="http://localhost:80/proiect/GaSM/app/javascript/countrycodes.js"></script>
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

            <script>
                
                var garbageMap = L.map('garbageMap', {
                    center: [45.9776587, 25.3419035],
                    zoom: 7.2,
                    zoomSnap: 0
                });

                const attribution = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
                const tileUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
                const tiles = L.tileLayer(tileUrl, { attribution });
                tiles.addTo(garbageMap);

                var paperMarkerIcon = L.icon({
                    iconUrl: 'http://localhost:80/proiect/GaSM/app/images/papermarkericon.png',
                    shadowUrl: 'http://localhost:80/proiect/GaSM/app/images/markershadowicon.png',
                    iconSize: [40, 50],
                    iconAnchor: [20, 50],
                    popupAnchor: [20, 0],
                    shadowSize:   [50, 64],
                    shadowAnchor: [0, 45]
                });
                var plasticMarkerIcon = L.icon({
                    iconUrl: 'http://localhost:80/proiect/GaSM/app/images/plasticmarkericon.png',
                    shadowUrl: 'http://localhost:80/proiect/GaSM/app/images/markershadowicon.png',
                    iconSize: [40, 50],
                    iconAnchor: [20, 50],
                    popupAnchor: [20, 0],
                    shadowSize:   [50, 64],
                    shadowAnchor: [0, 45]
                });
                var metalMarkerIcon = L.icon({
                    iconUrl: 'http://localhost:80/proiect/GaSM/app/images/metalmarkericon.png',
                    shadowUrl: 'http://localhost:80/proiect/GaSM/app/images/markershadowicon.png',
                    iconSize: [40, 50],
                    iconAnchor: [20, 50],
                    popupAnchor: [20, 0],
                    shadowSize:   [50, 64],
                    shadowAnchor: [0, 45]
                });

                function addMarker(marker)
                {
                    if(marker.trashType.localeCompare('paper') == 0) markerIcon = paperMarkerIcon;
                    else if(marker.trashType.localeCompare('plastic') == 0) markerIcon = plasticMarkerIcon;
                    else if(marker.trashType.localeCompare('metal') == 0) markerIcon = metalMarkerIcon;

                    if(marker.trashType.localeCompare('') != 0)
                    {
                        const mapMarker = L.marker([marker.latitude, marker.longitude], {title: marker.trashType, icon: markerIcon});
                        mapMarker.addTo(garbageMap);
                    }
                }

                function loadMarkers()
                {
                    var markers = JSON.parse(JSON.stringify(<?php echo json_encode($data['markers']); ?>));

                   for(var i = 1; i <= Object.keys(markers).length; i++)
                   {
                        addMarker(markers[i]);
                   }
                }

                loadMarkers();
                
            </script>
        </div>

        <div class="rightSide">
            <button class="brightGreen2DButton buttons">Replace 1</button>
            <button class="brightGreen2DButton buttons">Replace 2</button>
            <button class="brightGreen2DButton buttons">Replace 3</button>
        </div>
    </div>

    <script>
        
        var currentGarbageType = '';

        function selectMarker(garbageType)
        {
            currentGarbageType = garbageType;
        }

        function convertToAddress(coords)
        {
            var defObject = $.Deferred();
            var geocodeService = L.esri.Geocoding.geocodeService();
            geocodeService.reverse().latlng(coords).run(function(error, result) {
                var locationData = {
                    city : result.address.City,
                    country : getCountryNameIso3(result.address.CountryCode)
                }
                defObject.resolve(locationData);
            });

            return defObject.promise();
        }

        function onMapClick(e)
        {
            var latlng = garbageMap.mouseEventToLatLng(e.originalEvent);
            var loggedIn = <?php echo json_encode($_SESSION['loggedIn']); ?>;

            if(loggedIn.toString().localeCompare('true') == 0)
            {
                var currentdate = new Date();
                var marker = {
                    latitude: latlng.lat,
                    longitude: latlng.lng,
                    trashType: currentGarbageType,
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

            var locationData = convertToAddress(latlng);
            
            $.when(locationData).done(function(r) {
                locationData = r;

                var urlString ="lat=" + latlng.lat+"&lng=" + latlng.lng + "&type=" + currentGarbageType + "&country=" + locationData.country + "&city=" + locationData.city;

                $.ajax
                ({
                    url: "http://localhost:80/proiect/GaSM/app/controllers/DatabaseInsert.php",
                    type : "POST",
                    cache : false,
                    data : urlString,
                });
            })
        }

        garbageMap.on('click', onMapClick);
        
    </script>
</body>

</html>