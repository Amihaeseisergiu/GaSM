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

            <button onclick="location.href = 'http://localhost/proiect/GaSM/app/views/statistics.php'" class="D3Button">
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
                    iconSize: [40, 50],
                    iconAnchor: [20, 50],
                    popupAnchor: [20, 0]
                });
                var plasticMarkerIcon = L.icon({
                    iconUrl: 'http://localhost:80/proiect/GaSM/app/images/plasticmarkericon.png',
                    iconSize: [40, 50],
                    iconAnchor: [20, 50],
                    popupAnchor: [20, 0]
                });
                var metalMarkerIcon = L.icon({
                    iconUrl: 'http://localhost:80/proiect/GaSM/app/images/metalmarkericon.png',
                    iconSize: [40, 50],
                    iconAnchor: [20, 50],
                    popupAnchor: [20, 0]
                });

                function addMarker(lat, lng, type)
                {
                    if(type.localeCompare('paper') == 0) markerIcon = paperMarkerIcon;
                    else if(type.localeCompare('plastic') == 0) markerIcon = plasticMarkerIcon;
                    else if(type.localeCompare('metal') == 0) markerIcon = metalMarkerIcon;

                    if(type.localeCompare('') != 0)
                    {
                        const marker = L.marker([lat, lng], {title: type, icon: markerIcon});
                        marker.addTo(garbageMap);
                    }
                }

                function loadMarkers()
                {
                    var markers = JSON.parse(JSON.stringify(<?php echo json_encode($data['markers']); ?>));

                   for(var i = 1; i <= Object.keys(markers).length; i++)
                   {
                        addMarker(markers[i].latitude, markers[i].longitude, markers[i].trashType);
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

        function onMapClick(e)
        {
            var latlng = garbageMap.mouseEventToLatLng(e.originalEvent);

            addMarker(latlng.lat, latlng.lng, currentGarbageType);

            var userId = 1;
            var urlString ="lat=" + latlng.lat+"&lng=" + latlng.lng+"&type=" + currentGarbageType + "&userId=" + userId;

            $.ajax
            ({
                url: "http://localhost:80/proiect/GaSM/app/controllers/MarkerInsert.php",
                type : "POST",
                cache : false,
                data : urlString,
            });
        }

        garbageMap.on('click', onMapClick);
    </script>
</body>

</html>