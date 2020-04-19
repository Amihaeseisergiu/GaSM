<?php
require_once('../app/php/initTrash.php');
require_once('../app/php/initHTML.php');
require_once('../app/php/initCSV.php');
?>
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
        </div>
    </div>
    <div class="main1">
        <form class="buttons" method="GET">
            <button class="D3Button button" type="submit" id="firstButton" value="Download file" name="downloadCSV">
                CSV Report
            </button>
            <button class="D3Button button" id="secondButton" value="Download file" name="downloadPDF">
                PDF Report
            </button>
            <button class="D3Button button" type="submit" id="thirdButton" value="Download file" name="downloadHTML">
                HTML Report
            </button>
        </form>
        <div class="chartAndButton">
            <div id="chartContainer"></div>
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
                <select id="filters">
                    <option value="Today">Today</option>
                    <option value="Last Week">Last Week</option>
                    <option value="Last Month">Last Month</option>
                    <option value="All Time">All Time</option>
                </select>
                <button onclick="changeChart()" name="dateFilter" id="filterButton" style="background-color: #0ed145; color:white; padding-left:1em; padding-right:1em; margin-left:0.5em; padding-top:0.3em; padding-bottom:0.3em; color:black; font-size:1em; font-weight:bold;">Filter</button>
            </div>
            <?php
            /*  if (isset($_GET["dateFilter"])) {
                if (isset($_GET["filter"])) {
                    $shownGarbageTypes = "";
                    if (isset($_GET["plastic"])) {
                        $shownGarbageTypes = $shownGarbageTypes . "plastic_";
                    }
                    if (isset($_GET["paper"])) {
                        $shownGarbageTypes = $shownGarbageTypes . "paper_";
                    }
                    if (isset($_GET["glass"])) {
                        $shownGarbageTypes = $shownGarbageTypes . "glass_";
                    }
                    if (isset($_GET["metal"])) {
                        $shownGarbageTypes = $shownGarbageTypes . "metal_";
                    }
                    if ($_GET["filter"] === "Last Day") {
                        header("Location: http://localhost/proiect/GaSM/public/Statistics/Today/" . $shownGarbageTypes);
                    } else if ($_GET["filter"] === "Last Week") {
                        header("Location: http://localhost/proiect/GaSM/public/Statistics/Weekly/" . $shownGarbageTypes);
                    } else if ($_GET["filter"] === "Last Month") {
                        header("Location: http://localhost/proiect/GaSM/public/Statistics/Monthly/" . $shownGarbageTypes);
                    } else {
                        header("Location: http://localhost/proiect/GaSM/public/Statistics/AllTime/" . $shownGarbageTypes);
                    }
                }
            }*/
            ?>
        </div>
    </div>
    <div class="main2">
        <div class="box1">
            <h3 class="box-p">
                Changes
            </h3>
            <div class="changes">
                <div class="box-firstchange">
                    <div class="<?php echo $data['changes'][0]['arrow'] ?>"> </div>
                    <span> <?php echo $data['changes'][0]['diff']; ?> </span>
                </div>
                <div class="box-secondchange">
                    <div class="uparrow"></div>
                    <span> -5% Congestion </span>
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
    <div id="demo"></div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        <?php require_once('../app/php/initChart.php'); ?>
    </script>
    <script src="http://localhost/proiect/GaSM/app/javascript/jspdf.min.js"></script>
    <script>
        <?php require_once('../app/php/initPDF.php'); ?>
    </script>
    <script>
        function changeChart() {
            filter = document.getElementById('filters').value;
            fetch('http://localhost:80/proiect/GaSM/app/api/DataBaseFetch.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        "filter": filter
                    })
                }).then(response => response.text())
                .then(data => {

                });

            var chart1 = new CanvasJS.Chart("chartContainer", {
                backgroundColor: "white",
                fileName: "LineChart",
                title: {
                    text: "Garbage distribution"
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
                        visible: <?php if ($data['garbageToShow']['plastic'] === true) echo 'true';
                                    else echo 'false'; ?>,
                        type: "line",
                        dataPoints: dpsPlastic
                    },
                    {
                        showInLegend: true,
                        name: "series2",
                        legendText: "Paper",
                        visible: <?php if ($data['garbageToShow']['paper'] === true) echo 'true';
                                    else echo 'false'; ?>,
                        type: "line",
                        dataPoints: dpsPaper
                    },
                    {
                        showInLegend: true,
                        name: "series3",
                        legendText: "Glass",
                        visible: <?php if ($data['garbageToShow']['glass'] === true) echo 'true';
                                    else echo 'false'; ?>,
                        type: "line",
                        dataPoints: dpsGlass
                    },
                    {
                        showInLegend: true,
                        name: "series4",
                        legendText: "Metal",
                        visible: <?php if ($data['garbageToShow']['metal'] === true) echo 'true';
                                    else echo 'false'; ?>,
                        type: "line",
                        dataPoints: dpsMetal
                    }
                ]
            });
            chart1.render();
        }
    </script>
</body>

</html>