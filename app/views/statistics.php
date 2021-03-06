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
            <button onclick="location.href = 'http://localhost/proiect/GaSM/public/Campaign/index/0'" class="D3Button">
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
                <div id="chartContainer">
                    <button onclick="resetZoom()" id="resetZoom">R</button>
                    <div id="chartWrapper">
                        <canvas id="chart"></canvas>
                    </div>
                </div>
                <div id="rightButton" onclick="switchChart()">
                    <div class="rightarrow"></div>
                </div>
            </div>
            <div id="filterContainer">
                <div id="garbageLabels">
                    <input type="checkbox" id="garbage1" name="plastic" value="Plastic">
                    <label for="garbage1" class="garbageLabel"> Plastic </label>

                    <input type="checkbox" id="garbage2" name="paper" value="Paper">
                    <label for="garbage2" class="garbageLabel"> Paper </label>

                    <input type="checkbox" id="garbage3" name="glass" value="Glass">
                    <label for="garbage3" class="garbageLabel"> Glass </label>

                    <input type="checkbox" id="garbage4" name="metal" value="Metal">
                    <label for="garbage4" class="garbageLabel"> Metal </label>
                </div>
                <select name="filters" id="filters">
                    <option value="All Time">All Time</option>
                    <option value="Last Week">Last Week</option>
                    <option value="Last Month">Last Month</option>
                    <option value="Today">Today</option>
                </select>
                <button onclick="changeChart()" name="dateFilter" id="filterButton">Filter</button>
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
                    <div id="third-change"></div>
                    <span id="third-change-span"> </span>
                </div>
            </div>
        </div>
        <div class="box2">
            <h3 class="box-p">
                Garbage distribution
            </h3>
            <div id="pieContainer">
                <canvas id="pieChart"></canvas>
            </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@0.7.4"></script>
    <script src="http://localhost/proiect/GaSM/app/javascript/jspdf.min.js"></script>
    <script>
        var country = '<?php echo $_SESSION['country']; ?>';
        var city = '<?php echo $_SESSION['city'] ?>';
        var county = '<?php echo $_SESSION['county']; ?>';

        function switchChart() {
            if (currentChart === "line") {
                // var chartId = document.getElementById(currentChart.concat("Chart"));
                //chartId.setAttribute("id", "barChart");
                currentChart = "bar";
            } else if (currentChart == "bar") {
                //  var chartId = document.getElementById(currentChart.concat("Chart"));
                //chartId.setAttribute("id", "chart");
                if (city != 'none') {
                    currentChart = "country";
                } else {
                    currentChart = "line";
                }
            } else if (currentChart == "country") {
                currentChart = "county";
            } else if (currentChart == "county") {
                currentChart = "line";
            }
            loadChart();
        }
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
        var chart;
        var pieChart;
        var countyDps = [];
        var cityDps = [];

        resetZoom = function() {
            chart.resetZoom();
        };

        function loadChart() {
            if (filter == "Today") {
                var unit = 'hour';
            } else {
                var unit = 'day';
            }
            if (chart) {
                chart.destroy();
            }
            if (currentChart === "line") {
                var ctx = document.getElementById('chart').getContext('2d');
                chart = new Chart(ctx, {
                    // The type of chart we want to create
                    type: 'line',
                    fill: false,
                    // The data for our dataset
                    data: {
                        datasets: [{
                                label: 'Plastic',
                                borderColor: 'red',
                                hidden: showPlastic,
                                data: plasticDps
                            },
                            {
                                label: 'Paper',
                                borderColor: 'yellow',
                                hidden: showPaper,
                                data: paperDps
                            },
                            {
                                label: 'Glass',
                                borderColor: 'blue',
                                hidden: showGlass,
                                data: glassDps
                            },
                            {
                                label: 'Metal',
                                borderColor: 'green',
                                hidden: showMetal,
                                data: metalDps
                            }
                        ]
                    },

                    // Configuration options go here
                    options: {
                        maintainAspectRatio: false,
                        scales: {
                            xAxes: [{
                                type: 'time',
                                time: {
                                    unit: unit
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    stepSize: 1
                                }
                            }]
                        },
                        plugins: {
                            zoom: {
                                // Container for pan options

                                // Container for zoom options
                                zoom: {
                                    // Boolean to enable zooming
                                    enabled: true,

                                    // Enable drag-to-zoom behavior

                                    // Drag-to-zoom effect can be customized
                                    drag: {
                                        borderColor: 'rgba(225,225,225,0.3)',
                                        borderWidth: 5,
                                        backgroundColor: 'rgb(225,225,225)',
                                        animationDuration: 0
                                    },

                                    mode: 'x',
                                    speed: 0.1,

                                    // On category scale, minimal zoom level before actually applying zoom
                                    sensitivity: 3
                                }
                            }
                        }
                    }
                });
                // var myArray = {
                //     "line": chart
                //  };
                //  charts.push(myArray);
            } else if (currentChart == "bar") {
                var ctx = document.getElementById('chart').getContext('2d');
                chart = new Chart(ctx, {
                    // The type of chart we want to create
                    type: 'bar',
                    // The data for our dataset
                    data: {
                        datasets: [{
                                label: 'Plastic',
                                fill: false,
                                data: plasticDps,
                                backgroundColor: 'red',
                                borderColor: 'red',
                                hidden: showPlastic,
                                borderWidth: 1
                            },
                            {
                                label: 'Paper',
                                fill: false,
                                data: paperDps,
                                backgroundColor: 'yellow',
                                borderColor: 'yellow',
                                hidden: showPaper,
                                borderWidth: 1
                            },
                            {
                                label: 'Glass',
                                fill: false,
                                data: glassDps,
                                backgroundColor: 'blue',
                                borderColor: 'blue',
                                hidden: showGlass,
                                borderWidth: 1
                            },
                            {
                                label: 'Metal',
                                fill: false,
                                data: metalDps,
                                backgroundColor: 'green',
                                borderColor: 'green',
                                hidden: showMetal,
                                borderWidth: 1
                            }
                        ]
                    },

                    // Configuration options go here
                    options: {
                        maintainAspectRatio: false,
                        scales: {
                            xAxes: [{
                                type: 'time',
                                time: {
                                    unit: unit
                                }
                            }]
                        },
                        plugins: {
                            zoom: {
                                zoom: {
                                    enabled: true,
                                    drag: {
                                        borderColor: 'rgba(225,225,225,0.3)',
                                        borderWidth: 5,
                                        backgroundColor: 'rgb(225,225,225)',
                                        animationDuration: 0
                                    },
                                    mode: 'x',
                                    speed: 0.1,
                                    sensitivity: 3
                                }
                            }
                        }
                    }
                });
            } else if (currentChart == "country") {
                if (filter == 'All Time') {
                    var concatFilter = "AllTime";
                } else {
                    concatFilter = filter;
                }

                function getPlastic() {
                    var URL = 'http://localhost:80/proiect/GaSM/public/api/markers/quantity/';
                    URL = URL.concat(country, '?filter=', concatFilter, '&type=plastic');
                    return fetch(URL).then(response => response.json()).catch(error => console.log(error));
                }

                function getPaper() {
                    var URL = 'http://localhost:80/proiect/GaSM/public/api/markers/quantity/';
                    URL = URL.concat(country, '?filter=', concatFilter, '&type=paper');
                    return fetch(URL).then(response => response.json()).catch(error => console.log(error));
                }

                function getGlass() {
                    var URL = 'http://localhost:80/proiect/GaSM/public/api/markers/quantity/';
                    URL = URL.concat(country, '?filter=', concatFilter, '&type=glass');
                    return fetch(URL).then(response => response.json()).catch(error => console.log(error));
                }

                function getMetal() {
                    var URL = 'http://localhost:80/proiect/GaSM/public/api/markers/quantity/';
                    URL = URL.concat(country, '?filter=', concatFilter, '&type=metal');
                    return fetch(URL).then(response => response.json()).catch(error => console.log(error));
                }

                function getAll() {
                    return Promise.all([getPlastic(), getPaper(), getGlass(), getMetal()]);
                }

                getAll()
                    .then(([plasticQ, paperQ, glassQ, metalQ]) => {
                        var countyLabels = [];
                        var countyPlasticData = [];
                        var countyPaperData = [];
                        var countyGlassData = [];
                        var countyMetalData = [];
                        if (!("message" in plasticQ)) {
                            plasticQ.forEach(marker => {
                                if (!countyLabels.includes(marker["county"])) {
                                    countyLabels.push(marker["county"]);
                                }
                            });
                        }
                        if (!("message" in paperQ)) {
                            paperQ.forEach(marker => {
                                if (!countyLabels.includes(marker["county"])) {
                                    countyLabels.push(marker["county"]);
                                }
                            });
                        }
                        if (!("message" in glassQ)) {
                            glassQ.forEach(marker => {
                                if (!countyLabels.includes(marker["county"])) {
                                    countyLabels.push(marker["county"]);
                                }
                            });
                        }
                        if (!("message" in glassQ)) {
                            metalQ.forEach(marker => {
                                if (!countyLabels.includes(marker["county"])) {
                                    countyLabels.push(marker["county"]);
                                }
                            });
                        }
                        countyLabels.forEach(county => {
                            var quantity = 0;
                            if (!("message" in plasticQ)) {
                                plasticQ.forEach(plastic => {
                                    if (plastic["county"] == county) {
                                        quantity = plastic["quantity"];
                                    }
                                });
                            }
                            countyPlasticData.push(quantity);

                            var quantity = 0;
                            if (!("message" in paperQ)) {
                                paperQ.forEach(paper => {
                                    if (paper["county"] == county) {
                                        quantity = paper["quantity"];
                                    }
                                });
                            }
                            countyPaperData.push(quantity);

                            var quantity = 0;
                            if (!("message" in glassQ)) {
                                glassQ.forEach(glass => {
                                    if (glass["county"] == county) {
                                        quantity = glass["quantity"];
                                    }
                                });
                            }
                            countyGlassData.push(quantity);

                            var quantity = 0;
                            if (!("message" in metalQ)) {
                                metalQ.forEach(metal => {
                                    if (metal["county"] == county) {
                                        quantity = metal["quantity"];
                                    }
                                });
                            }
                            countyMetalData.push(quantity);
                        });
                        var ctx = document.getElementById('chart').getContext('2d');
                        chart = new Chart(ctx, {
                            // The type of chart we want to create
                            type: 'bar',
                            // The data for our dataset
                            data: {
                                labels: countyLabels,
                                datasets: [{
                                        fill: false,
                                        data: countyPlasticData,
                                        label: 'Plastic',
                                        backgroundColor: 'red',
                                        borderColor: 'red',
                                        hidden: showPlastic,
                                        borderWidth: 1
                                    },
                                    {
                                        fill: false,
                                        data: countyPaperData,
                                        label: 'Paper',
                                        backgroundColor: 'yellow',
                                        borderColor: 'yellow',
                                        hidden: showPaper,
                                        borderWidth: 1
                                    },
                                    {
                                        fill: false,
                                        data: countyGlassData,
                                        label: 'Glass',
                                        backgroundColor: 'blue',
                                        borderColor: 'blue',
                                        hidden: showGlass,
                                        borderWidth: 1
                                    },
                                    {
                                        fill: false,
                                        data: countyMetalData,
                                        label: 'Metal',
                                        backgroundColor: 'green',
                                        borderColor: 'green',
                                        hidden: showMetal,
                                        borderWidth: 1
                                    }
                                ]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        stacked: true,
                                        ticks: {
                                            min: 0,
                                            max: 100
                                        }
                                    }],
                                    xAxes: [{
                                        stacked: true
                                    }]
                                },
                                maintainAspectRatio: false,
                                plugins: {
                                    zoom: {
                                        pan: {
                                            enabled: true,
                                            mode: 'y'
                                        }
                                    }
                                }
                            }
                        });
                    });
            } else if (currentChart == "county") {
                if (filter == 'All Time') {
                    var concatFilter = "AllTime";
                } else {
                    concatFilter = filter;
                }

                function getPlastic() {
                    var URL = 'http://localhost:80/proiect/GaSM/public/api/markers/quantity/';
                    URL = URL.concat(country, '/', city, '?filter=', concatFilter, '&type=plastic');
                    return fetch(URL).then(response => response.json()).catch(error => console.log(error));
                }

                function getPaper() {
                    var URL = 'http://localhost:80/proiect/GaSM/public/api/markers/quantity/';
                    URL = URL.concat(country, '/', city, '?filter=', concatFilter, '&type=paper');
                    return fetch(URL).then(response => response.json()).catch(error => console.log(error));
                }

                function getGlass() {
                    var URL = 'http://localhost:80/proiect/GaSM/public/api/markers/quantity/';
                    URL = URL.concat(country, '/', city, '?filter=', concatFilter, '&type=glass');
                    return fetch(URL).then(response => response.json()).catch(error => console.log(error));
                }

                function getMetal() {
                    var URL = 'http://localhost:80/proiect/GaSM/public/api/markers/quantity/';
                    URL = URL.concat(country, '/', city, '?filter=', concatFilter, '&type=metal');
                    return fetch(URL).then(response => response.json()).catch(error => console.log(error));
                }

                function getAll() {
                    return Promise.all([getPlastic(), getPaper(), getGlass(), getMetal()]);
                }

                getAll()
                    .then(([plasticQ, paperQ, glassQ, metalQ]) => {
                        // countyDps = data;
                        var countyLabels = [];
                        var countyPlasticData = [];
                        var countyPaperData = [];
                        var countyGlassData = [];
                        var countyMetalData = [];
                        if (!("message" in plasticQ)) {
                            plasticQ.forEach(marker => {
                                if (!countyLabels.includes(marker["city"])) {
                                    countyLabels.push(marker["city"]);
                                }
                            });
                        }
                        if (!("message" in paperQ)) {
                            paperQ.forEach(marker => {
                                if (!countyLabels.includes(marker["city"])) {
                                    countyLabels.push(marker["city"]);
                                }
                            });
                        }
                        if (!("message" in glassQ)) {
                            glassQ.forEach(marker => {
                                if (!countyLabels.includes(marker["city"])) {
                                    countyLabels.push(marker["city"]);
                                }
                            });
                        }
                        if (!("message" in glassQ)) {
                            metalQ.forEach(marker => {
                                if (!countyLabels.includes(marker["city"])) {
                                    countyLabels.push(marker["city"]);
                                }
                            });
                        }
                        countyLabels.forEach(county => {
                            var quantity = 0;
                            if (!("message" in plasticQ)) {
                                plasticQ.forEach(plastic => {
                                    if (plastic["city"] == county) {
                                        quantity = plastic["quantity"];
                                    }
                                });
                            }
                            countyPlasticData.push(quantity);

                            var quantity = 0;
                            if (!("message" in paperQ)) {
                                paperQ.forEach(paper => {
                                    if (paper["city"] == county) {
                                        quantity = paper["quantity"];
                                    }
                                });
                            }
                            countyPaperData.push(quantity);

                            var quantity = 0;
                            if (!("message" in glassQ)) {
                                glassQ.forEach(glass => {
                                    if (glass["city"] == county) {
                                        quantity = glass["quantity"];
                                    }
                                });
                            }
                            countyGlassData.push(quantity);

                            var quantity = 0;
                            if (!("message" in metalQ)) {
                                metalQ.forEach(metal => {
                                    if (metal["city"] == county) {
                                        quantity = metal["quantity"];
                                    }
                                });
                            }
                            countyMetalData.push(quantity);
                        });
                        var ctx = document.getElementById('chart').getContext('2d');
                        chart = new Chart(ctx, {
                            // The type of chart we want to create
                            type: 'bar',
                            // The data for our dataset
                            data: {
                                labels: countyLabels,
                                datasets: [{
                                        fill: false,
                                        data: countyPlasticData,
                                        label: 'Plastic',
                                        backgroundColor: 'red',
                                        borderColor: 'red',
                                        hidden: showPlastic,
                                        borderWidth: 1
                                    },
                                    {
                                        fill: false,
                                        data: countyPaperData,
                                        label: 'Paper',
                                        backgroundColor: 'yellow',
                                        borderColor: 'yellow',
                                        hidden: showPaper,
                                        borderWidth: 1
                                    },
                                    {
                                        fill: false,
                                        data: countyGlassData,
                                        label: 'Glass',
                                        backgroundColor: 'blue',
                                        borderColor: 'blue',
                                        hidden: showGlass,
                                        borderWidth: 1
                                    },
                                    {
                                        fill: false,
                                        data: countyMetalData,
                                        label: 'Metal',
                                        backgroundColor: 'green',
                                        borderColor: 'green',
                                        hidden: showMetal,
                                        borderWidth: 1
                                    }
                                ]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        stacked: true,
                                        ticks: {
                                            min: 0,
                                            max: 100
                                        }
                                    }],
                                    xAxes: [{
                                        stacked: true
                                    }]
                                },
                                maintainAspectRatio: false,
                                plugins: {
                                    zoom: {
                                        pan: {
                                            enabled: true,
                                            mode: 'y'
                                        }
                                    }
                                }
                            }
                        });
                    });
            }
        }

        function changeChart() {
            lastFilter = filter;
            showPlastic = document.getElementById('garbage1').checked;
            showPaper = document.getElementById('garbage2').checked;
            showGlass = document.getElementById('garbage3').checked;
            showMetal = document.getElementById('garbage4').checked;
            if (showPlastic == false && showPaper == false && showGlass == false && showMetal == false) {} else {
                showPlastic = !showPlastic;
                showPaper = !showPaper;
                showGlass = !showGlass;
                showMetal = !showMetal;
            }
            filter = document.getElementById('filters');
            filter = filter.value;
            if (filter === "Last Week") {
                filter = "LastWeek";
            }
            if (filter === "Last Month") {
                filter = "LastMonth";
            }
            if (filter === "All Time") {
                filter = "AllTime";
            }
            var url = 'http://localhost/proiect/GasM/public/api/markers';
            if (country != 'none') {
                url = url.concat('/', country, '/', city, '?filter=', filter);
            } else {
                url = url.concat('?filter=', filter);
            }
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
                        var totalCurrent = markers.length;
                        allMetal = 0;
                        markerCoordinates = [];
                        var averageCurrentWaitingTime = 0;
                        var averagePrecedentWaitingTime = 0;
                        markers.forEach(marker => {
                            var auxTime1 = new Date(marker["time"]);
                            if (marker["remove-time"] == null) {
                                var auxTime2 = new Date();
                                var date = auxTime2.getFullYear() + '-' + (auxTime2.getMonth() + 1) + '-' + auxTime2.getDate();
                                var time = auxTime2.getHours() + ":" + auxTime2.getMinutes() + ":" + auxTime2.getSeconds();
                                var auxTime2 = date + ' ' + time;
                                auxTime2 = new Date(auxTime2);
                            } else {
                                var auxTime2 = new Date(marker["remove-time"]);
                            }
                            var diffInMilliSeconds = Math.abs(auxTime2 - auxTime1);
                            var minutes = Math.floor(diffInMilliSeconds / 60000);
                            averageCurrentWaitingTime = averageCurrentWaitingTime + minutes;
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
                        averageCurrentWaitingTime = averageCurrentWaitingTime / totalCurrent;
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
                        if (pieChart) {
                            pieChart.destroy();
                        }
                        var ctx2 = document.getElementById("pieChart");
                        ctx2.height = 135;
                        //ctx2.width = 200;
                        pieChart = new Chart(ctx2, {
                            type: 'pie',
                            data: {
                                labels: ["Plastic", "Paper", "Glass", "Metal"],
                                datasets: [{
                                    label: "Reports",
                                    backgroundColor: ["red", "yellow", "blue", "green"],
                                    data: [allPlastic, allPaper, allGlass, allMetal]
                                }]
                            },
                            options: {
                                // maintainAspectRatio: false
                            }
                        });

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
                        var allTrash = markers.length;
                        var precedentMarkerCoordinates = [];
                        var url = 'http://localhost/proiect/GasM/public/api/markers/precedent';
                        if (country != 'none') {
                            url = url.concat('/', country, '/', city, '?filter=', filter);
                        } else {
                            url = url.concat('?filter=', filter);
                        }
                        fetch(url).then(response => response.json())
                            .then(precedentData => {
                                if (!("message" in precedentData)) {
                                    var precedentMarkers = precedentData;
                                    var totalPrecedent = precedentMarkers.length;
                                    precedentMarkers.forEach(marker => {
                                        var auxTime1 = new Date(marker["time"]);
                                        if (marker["remove-time"] == null) {
                                            var auxTime2 = new Date();
                                            var date = auxTime2.getFullYear() + '-' + (auxTime2.getMonth() + 1) + '-' + auxTime2.getDate();
                                            var time = auxTime2.getHours() + ":" + auxTime2.getMinutes() + ":" + auxTime2.getSeconds();
                                            var auxTime2 = date + ' ' + time;
                                            auxTime2 = new Date(auxTime2);
                                        } else {
                                            var auxTime2 = new Date(marker["remove-time"]);
                                        }
                                        var diffInMilliSeconds = Math.abs(auxTime2 - auxTime1);
                                        var minutes = Math.floor(diffInMilliSeconds / 60000);
                                        averagePrecedentWaitingTime = averagePrecedentWaitingTime + minutes;
                                        var myArray = {
                                            "longitude": marker["longitude"],
                                            "latitude": marker["latitude"]
                                        };
                                        precedentMarkerCoordinates.push(myArray);
                                    });
                                    averagePrecedentWaitingTime = averagePrecedentWaitingTime / totalPrecedent;
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
                                    //
                                    dif = (1 - averagePrecedentWaitingTime / averageCurrentWaitingTime) * 100;
                                    dif = Math.round(dif);
                                    if (dif < 0) {
                                        dif = dif.toString(10);
                                        dif = dif.substr(1);
                                        dif = "+ ".concat(dif, " % Speed");
                                        document.getElementById("third-change-span").textContent = dif;
                                        document.getElementById("third-change").setAttribute("class", "uparrow");
                                    } else {
                                        dif = "- ".concat(dif, " % Speed");
                                        document.getElementById("third-change-span").textContent = dif;
                                        document.getElementById("third-change").setAttribute("class", "downarrow");
                                    }
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
                }).catch(error => console.log(error));
        }
        changeChart();

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
            if (filter == "Today") {
                timeFlt = "Today";
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
            var pdf = new jsPDF('p', 'mm', [390, 400]);
            // if (currentChart == "line") {
            var canvas1 = document.querySelector("#chart");
            //  } else if (currentChart == "bar") {
            //       var canvas1 = document.querySelector("#barChart .canvasjs-chart-canvas");
            //  }

            canvas1.resizeAndExport = function(width, height) {
                var c = document.createElement('canvas');
                c.width = width;
                c.height = height;
                c.getContext('2d').drawImage(this, 0, 0, this.width, this.height, 0, 0, width, height);
                return c.toDataURL();
            }
            var img = new Image();
            img.src = canvas1.resizeAndExport(1460, 550);

            var canvas2 = document.querySelector("#pieChart");
            //var dataURL1 = canvas1.toDataURL('image1/JPEG', 1);
            var dataURL2 = canvas2.toDataURL('image2/JPEG', 1);
            pdf.text(timeFlt, 160, 10);
            pdf.line(0, 15, 400, 15);
            pdf.addImage(img, 'JPEG', 0, 15);
            pdf.line(0, 160, 400, 160);
            pdf.addImage(dataURL2, 'JPEG', 120, 180);
            pdf.line(0, 245, 400, 245);

            if (country != 'none' && county != 'none') {
                getBoth()
                    .then(([counties, regions]) => {
                        if (!("message" in counties) && !("message" in regions)) {
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
                            pdf.line(0, 355, 400, 355);
                            var timeFlt2 = '';
                            if (filter == "AllTime") {
                                timeFlt2 = 'All Time';
                            }
                            if (filter == "LastMonth") {
                                timeFlt2 = "Monthly";
                            }
                            if (filter == "LastWeek") {
                                timeFlt2 = "Weekly";
                            }
                            if (filter == "Today") {
                                timeFlt2 = "Daily";
                            }
                            var firstChange = document.getElementById('first-change-span').textContent;
                            var secondChange = document.getElementById('second-change-span').textContent;
                            var thirdChange = document.getElementById('third-change-span').textContent;
                            var changes = timeFlt2.concat(' Changes\r\n', firstChange, '\r\n', secondChange, '\r\n', thirdChange);
                            pdf.text(changes, 160, 365);
                            pdf.save("download.pdf");
                        } else {
                            pdf.save("download.pdf");
                        }
                    });
            } else {
                var timeFlt2 = '';
                if (filter == "AllTime") {
                    timeFlt2 = 'All Time';
                }
                if (filter == "LastMonth") {
                    timeFlt2 = "Monthly";
                }
                if (filter == "LastWeek") {
                    timeFlt2 = "Weekly";
                }
                if (filter == "Today") {
                    timeFlt2 = "Daily";
                }
                var firstChange = document.getElementById('first-change-span').textContent;
                var secondChange = document.getElementById('second-change-span').textContent;
                var thirdChange = document.getElementById('third-change-span').textContent;
                var changes = timeFlt2.concat(' Changes\r\n', firstChange, '\r\n', secondChange, '\r\n', thirdChange);
                pdf.text(changes, 160, 260);
                pdf.save("download.pdf");
            }

            function getByCounty() {
                var pdfURL = 'http://localhost:80/proiect/GaSM/public/api/markers/quantity';
                pdfURL = pdfURL.concat("/", country, "?filter=", filter);
                return fetch(pdfURL).then(response => response.json()).catch(error => console.log(error));
            }

            function getByRegion() {
                var pdfURL = 'http://localhost:80/proiect/GaSM/public/api/markers/quantity';
                pdfURL = pdfURL.concat("/", country, "/", county, "?filter=", filter);
                return fetch(pdfURL).then(response => response.json()).catch(error => console.log(error));
            }

            function getBoth() {
                return Promise.all([getByCounty(), getByRegion()]);
            }
        }

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

            var csvURL = 'http://localhost:80/proiect/GaSM/app/php/getCSV.php?filter=';
            csvURL = csvURL.concat(filter, "&country=", country, "&city=", city);
            fetch(csvURL).then(handleErrors).then(response => response.blob())
                .then(data => {
                    var csvToString = blobToString(data);
                    csvToString = csvToString.replace(/\"/g, "");
                    var csvSplit = csvToString.split("newline");
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
                    var encodedUri = encodeURI(csvContent);
                    var link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", "report.csv");
                    document.body.appendChild(link);

                    link.click();
                    link.remove();
                });
        }

        function handleErrors(response) {
            if (!response.ok) {
                throw new Error(response.statusText);
            }
            return response;
        }
    </script>
</body>

</html>