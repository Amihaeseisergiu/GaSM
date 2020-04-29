<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://localhost:80/proiect/GaSM/app/css/upperPage.css">
    <link rel="stylesheet" href="http://localhost:80/proiect/GaSM/app/css/inputButtons.css">
    <link rel="stylesheet" href="http://localhost:80/proiect/GaSM/app/css/statisticsStyle.css">
    <title>GaSM</title>
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
            <button onclick="location.href =  'http://localhost/proiect/GaSM/public/'" class="D3Button">
                Home
            </button>

            <button onclick="location.href = 'http://localhost/proiect/GaSM/public/Map'" class="D3Button">
                Map
            </button>

            <button>
                Statistics
            </button>
            <button onclick="location.href = 'http://localhost/proiect/GaSM/public/Campaign/index/1'" class="D3Button">
                Campaigns
            </button>
        </div>
    </div>
    <div class="main1">
        <div class="buttons">
            <button class="D3Button button" onclick="downloadCSV()" id="firstButton" value="Download file" name="downloadCSV">
                CSV Report
            </button>
            <button class="D3Button button" onclick="downloadPDF()" id="secondButton" value="Download file" name="downloadPDF">
                PDF Report
            </button>
            <button class="D3Button button" onclick="downloadHTML()" id="thirdButton" value="Download file" name="downloadHTML">
                HTML Report
            </button>
        </div>
        <div class="chartAndButton">
            <div id="chartAndSwitch">
                <div id="leftButton" onclick="switchChart()">
                    <div class="leftarrow"></div>
                </div>
                <div class="charts" id="lineChart"></div>
                <div id="rightButton" onclick="switchChart()">
                    <div class="rightarrow"></div>
                </div>
            </div>
            <div class="form" style="position:relative; margin-top:1em; display:flex;">
                <div class="garbage">
                    <input type="checkbox" id="garbage1" name="plastic" value="Plastic">
                    <label for="garbage1" style="margin-right: 0.5em;"> Plastic </label>

                    <input type="checkbox" id="garbage2" name="paper" value="Paper">
                    <label for="garbage2" style="margin-right: 0.5em;"> Paper </label>

                    <input type="checkbox" id="garbage3" name="glass" value="Glass">
                    <label for="garbage3" style="margin-right: 0.5em;"> Glass </label>

                    <input type="checkbox" id="garbage4" name="metal" value="Metal">
                    <label for="garbage4" style="margin-right: 0.5em;"> Metal </label>
                </div>
                <select name="filters" id="filters">
                    <option value="All Time">All Time</option>
                    <option value="Last Week">Last Week</option>
                    <option value="Last Month">Last Month</option>
                    <option value="Today">Today</option>
                </select>
                <button onclick="changeChart()" name="dateFilter" id="filterButton" style="background-color: #0ed145; color:white; padding-left:1em; padding-right:1em; margin-left:0.5em; padding-top:0.3em; padding-bottom:0.3em; color:black; font-size:1em; font-weight:bold;">Filter</button>
            </div>
        </div>
    </div>
    <div class="main2">
        <div class="box1">
            <h3 class="box-p">
                Changes
            </h3>
            <div class="changes">
                <div class="box-firstchange">
                    <div id="first-change"> </div>
                    <span id="first-change-span"> </span>
                </div>
                <div class="box-secondchange">
                    <div id="second-change"></div>
                    <span id="second-change-span"> </span>
                </div>
                <div class="box-thirdchange">
                    <div class="downarrow"></div>
                    <span> -5% Speed </span>
                </div>
            </div>
        </div>
        <div class="box2">
            <h3 class="box-p">
                Garbage distribution
            </h3>
            <div id="pieChart" style="height: 200px;"></div>
        </div>
        <div class="box3">
            <h3 class="box-p">
                Mentions
            </h3>
            <div class="box-text">
                The charts are generated based on the region settings of your account. If you wish to see all the data, please log out.
            </div>
        </div>
    </div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="http://localhost/proiect/GaSM/app/javascript/jspdf.min.js"></script>
    <script>
        var country = '<?php echo $_SESSION['country']; ?>';
        var city = '<?php echo $_SESSION['city'] ?>';
        var county = '<?php echo $_SESSION['county']; ?>';
    </script>
    <script>
        function switchChart() {
            if (currentChart === "line") {
                var chartId = document.getElementById(currentChart.concat("Chart"));
                chartId.setAttribute("id", "barChart");
                currentChart = "bar";
            } else {
                var chartId = document.getElementById(currentChart.concat("Chart"));
                chartId.setAttribute("id", "lineChart");
                currentChart = "line";
            }
            loadChart();
        }
    </script>

    <script>
        var filter = 'All Time';
        var showPlastic = true;
        var showPaper = true;
        var showGlass = true;
        var showMetal = true;
        var markerCoordinates = [];
        var plastics = [];
        var papers = [];
        var glasses = [];
        var metals = [];
        var allPlastic = 0;
        var allPaper = 0;
        var allGlass = 0;
        var allMetal = 0;
        var plasticDps = [];
        var paperDps = [];
        var glassDps = [];
        var metalDps = [];
        var currentChart = "line";
        var charts = [];

        function loadChart() {
            if (currentChart === "line") {
                var lineChart = new CanvasJS.Chart("lineChart", {
                    animationEnabled: true,
                    backgroundColor: "white",
                    fileName: "LineChart",
                    title: {
                        text: "Garbage Distribution"
                    },
                    axisX: {
                        tickColor: "red",
                        tickLength: 5,
                        tickThickness: 2
                    },
                    axisY: {
                        tickLength: 15,
                        tickColor: "DarkSlateBlue",
                        tickThickness: 5
                    },
                    zoomEnabled: true,
                    data: [{
                            showInLegend: true,
                            name: "series1",
                            legendText: "Plastic",
                            visible: showPlastic,
                            type: "line",
                            dataPoints: plasticDps
                        },
                        {
                            showInLegend: true,
                            name: "series2",
                            legendText: "Paper",
                            visible: showPaper,
                            type: "line",
                            dataPoints: paperDps
                        },
                        {
                            showInLegend: true,
                            name: "series3",
                            legendText: "Glass",
                            visible: showGlass,
                            type: "line",
                            dataPoints: glassDps
                        },
                        {
                            showInLegend: true,
                            name: "series4",
                            legendText: "Metal",
                            visible: showMetal,
                            type: "line",
                            dataPoints: metalDps
                        }
                    ]
                });
                lineChart.render();
                var myArray = {
                    "line": lineChart
                };
                charts.push(myArray);
            } else if (currentChart == "bar") {
                var plasticToBar = plasticDps;
                var paperToBar = paperDps;
                var glassToBar = glassDps;
                var metalToBar = metalDps;
                plasticToBar['label'] = plasticToBar['x'];
                delete plasticToBar['x'];
                paperToBar['label'] = paperToBar['x'];
                delete paperToBar['x'];
                glassToBar['label'] = glassToBar['x'];
                delete glassToBar['x'];
                metalToBar['label'] = metalToBar['x'];
                delete metalToBar['x'];
                var chartId = document.getElementById("barChart");
                chartId.setAttribute("id", "barChart");
                currentChart = "bar";
                var barChart = new CanvasJS.Chart("barChart", {
                    animationEnabled: true,
                    zoomEnabled: true,
                    title: {
                        text: "Garbage Distribution"
                    },
                    axisY: {
                        title: "Reports",
                        titleFontColor: "#4F81BC",
                        lineColor: "#4F81BC",
                        labelFontColor: "#4F81BC",
                        tickColor: "#4F81BC"
                    },
                    toolTip: {
                        shared: true
                    },
                    legend: {
                        cursor: "pointer"
                    },
                    data: [{
                            type: "column",
                            name: "Plastic",
                            legendText: "Plastic",
                            visible: showPlastic,
                            showInLegend: true,
                            dataPoints: plasticToBar
                        },
                        {
                            type: "column",
                            name: "Paper",
                            legendText: "Paper",
                            visible: showPaper,
                            axisYType: "secondary",
                            showInLegend: true,
                            dataPoints: paperToBar
                        },
                        {
                            type: "column",
                            name: "Glass",
                            legendText: "Glass",
                            visible: showGlass,
                            axisYType: "secondary",
                            showInLegend: true,
                            dataPoints: glassToBar
                        },
                        {
                            type: "column",
                            name: "Metal",
                            legendText: "Metal",
                            visible: showMetal,
                            axisYType: "secondary",
                            showInLegend: true,
                            dataPoints: metalToBar
                        }
                    ]
                });
                barChart.render();
                var myArray = {
                    "bar": barChart
                };
                charts.push(myArray);
            }
        }

        function changeChart() {
            lastFilter = filter;
            showPlastic = document.getElementById('garbage1').checked;
            showPaper = document.getElementById('garbage2').checked;
            showGlass = document.getElementById('garbage3').checked;
            showMetal = document.getElementById('garbage4').checked;
            if (showPlastic == false && showPaper == false && showGlass == false && showMetal == false) {
                showPlastic = true;
                showPaper = true;
                showGlass = true;
                showMetal = true;
            }
            filter = document.getElementById('filters');
            filter = filter.value;
            if (filter === "Last Week") {
                filter = "LastWeek";
            }
            if (filter === "Last Month") {
                filter = "LastMonth";
            }
            var url = 'http://localhost:80/proiect/GaSM/app/api/markers/read/getTrash.php?filter=';
            url = url.concat(filter, '&country=', country, '&city=', city);
            fetch(url).then(response => response.json())
                .then(data => {
                    var markers = data;
                    if (!("message" in data)) {
                        plastics = [];
                        papers = [];
                        glasses = [];
                        metals = [];
                        allPlastic = 0;
                        allPaper = 0;
                        allGlass = 0;
                        allMetal = 0;
                        markerCoordinates = [];
                        markers.forEach(marker => {
                            var myArray = {
                                "longitude": marker["longitude"],
                                "latitude": marker["latitude"]
                            };
                            markerCoordinates.push(myArray);
                            var time;
                            if (filter !== "Today") {
                                time = marker["time"].split(" ");
                                time = new Date(time[0]);
                            } else {
                                time = new Date(marker["time"]);
                                time.setSeconds(0);
                            }
                            if (marker["trash_type"] === "plastic") {
                                var ok = 0;
                                for (var i = 0; i < plastics.length; i++) {
                                    var time2 = new Date(plastics[i]['time']);
                                    if (time2.getTime() == time.getTime()) {
                                        plastics[i]['quantity']++;
                                        ok = 1;
                                    }
                                }
                                if (ok == 0) {
                                    var myArray = {
                                        "time": time,
                                        "quantity": 1
                                    }
                                    plastics.push(myArray);
                                }
                            }
                            if (marker["trash_type"] === "paper") {
                                var ok = 0;
                                for (var i = 0; i < papers.length; i++) {
                                    var time2 = new Date(papers[i]['time']);
                                    if (time2.getTime() == time.getTime()) {
                                        papers[i]['quantity']++;
                                        ok = 1;
                                    }
                                }
                                if (ok == 0) {
                                    var myArray = {
                                        "time": time,
                                        "quantity": 1
                                    }
                                    papers.push(myArray);
                                }
                            }
                            if (marker["trash_type"] === "glass") {
                                var ok = 0;
                                for (var i = 0; i < glasses.length; i++) {
                                    var time2 = new Date(glasses[i]['time']);
                                    if (time2.getTime() == time.getTime()) {
                                        glasses[i]['quantity']++;
                                        ok = 1;
                                    }
                                }
                                if (ok == 0) {
                                    var myArray = {
                                        "time": time,
                                        "quantity": 1
                                    }
                                    glasses.push(myArray);
                                }
                            }
                            if (marker["trash_type"] === "metal") {
                                var ok = 0;
                                for (var i = 0; i < metals.length; i++) {
                                    var time2 = new Date(metals[i]['time']);
                                    if (time2.getTime() == time.getTime()) {
                                        metals[i]['quantity']++;
                                        ok = 1;
                                    }
                                }
                                if (ok == 0) {
                                    var myArray = {
                                        "time": time,
                                        "quantity": 1
                                    }
                                    metals.push(myArray);
                                }
                            }
                        });
                        plastics.sort(compare);
                        papers.sort(compare);
                        glasses.sort(compare);
                        metals.sort(compare);

                        function compare(a, b) {
                            if (a["time"] > b["time"]) return 1;
                            if (b["time"] > a["time"]) return -1;
                            return 0;
                        }
                        plasticDps = [];
                        paperDps = [];
                        glassDps = [];
                        metalDps = [];
                        for (var i = 0; i < plastics.length; i++) {
                            var myArray = {
                                x: plastics[i]["time"],
                                y: plastics[i]["quantity"]
                            }
                            plasticDps.push(myArray);
                        }

                        for (var i = 0; i < glasses.length; i++) {
                            var myArray = {
                                x: glasses[i]["time"],
                                y: glasses[i]["quantity"]
                            }
                            glassDps.push(myArray);
                        }

                        for (var i = 0; i < papers.length; i++) {
                            var myArray = {
                                x: papers[i]["time"],
                                y: papers[i]["quantity"]
                            }
                            paperDps.push(myArray);
                        }

                        for (var i = 0; i < metals.length; i++) {
                            var myArray = {
                                x: metals[i]["time"],
                                y: metals[i]["quantity"]
                            }
                            metalDps.push(myArray);
                        }
                        loadChart();
                        for (var i = 0; i < plastics.length; i++) {
                            allPlastic = allPlastic + plastics[i]["quantity"];
                        }
                        for (var i = 0; i < papers.length; i++) {
                            allPaper = allPaper + papers[i]["quantity"];
                        }
                        for (var i = 0; i < glasses.length; i++) {
                            allGlass = allGlass + glasses[i]["quantity"];
                        }
                        for (var i = 0; i < metals.length; i++) {
                            allMetal = allMetal + metals[i]["quantity"];
                        }
                        var chart2 = new CanvasJS.Chart("pieChart", {
                            animationEnabled: true,
                            theme: "light2",
                            title: {
                                text: "Garbage distribution",
                                horizontalAlign: "left",
                                fileName: "PieChart",
                                verticalAlign: "center"
                            },
                            data: [{
                                type: "pie",
                                showInLegend: true,
                                toolTipContent: "{y} - #percent %",
                                legendText: "{indexLabel}",
                                dataPoints: [{
                                        y: allPlastic,
                                        indexLabel: "Plastic"
                                    },
                                    {
                                        y: allMetal,
                                        indexLabel: "Metal"
                                    },
                                    {
                                        y: allPaper,
                                        indexLabel: "Paper"
                                    },
                                    {
                                        y: allGlass,
                                        indexLabel: "Glass"
                                    }
                                ]
                            }]
                        });
                        chart2.render();

                        function distance(lat1, lon1, lat2, lon2, unit) {
                            if ((lat1 == lat2) && (lon1 == lon2)) {
                                return 0;
                            } else {
                                var radlat1 = Math.PI * lat1 / 180;
                                var radlat2 = Math.PI * lat2 / 180;
                                var theta = lon1 - lon2;
                                var radtheta = Math.PI * theta / 180;
                                var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
                                if (dist > 1) {
                                    dist = 1;
                                }
                                dist = Math.acos(dist);
                                dist = dist * 180 / Math.PI;
                                dist = dist * 60 * 1.1515;
                                if (unit == "K") {
                                    dist = dist * 1.609344
                                }
                                if (unit == "N") {
                                    dist = dist * 0.8684
                                }
                                return dist;
                            }
                        }
                        var allTrash = allGlass + allMetal + allPaper + allPlastic;
                        var precedentMarkerCoordinates = [];
                        var url = 'http://localhost:80/proiect/GaSM/app/api/markers/read/getPrecedentTrash.php?filter=';
                        url = url.concat(filter, '&country=', country, '&city=', city);
                        fetch(url).then(response => response.json())
                            .then(precedentData => {
                                var precedentMarkers = precedentData;
                                precedentMarkers.forEach(marker => {
                                    var myArray = {
                                        "longitude": marker["longitude"],
                                        "latitude": marker["latitude"]
                                    };
                                    precedentMarkerCoordinates.push(myArray);
                                });
                                var allPrecedentTrash = precedentMarkers.length;
                                var dif = 0;
                                if (allPrecedentTrash > allTrash) {
                                    dif = allPrecedentTrash - allTrash;
                                    dif = "- ".concat(dif, " Reports");
                                    document.getElementById("first-change-span").textContent = dif;
                                    document.getElementById("first-change").setAttribute("class", "uparrow");
                                } else {
                                    dif = allTrash - allPrecedentTrash;
                                    dif = "+ ".concat(dif, " Reports");
                                    document.getElementById("first-change-span").textContent = dif;
                                    document.getElementById("first-change").setAttribute("class", "downarrow");
                                }

                                var currentAverageMarkerDistance = 0;
                                var precedentAverageMarkerDistance = 0;
                                // console.log(markerCoordinates.length);
                                //  console.log(precedentMarkerCoordinates.length);
                                if (markerCoordinates.length <= 100) {
                                    for (var i = 0; i < markerCoordinates.length - 1; i++) {
                                        for (var j = i + 1; j < markerCoordinates.length; j++) {
                                            currentAverageMarkerDistance = currentAverageMarkerDistance + distance(markerCoordinates[i]["latitude"], markerCoordinates[i]["longitude"], markerCoordinates[j]["latitude"], markerCoordinates[j]["longitude"], "K");
                                        }
                                    }
                                    if (markerCoordinates.length != 0) {
                                        currentAverageMarkerDistance = currentAverageMarkerDistance / markerCoordinates.length;
                                    }

                                } else {
                                    for (var k = 0; k < 25; k++) {
                                        var estimatedAverageMarkerDistance = 0;
                                        var randomCoordinates = [...markerCoordinates];
                                        var selectedRandomCoordinates = [];
                                        for (var i = 0; i < 100; i++) {
                                            var index = Math.floor(Math.random() * randomCoordinates.length);
                                            selectedRandomCoordinates.push(randomCoordinates[index]);
                                            randomCoordinates.splice(index, 1);
                                        }
                                        //console.log(selectedRandomCoordinates.length);
                                        for (var i = 0; i < selectedRandomCoordinates.length - 1; i++) {
                                            for (var j = i + 1; j < selectedRandomCoordinates.length; j++) {
                                                estimatedAverageMarkerDistance = estimatedAverageMarkerDistance + distance(selectedRandomCoordinates[i]["latitude"], selectedRandomCoordinates[i]["longitude"], selectedRandomCoordinates[j]["latitude"], selectedRandomCoordinates[j]["longitude"], "K");
                                            }
                                        }
                                        estimatedAverageMarkerDistance = estimatedAverageMarkerDistance / 100;
                                        currentAverageMarkerDistance = currentAverageMarkerDistance + estimatedAverageMarkerDistance;
                                    }
                                    currentAverageMarkerDistance = currentAverageMarkerDistance / 25;
                                }


                                if (precedentMarkerCoordinates.length <= 100) {
                                    for (var i = 0; i < precedentMarkerCoordinates.length - 1; i++) {
                                        for (var j = i + 1; j < precedentMarkerCoordinates.length; j++) {
                                            precedentAverageMarkerDistance = precedentAverageMarkerDistance + distance(precedentMarkerCoordinates[i]["latitude"], precedentMarkerCoordinates[i]["longitude"], precedentMarkerCoordinates[j]["latitude"], precedentMarkerCoordinates[j]["longitude"], "K");
                                        }
                                    }
                                    if (precedentMarkerCoordinates.length != 0) {
                                        precedentAverageMarkerDistance = precedentAverageMarkerDistance / precedentMarkerCoordinates.length;
                                    }
                                } else {
                                    for (k = 0; k < 25; k++) {
                                        var estimatedAverageMarkerDistance = 0;
                                        var randomCoordinates = [...precedentMarkerCoordinates];
                                        var selectedRandomCoordinates = [];
                                        for (var i = 0; i < 100; i++) {
                                            var index = Math.floor(Math.random() * randomCoordinates.length);
                                            selectedRandomCoordinates.push(randomCoordinates[index]);
                                            randomCoordinates.splice(index, 1);
                                        }
                                        for (var i = 0; i < selectedRandomCoordinates.length - 1; i++) {
                                            for (var j = i + 1; j < selectedRandomCoordinates.length; j++) {
                                                estimatedAverageMarkerDistance = estimatedAverageMarkerDistance + distance(selectedRandomCoordinates[i]["latitude"], selectedRandomCoordinates[i]["longitude"], selectedRandomCoordinates[j]["latitude"], selectedRandomCoordinates[j]["longitude"], "K");
                                            }
                                        }
                                        estimatedAverageMarkerDistance = estimatedAverageMarkerDistance / 100;
                                        precedentAverageMarkerDistance = precedentAverageMarkerDistance + estimatedAverageMarkerDistance;
                                    }
                                    precedentAverageMarkerDistance = precedentAverageMarkerDistance / 25;
                                }
                                dif = (1 - precedentAverageMarkerDistance / currentAverageMarkerDistance) * 100;
                                dif = Math.round(dif);
                                if (dif < 0) {
                                    dif = dif.toString(10);
                                    dif = dif.substr(1);
                                    dif = "- ".concat(dif, " % Congestion");
                                    document.getElementById("second-change-span").textContent = dif;
                                    document.getElementById("second-change").setAttribute("class", "uparrow");
                                } else {
                                    dif = "+ ".concat(dif, " % Congestion");
                                    document.getElementById("second-change-span").textContent = dif;
                                    document.getElementById("second-change").setAttribute("class", "downarrow");
                                }

                            });
                    } else {
                        filter = lastFilter;
                        if (filter == "LastMonth") {
                            filter = "Last Month";
                        }
                        if (filter == "LastWeek") {
                            filter = "Last Week";
                        }
                        document.getElementById('filters').value = filter;
                        if (filter == "Last Month") {
                            filter = "LastMonth";
                        }
                        if (filter == "Last Week") {
                            filter = "LastWeek";
                        }
                    }
                });
        }
    </script>
    <script>
        changeChart();
    </script>
    <script>
        function downloadHTML() {
            var url = "http://localhost:80/proiect/GaSM/app/php/ajaxHTML.php";
            var sentData = {
                "timeFilter": filter,
                "country": country,
                "city": city,
                "county": county,
                "plastic": JSON.stringify(plastics),
                "paper": JSON.stringify(papers),
                "glass": JSON.stringify(glasses),
                "metal": JSON.stringify(metals),
                "allPlastic": allPlastic,
                "allPaper": allPaper,
                "allGlass": allGlass,
                "allMetal": allMetal
            };
            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(sentData)
                }).then(response => response.blob())
                .then(data => {
                    var url = window.URL.createObjectURL(data);
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = "report.html";
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                });
        }
    </script>
    <script>
        function downloadPDF() {
            var timeFlt = "";
            var markersByCounty;
            var markersByRegion;
            if (filter != "Today" && filter != "LastMonth" && filter != "LastWeek") {
                timeFlt = 'All Time';
            }
            if (filter == "LastMonth") {
                timeFlt = "Last Month";
            }
            if (filter == "LastWeek") {
                timeFlt = "Last Week";
            }
            if (city != 'none') {
                timeFlt = timeFlt.concat(" Report ", city);
            } else {
                timeFlt = timeFlt.concat(" Report ");
            }

            function compare(a, b) {
                if (a["quantity"] > b["quantity"]) return 1;
                if (b["quantity"] > a["quantity"]) return -1;
                return 0;
            }
            var pdf = new jsPDF('p', 'mm', [380, 400]);
            if (currentChart == "line") {
                var canvas1 = document.querySelector("#lineChart .canvasjs-chart-canvas");
            } else if (currentChart == "bar") {
                var canvas1 = document.querySelector("#barChart .canvasjs-chart-canvas");
            }
            var canvas2 = document.querySelector("#pieChart .canvasjs-chart-canvas");
            var dataURL1 = canvas1.toDataURL('image1/JPEG', 1);
            var dataURL2 = canvas2.toDataURL('image2/JPEG', 1);
            pdf.text(timeFlt, 160, 10);
            pdf.line(0, 15, 400, 15);
            pdf.addImage(dataURL1, 'JPEG', 0, 25);
            pdf.line(0, 135, 400, 135);
            pdf.addImage(dataURL2, 'JPEG', 120, 150);
            pdf.line(0, 245, 400, 245);

            getBoth()
                .then(([counties, regions]) => {
                    markersByCounty = counties;
                    markersByCounty.sort(compare);
                    var cleanestCounties = 'Cleanest Counties: \r\n\r\n';
                    var dirtiestCounties = 'Dirtiest Counties: \r\n\r\n';
                    var contor = 1;
                    if (markersByCounty != null) {
                        markersByCounty.forEach(county => {
                            if (contor <= 5) {
                                cleanestCounties = cleanestCounties.concat(county['county'], ' : ', county['quantity'], '\r\n');
                            } else if (contor > markersByCounty.length - 5) {
                                dirtiestCounties = dirtiestCounties.concat(county['county'], ' : ', county['quantity'], '\r\n');
                            }
                            contor++;
                        });
                    }
                    pdf.text(cleanestCounties, 15, 255);
                    pdf.text(dirtiestCounties, 300, 255);

                    markersByRegion = regions;
                    markersByRegion.sort(compare);
                    var cleanestRegions = 'Cleanest Cities: \r\n\r\n';
                    var dirtiestRegions = 'Dirtiest Cities: \r\n\r\n';
                    var contor = 1;
                    if (markersByRegion != null) {
                        markersByRegion.forEach(region => {
                            if (contor <= 5) {
                                cleanestRegions = cleanestRegions.concat(region['city'], ' : ', region['quantity'], '\r\n');
                            } else if (contor > markersByRegion.length - 5) {
                                dirtiestRegions = dirtiestRegions.concat(region['city'], ' : ', region['quantity'], '\r\n');
                            }
                            contor++;
                        });
                    }
                    pdf.line(0, 300, 400, 300);
                    pdf.text(cleanestRegions, 15, 310);
                    pdf.text(dirtiestRegions, 300, 310);
                    pdf.save("download.pdf");
                });

            function getByCounty() {
                var pdfURL = 'http://localhost:80/proiect/GaSM/app/api/markers/read/getByCounty.php?filter=';
                pdfURL = pdfURL.concat(filter, "&country=", country);
                return fetch(pdfURL).then(response => response.json())
            }

            function getByRegion() {
                var pdfURL = 'http://localhost:80/proiect/GaSM/app/api/markers/read/getByRegion.php?filter=';
                pdfURL = pdfURL.concat(filter, "&country=", country, "&county=", county);
                return fetch(pdfURL).then(response => response.json())
            }

            function getBoth() {
                return Promise.all([getByCounty(), getByRegion()]);
            }
        }
    </script>
    <script src="http://danml.com/js/download.js"></script>
    <script>
        function downloadCSV() {
            function blobToString(b) {
                var u, x;
                u = URL.createObjectURL(b);
                x = new XMLHttpRequest();
                x.open('GET', u, false);
                x.send();
                URL.revokeObjectURL(u);
                return x.responseText;
            }

            var csvURL = 'http://localhost:80/proiect/GaSM/app/api/markers/read/getCSV.php?filter=';
            csvURL = csvURL.concat(filter, "&country=", country, "&city=", city);
            fetch(csvURL).then(response => response.blob())
                .then(data => {
                    var csvToString = blobToString(data);
                    csvToString = csvToString.replace(/\"/g, "");
                    var csvSplit = csvToString.split("newline");
                    console.log(csvSplit.length);
                    var csvArray = [];
                    csvSplit.forEach(element => {
                        var myArray = [element];
                        csvArray.push(myArray);
                    });
                    let csvContent = "data:text/csv;charset=utf-8,";
                    csvArray.forEach(function(rowArray) {
                        let row = rowArray.join(",");
                        csvContent += row + "\r\n";
                    });
                    download(csvContent, "repot.csv", "csv");
                });
        }
    </script>
</body>

</html>
