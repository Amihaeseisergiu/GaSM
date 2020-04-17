<?php
$pls = "";
$counter = 0;
foreach ($data['plastics'] as $plastic) {
    if (count($data['plastics']) - 1 == $counter) {
        $pls = $pls . '"' . $plastic['time'] . '"';
    } else {
        $counter++;
        $pls = $pls . '"' . $plastic['time'] . '", ';
    }
};
$plsQuantity = "";
$counter = 0;
foreach ($data['plastics'] as $plastic) {
    if (count($data['plastics']) - 1 == $counter) {
        $plsQuantity = $plsQuantity . $plastic['quantity'];
    } else {
        $counter++;
        $plsQuantity = $plsQuantity .  $plastic['quantity'] . ',';
    }
}


$pap = "";
$counter = 0;
foreach ($data['papers'] as $paper) {
    if (count($data['papers']) - 1 == $counter) {
        $pap = $pap . '"' . $paper['time'] . '"';
    } else {
        $counter++;
        $pap = $pap . '"' . $paper['time'] . '", ';
    }
};
$papQuantity = "";
$counter = 0;
foreach ($data['papers'] as $paper) {
    if (count($data['papers']) - 1 == $counter) {
        $papQuantity = $papQuantity . $paper['quantity'];
    } else {
        $counter++;
        $papQuantity = $papQuantity .  $paper['quantity'] . ',';
    }
}

$gls = "";
$counter = 0;
foreach ($data['glasses'] as $glass) {
    if (count($data['glasses']) - 1 == $counter) {
        $gls = $gls . '"' . $glass['time'] . '"';
    } else {
        $counter++;
        $gls = $gls . '"' . $glass['time'] . '", ';
    }
};
$glsQuantity = "";
$counter = 0;
foreach ($data['glasses'] as $glass) {
    if (count($data['glasses']) - 1 == $counter) {
        $glsQuantity = $glsQuantity . $glass['quantity'];
    } else {
        $counter++;
        $glsQuantity = $glsQuantity .  $glass['quantity'] . ',';
    }
}

$mtl = "";
$counter = 0;
foreach ($data['metals'] as $metal) {
    if (count($data['metals']) - 1 == $counter) {
        $mtl = $mtl . '"' . $metal['time'] . '"';
    } else {
        $counter++;
        $mtl = $mtl . '"' . $metal['time'] . '", ';
    }
};
$mtlQuantity = "";
$counter = 0;
foreach ($data['metals'] as $metal) {
    if (count($data['metals']) - 1 == $counter) {
        $mtlQuantity = $mtlQuantity . $metal['quantity'];
    } else {
        $counter++;
        $mtlQuantity = $mtlQuantity .  $metal['quantity'] . ',';
    }
}
require_once('../app/php/initDownload.php');
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
                CSV Raport
            </button>
            <button class="D3Button button" type="submit" id="secondButton" value="Download file" name="downloadPDF">
                PDF Raport
            </button>
            <button class="D3Button button" type="submit" id="thirdButton" value="Download file" name="downloadHTML">
                HTML Raport
            </button>
        </form>
        <div class="chartAndButton">
            <div id="chartContainer"></div>
            <form method="get" class="form" style="position:relative; margin-top:1em; display:flex;">
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
                <input list="filters" name="filter" style="padding: 0.5em;">
                <datalist id="filters">
                    <option value="All Time">
                    <option value="Last Day">
                    <option value="Last Week">
                    <option value="Last Month">
                </datalist>
                <button type="submit" name="dateFilter" id="filterButton" style="background-color: #0ed145; color:white; padding-left:1em; padding-right:1em; margin-left:0.5em; padding-top:0.3em; padding-bottom:0.3em; color:black; font-size:1em; font-weight:bold;">Filter</button>
            </form>
            <?php
            if (isset($_GET["dateFilter"])) {
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
                        header("Location: http://localhost/proiect/GaSM/public/Statistics/Daily/" . $shownGarbageTypes);
                    } else if ($_GET["filter"] === "Last Week") {
                        header("Location: http://localhost/proiect/GaSM/public/Statistics/Weekly/" . $shownGarbageTypes);
                    } else if ($_GET["filter"] === "Last Month") {
                        header("Location: http://localhost/proiect/GaSM/public/Statistics/Monthly/" . $shownGarbageTypes);
                    } else {
                        header("Location: http://localhost/proiect/GaSM/public/Statistics/All Time/" . $shownGarbageTypes);
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
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        <?php require_once('../app/php/charts.php'); ?>
    </script>
</body>

</html>