<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$json = file_get_contents("php://input");
$data = json_decode($json, true);

if ($data['timeFilter'] === "All Time" || $data['timeFilter'] == '') {
    $data['timeFilter'] = "AllTime";
}

$curl = curl_init();
$markersByCounty = array();
curl_setopt_array($curl, array(
    CURLOPT_URL => "http://localhost:80/proiect/GaSM/app/api/markers/read/getByCounty.php?filter=" . $data['timeFilter'] . '&country=' . $data['country'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache"
    ),
));

$markersByCounty = curl_exec($curl);
$markersByCounty = json_decode($markersByCounty, true);
$err = curl_error($curl);

curl_close($curl);
if ($markersByCounty != null) {
    usort($markersByCounty, function ($a, $b) {
        return $a['quantity'] - $b['quantity'];
    });
}

$markersByRegion = array();

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "http://localhost:80/proiect/GaSM/app/api/markers/read/getByRegion.php?filter=" . $data['timeFilter'] . '&county=' . $data['county'] . '&country=' . $data['country'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache"
    ),
));
// echo curl_exec($curl);
$markersByRegion = curl_exec($curl);
$markersByRegion = json_decode($markersByRegion, true);
$err = curl_error($curl);

curl_close($curl);
if ($markersByRegion != null) {
    usort($markersByRegion, function ($a, $b) {
        return $a['quantity'] - $b['quantity'];
    });
}
if (isset($data['timeFilter'])) {
    $doc = new DOMDocument();
    $doc->loadHTML('<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Report</title>
    </head>
    <body>
        
    </body>
    </html>');
    $str = file_get_contents(__DIR__ . '../initChart.php');
    $str = str_replace('<?php echo $pls; ?>', $data['plastic'], $str);
    $str = str_replace('<?php echo $pap; ?>', $data['paper'], $str);
    $str = str_replace('<?php echo $gls; ?>', $data['glass'], $str);
    $str = str_replace('<?php echo $mtl; ?>', $data['metal'], $str);
    $str = str_replace('<?php echo $plsQuantity; ?>', $data['allPlastic'], $str);
    $str = str_replace('<?php echo $papQuantity; ?>', $data['allPaper'], $str);
    $str = str_replace('<?php echo $glsQuantity; ?>', $data['allGlass'], $str);
    $str = str_replace('<?php echo $mtlQuantity; ?>', $data['allMetal'], $str);
  /*  $str = str_replace('<?php if ($data[\'garbageToShow\'][\'plastic\'] === true) echo \'true\';
                            else echo \'false\'; ?>', 'true', $str);
    $str = str_replace('<?php if ($data[\'garbageToShow\'][\'paper\'] === true) echo \'true\';
                            else echo \'false\'; ?>', 'true', $str);
    $str = str_replace('<?php if ($data[\'garbageToShow\'][\'glass\'] === true) echo \'true\';
                            else echo \'false\'; ?>', 'true', $str);
    $str = str_replace('<?php if ($data[\'garbageToShow\'][\'metal\'] === true) echo \'true\';
                            else echo \'false\'; ?>', 'true', $str);*/
    $header = $doc->createElement('h1');
    $header->setAttribute('style', 'display:flex; justify-content:center; align-items:center; background-color: #cacbc8; margin-top:0em; font-size:3em; padding-bottom:1em; padding-top: 0.5em;');
    if ($data['timeFilter'] === "AllTime" || $data['timeFilter'] == '') {
        $data['timeFilter'] = "All Time";
    }
    if ($data['timeFilter'] === "LastMonth") {
        $data['timeFilter'] = "Last Month";
    }
    if ($data['timeFilter'] === "LastWeek") {
        $data['timeFilter'] = "Last Week";
    }
    if ($data['city'] != 'none') {
        $nodText2 = $doc->createTextNode($data['timeFilter'] . ' Report ' . $data['city']);
    } else {
        $nodText2 = $doc->createTextNode($data['timeFilter'] . ' Report');
    }
    $header->appendChild($nodText2);
    $divNode = $doc->createElement('div');
    $divNode->setAttribute('style', 'display: flex; flex-direction:column; justify-content:center; align-items:center;');
    $bodyContent = $doc->getElementsByTagName('body');
    $firstScript = $doc->createElement('script');
    $firstScript->setAttribute('src', 'https://canvasjs.com/assets/script/canvasjs.min.js');
    $secondScript = $doc->createElement('script');
    $nodText = $doc->createTextNode($str);
    $secondScript->appendChild($nodText);
    $list = $doc->createElement('div');
    $hdr = $doc->createElement('h2');
    $txt = $doc->createTextNode('Country Level');
    $hdr->appendChild($txt);
    $contor = 1;
    if ($markersByCounty != null) {
        foreach ($markersByCounty as $county) {
            $listEl = $doc->createElement('div');
            if ($contor <= 5) {
                $listEl->setAttribute('style', 'background-color: #0ed145;');
            } else if ($contor > count($markersByCounty) - 5) {
                $listEl->setAttribute('style', 'background-color: #e03131;');
            }
            $contor++;
            $txt = $doc->createTextNode($county['county'] . ' : ' . $county['quantity']);
            $listEl->appendChild($txt);
            $list->appendChild($listEl);
        }
    }
    foreach ($bodyContent as $body) {
        $body->setAttribute('style', 'background-color: #e7e7e7;');
        $body->appendChild($header);
        $body->appendChild($divNode);
        $body->appendChild($firstScript);
        $body->appendChild($secondScript);
    }
    $childDivNode1 = $doc->createElement('div');
    $childDivNode1->setAttribute('style', 'width: 1500px; border: 5px solid black; height: 400px; margin-bottom: 3em;');
    $childDivNode1->setAttribute('id', 'chartContainer');
    $divNode->appendChild($childDivNode1);
    $childDivNode2 = $doc->createElement('div');
    $childDivNode2->setAttribute('style', 'width: 650px; border: 5px solid black; height: 300px;');
    $childDivNode2->setAttribute('id', 'pieChart');
    $childDivNode3 = $doc->createElement('div');
    $childDivNode3->setAttribute('style', 'width: 1500px; border: 5px solid black; height: 400px; margin-bottom: 3em;');
    $childDivNode3->setAttribute('id', 'barChart');
    $divNode->appendChild($childDivNode3);
    $divNode->appendChild($childDivNode2);
    $list->setAttribute('style', 'display: flex; justify-content:center; flex-direction:column; font: bold;');
    $divCountry = $doc->createElement('div');
    $divCountry->setAttribute('style', 'width: 25%; text-align: center; border: 2px solid black; background-color: white; margin-top:2em; font-family: sens-serif;');
    $hdr->setAttribute('style', 'background-color: #cacbc8; padding-top: 1em; padding-bottom: 1em; margin-bottom: 0em; margin-top: 0em; font-size: 1.5em;');
    $divCountry->appendChild($hdr);
    $divCountry->appendChild($list);
    $hdr2 = $doc->createElement('h2');
    $hdr2->setAttribute('style', 'background-color: #cacbc8; padding-top: 1em; padding-bottom: 1em; margin-bottom: 0em; margin-top: 0em; font-size: 1.5em;');
    $txt = $doc->createTextNode('County Level');
    $hdr2->appendChild($txt);
    $divRegion = $doc->createElement('div');
    $divRegion->setAttribute('style', 'width: 25%; text-align: center; border: 2px solid black; background-color: white; margin-top:2em; font-family: sens-serif;');
    $list2 = $doc->createElement('div');
    $divRegion->appendChild($hdr2);
    $divRegion->appendChild($list2);
    $contor = 1;
    if ($markersByRegion != null) {
        foreach ($markersByRegion as $region) {
            $listEl = $doc->createElement('div');
            if ($contor <= 5) {
                $listEl->setAttribute('style', 'background-color: #0ed145;');
            } else if ($contor > count($markersByRegion) - 5) {
                $listEl->setAttribute('style', 'background-color: #e03131;');
            }
            $contor++;
            $txt = $doc->createTextNode($region['city'] . ' : ' . $region['quantity']);
            $listEl->appendChild($txt);
            $list2->appendChild($listEl);
        }
    }
    $divCountryAndRegion = $doc->createElement('div');
    $divCountryAndRegion->setAttribute('style', 'display: flex; justify-content: space-around;');
    $divCountryAndRegion->appendChild($divCountry);
    $divCountryAndRegion->appendChild($divRegion);
    $body->appendChild($divCountryAndRegion);
    $htmlAsString = $doc->saveHTML();
    echo $htmlAsString;
    //file_put_contents('report.html', $htmlAsString);
}
