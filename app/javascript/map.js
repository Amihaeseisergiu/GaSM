var garbageMap = L.map('garbageMap', {
    center: [45.9776587, 25.3419035],
    zoom: 7.2,
    zoomSnap: 0
});

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

var currentGarbageType = '';
var currentMapType = 'markers';
var loadedMarkers = [];
var markersCluster;

const attribution = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
const tileUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
const tiles = L.tileLayer(tileUrl, { attribution });
tiles.addTo(garbageMap);

function getColor(d) {
    return d > 1000 ? '#800026' :
        d > 500  ? '#BD0026' :
        d > 200  ? '#E31A1C' :
        d > 100  ? '#FC4E2A' :
        d > 50   ? '#FD8D3C' :
        d > 20   ? '#FEB24C' :
        d > 10   ? '#FED976' :
                    '#FFEDA0';
}

function style(feature) {
    return {
        fillColor: getColor(feature.properties.total),
        weight: 2,
        opacity: 1,
        color: 'white',
        dashArray: '3',
        fillOpacity: 0.7
    };
}

var geojson;

var info = L.control();

info.onAdd = function (map) {
    this._div = L.DomUtil.create('div', 'info');
    this.update();
    return this._div;
};

info.update = function (props) {
    this._div.innerHTML = '<h4>Garbage Stats ' +  (props ? props.name + '</h4>'
    + "<div style=\"display: flex; align-items: center; flex-direction: column;\">"
    + "<b>Total</b> " + props.total + "</br></br>"
    + "<div style=\"display: flex; align-items: center; flex-direction: column; border: 3px dashed green; padding: 5px 5px 5px 5px;\">"
    + "<b>Plastic</b> " + props.nr_plastic + "</br>"
    + "<b>Paper</b> " + props.nr_paper + "</br>"
    + "<b>Metal</b> " + props.nr_metal + "</br>"
    + "<b>Glass</b> " + props.nr_glass
    + "</div></div>"
        : '</h4>Hover over a countie');
};

var legend = L.control({position: 'bottomright'});

legend.onAdd = function (map) {

    var div = L.DomUtil.create('div', 'info legend'),
        grades = [0, 10, 20, 50, 100, 200, 500, 1000],
        labels = [];

    for (var i = 0; i < grades.length; i++) {
        div.innerHTML +=
            '<i style="background:' + getColor(grades[i] + 1) + '"></i> ' +
            grades[i] + (grades[i + 1] ? '&ndash;' + grades[i + 1] + '<br>' : '+');
    }

    return div;
};

function highlightFeature(e) {
    var layer = e.target;

    layer.setStyle({
        weight: 5,
        color: '#666',
        dashArray: '',
        fillOpacity: 0.7
    });

    if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
        layer.bringToFront();
    }
    info.update(layer.feature.properties);
}

function resetHighlight(e) {
    geojson.resetStyle(e.target);
    info.update();
}

function zoomToFeature(e) {
    garbageMap.fitBounds(e.target.getBounds());
}

function onEachFeature(feature, layer) {
    layer.on({
        mouseover: highlightFeature,
        mouseout: resetHighlight,
        click: zoomToFeature
    });
}

function addMarker(marker)
{
    if(marker.trashType.localeCompare('paper') == 0) markerIcon = paperMarkerIcon;
    else if(marker.trashType.localeCompare('plastic') == 0) markerIcon = plasticMarkerIcon;
    else if(marker.trashType.localeCompare('metal') == 0) markerIcon = metalMarkerIcon;

    if(marker.trashType.localeCompare('') != 0)
    {
        const mapMarker = L.marker([marker.latitude, marker.longitude], {title: marker.trashType, icon: markerIcon});
		markersCluster.addLayer(mapMarker);
        loadedMarkers.push(mapMarker);
    }
}

function selectMarker(garbageType)
{
    currentGarbageType = garbageType;
}

function loadMarkers()
{
    loadedMarkers = [];
    markersCluster = L.markerClusterGroup();

    fetch('http://localhost:80/proiect/GaSM/app/controllers/DatabaseFetch.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'type=markers'
    }).then(response => response.json())
    .then(data => {
        for(var i = 0; i < data.length; i++)
                addMarker(data[i]);
        
            garbageMap.addLayer(markersCluster);
    });
}

function selectMap(mapType)
{
    if(mapType.toString().localeCompare('markers') == 0 && currentMapType.toString().localeCompare('markers') != 0)
    {
        geojson.remove(garbageMap);
        info.remove(garbageMap);
        legend.remove(garbageMap);
        loadMarkers();
    }
    else if(mapType.toString().localeCompare('statistics') == 0 && currentMapType.toString().localeCompare('statistics') != 0)
    {
        fetch('http://localhost:80/proiect/GaSM/app/controllers/DatabaseFetch.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'type=markers'
        }).then(response => response.json())
        .then(data => {
            var markers = data;
            for(var i = 0; i < countiesData.features.length; i++)
            {
                countiesData.features[i].properties.total = 0;
                countiesData.features[i].properties.nr_plastic = 0;
                countiesData.features[i].properties.nr_paper = 0;
                countiesData.features[i].properties.nr_metal = 0;
                countiesData.features[i].properties.nr_glass = 0;
                for(var j = 0; j < markers.length; j++)
                {
                    if(countiesData.features[i].properties.name.localeCompare(markers[j].county) == 0)
                    {
                        countiesData.features[i].properties.total++;
                        switch(markers[j].trashType)
                        {
                            case 'plastic':
                                countiesData.features[i].properties.nr_plastic++;
                                break;
                            case 'paper':
                                countiesData.features[i].properties.nr_paper++;
                                break;
                            case 'metal':
                                countiesData.features[i].properties.nr_metal++;
                                break;
                            case 'glass':
                                countiesData.features[i].properties.nr_glass++;
                                break;
                            default: break;
                        }
                    }
                }
            }
            geojson = L.geoJson(countiesData, {
                style: style,
                onEachFeature: onEachFeature
            }).addTo(garbageMap);
            info.addTo(garbageMap);
            legend.addTo(garbageMap);
            garbageMap.removeLayer(markersCluster);
        });
    }
    currentMapType = mapType;
}