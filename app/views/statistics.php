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
        <div class="buttons">
            <button class="D3Button button" id="firstButton">
                Download CSV
            </button>
            <button class="D3Button button" id="secondButton">
                Download PDF
            </button>
            <button class="D3Button button" id="thirdButton">
                Download HTML
            </button>
        </div>
        <div class="chartAndButton">
            <div id="chartContainer"></div>
            <form method="get" class="form">
                <label  for="plastic"> Plastic </label>
                <input type="checkbox" class="types" name="plastic">
                <label  for="paper"> Paper </label>
                <input type="checkbox" class="types" name="paper">
                <label  for="glass"> Glass </label>
                <input type="checkbox" class="types" name="glass">
                <label for="metal"> Metal </label>
                <input type="checkbox" class="types" name="metal">
                <input list="filters" name="filter">
                <datalist id="filters">
                    <option value="All Time">
                    <option value="Last Day">
                    <option value="Last Week">
                    <option value="Last Month">
                </datalist>
                <button type="submit" name="dateFilter" style="background-color: #0ed145; color:white; padding-left:1em; padding-right:1em;">Filter</button>
            </form>
            <?php
            if (isset($_GET["dateFilter"])) {
                if (isset($_GET["filter"])) {
                    $shownGarbageTypes = "";
                    if(isset($_GET["plastic"])) {
                        $shownGarbageTypes = $shownGarbageTypes . "plastic_";
                    }
                    if(isset($_GET["paper"])) {
                        $shownGarbageTypes = $shownGarbageTypes . "paper_";
                    }
                    if(isset($_GET["glass"])) {
                        $shownGarbageTypes = $shownGarbageTypes . "glass_";
                    }
                    if(isset($_GET["metal"])) {
                        $shownGarbageTypes = $shownGarbageTypes . "metal_";
                    }
                    if ($_GET["filter"] === "Last Day") {
                        header("Location: http://localhost/proiect/GaSM/public/Statistics/filterByDay/" . $shownGarbageTypes);
                    } else if ($_GET["filter"] === "Last Week") {
                        header("Location: http://localhost/proiect/GaSM/public/Statistics/filterByWeek/" . $shownGarbageTypes);
                    } else if ($_GET["filter"] === "Last Month") {
                        header("Location: http://localhost/proiect/GaSM/public/Statistics/filterByMonth/" . $shownGarbageTypes);
                    } else {
                        header("Location: http://localhost/proiect/GaSM/public/Statistics/all/" . $shownGarbageTypes);
                    }
                }
            }
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
                    <div class="uparrow"> </div>
                    <span> +3% Efficiency</span>
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
                Other stuff
            </h3>
            <div class="box-text">
                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Alias ad ullam, porro ut dolorem dolore eius
                harum temporibus id blanditiis quis dignissimos ex inventore odio molestiae quos. Officiis, quod
                exercitationem.
            </div>
        </div>
    </div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script type="text/javascript">
        var dateArrayPlastic = [<?php $counter = 0;
                                foreach ($data['plastics'] as $plastic) {
                                    if (count($data['plastics']) - 1 == $counter) {
                                        echo '"' . $plastic['time'] . '"';
                                    } else {
                                        $counter++;
                                        echo '"' . $plastic['time'] . '", ';
                                    }
                                } ?>];



        var quantitiesPlastic = [<?php $counter = 0;
                                    foreach ($data['plastics'] as $plastic) {
                                        if (count($data['plastics']) - 1 == $counter) {
                                            echo  $plastic['quantity'];
                                        } else {
                                            $counter++;
                                            echo  $plastic['quantity'] . ',';
                                        }
                                    } ?>];

        var dpsPlastic = [];
        for (var i = 0; i < dateArrayPlastic.length; i++) {
            dpsPlastic.push({
                x: new Date(dateArrayPlastic[i]),
                y: quantitiesPlastic[i]
            });
        }

        dpsPlastic.sort(function(a, b) {
            if (a['x'] > b['x']) {
                return 1;
            }
            if (b['x'] > a['x']) {
                return -1;
            }
            return 0;
        });

        var dateArrayPaper = [<?php $counter = 0;
                                foreach ($data['papers'] as $paper) {
                                    if (count($data['papers']) - 1 == $counter) {
                                        echo '"' . $paper['time'] . '"';
                                    } else {
                                        $counter++;
                                        echo '"' . $paper['time'] . '", ';
                                    }
                                } ?>];



        var quantitiesPaper = [<?php $counter = 0;
                                foreach ($data['papers'] as $paper) {
                                    if (count($data['papers']) - 1 == $counter) {
                                        echo  $paper['quantity'];
                                    } else {
                                        $counter++;
                                        echo  $paper['quantity'] . ',';
                                    }
                                } ?>];
        var dpsPaper = [];
        for (var i = 0; i < dateArrayPaper.length; i++) {
            dpsPaper.push({
                x: new Date(dateArrayPaper[i]),
                y: quantitiesPaper[i]
            });
        }

        dpsPaper.sort(function(a, b) {
            if (a['x'] > b['x']) {
                return 1;
            }
            if (b['x'] > a['x']) {
                return -1;
            }
            return 0;
        });

        var dateArrayGlass = [<?php $counter = 0;
                                foreach ($data['glasses'] as $glass) {
                                    if (count($data['glasses']) - 1 == $counter) {
                                        echo '"' . $glass['time'] . '"';
                                    } else {
                                        $counter++;
                                        echo '"' . $glass['time'] . '", ';
                                    }
                                } ?>];



        var quantitiesGlass = [<?php $counter = 0;
                                foreach ($data['glasses'] as $glass) {
                                    if (count($data['glasses']) - 1 == $counter) {
                                        echo  $glass['quantity'];
                                    } else {
                                        $counter++;
                                        echo  $glass['quantity'] . ',';
                                    }
                                } ?>];
        var dpsGlass = [];
        for (var i = 0; i < dateArrayGlass.length; i++) {
            dpsGlass.push({
                x: new Date(dateArrayGlass[i]),
                y: quantitiesGlass[i]
            });
        }

        dpsGlass.sort(function(a, b) {
            if (a['x'] > b['x']) {
                return 1;
            }
            if (b['x'] > a['x']) {
                return -1;
            }
            return 0;
        });

        var dateArrayMetal = [<?php $counter = 0;
                                foreach ($data['metals'] as $metal) {
                                    if (count($data['metals']) - 1 == $counter) {
                                        echo '"' . $metal['time'] . '"';
                                    } else {
                                        $counter++;
                                        echo '"' . $metal['time'] . '", ';
                                    }
                                } ?>];



        var quantitiesMetal = [<?php $counter = 0;
                                foreach ($data['metals'] as $metal) {
                                    if (count($data['metals']) - 1 == $counter) {
                                        echo  $metal['quantity'];
                                    } else {
                                        $counter++;
                                        echo  $metal['quantity'] . ',';
                                    }
                                } ?>];
        var dpsMetal = [];
        for (var i = 0; i < dateArrayMetal.length; i++) {
            dpsMetal.push({
                x: new Date(dateArrayMetal[i]),
                y: quantitiesMetal[i]
            });
        }

        dpsMetal.sort(function(a, b) {
            if (a['x'] > b['x']) {
                return 1;
            }
            if (b['x'] > a['x']) {
                return -1;
            }
            return 0;
        });

        var chart1 = new CanvasJS.Chart("chartContainer", {
            backgroundColor: "white",
            title: {
                text: "Garbage distribution"
            },
            data: [{
                    showInLegend: true,
                    name: "series1",
                    legendText: "Plastic",
                    visible :  '<?php if($data['garbageToShow']['plastic'] === true) echo true; else echo false; ?>',
                    type: "line",
                    dataPoints: dpsPlastic
                },
                {
                    showInLegend: true,
                    name: "series2",
                    legendText: "Paper",
                    visible :  '<?php if($data['garbageToShow']['paper'] === true) echo true; else echo false; ?>',
                    type: "line",
                    dataPoints: dpsPaper
                },
                {
                    showInLegend: true,
                    name: "series3",
                    legendText: "Glass",
                    visible :  '<?php if($data['garbageToShow']['glass'] === true) echo true; else echo false; ?>',
                    type: "line",
                    dataPoints: dpsGlass
                },
                {
                    showInLegend: true,
                    name: "series4",
                    legendText: "Metal",
                    visible :  '<?php if($data['garbageToShow']['metal'] === true) echo true; else echo false; ?>',
                    type: "line",
                    dataPoints: dpsMetal
                }
            ]
        });
        chart1.render();
        var allPlastic = 0;
        for (var i = 0; i < dateArrayPlastic.length; i++) {
            allPlastic = allPlastic + quantitiesPlastic[i];
        }
        var allPaper = 0;
        for (var i = 0; i < dateArrayPaper.length; i++) {
            allPaper = allPaper + quantitiesPaper[i];
        }
        var allGlass = 0;
        for (var i = 0; i < dateArrayGlass.length; i++) {
            allGlass = allGlass + quantitiesGlass[i];
        }
        var allMetal = 0;
        for (var i = 0; i < dateArrayMetal.length; i++) {
            allMetal = allMetal + quantitiesMetal[i];
        }
        var chart2 = new CanvasJS.Chart("pieChart", {
            theme: "light2",
            title: {
                text: "Garbage distribution",
                horizontalAlign: "left",
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
    </script>
</body>

</html>
