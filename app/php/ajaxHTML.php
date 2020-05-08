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
    CURLOPT_URL => "http://localhost:80/proiect/GaSM/public/api/markers/quantity" . '/' . $data['country'] . '?filter=' . $data['timeFilter'],
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
    CURLOPT_URL => "http://localhost:80/proiect/GaSM/public/api/markers/quantity" . '/' . $data['country'] . '/' . $data['county'] . '?filter=' . $data['timeFilter'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache"
    ),
));
//echo curl_exec($curl);
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
        <style type="text/css"></style>
    </head>
    <body>
        
    </body>
    </html>');
    $style = file_get_contents('../css/htmlCss.css');
    $doc->getElementsByTagName("style")->item(0)->nodeValue = $style;
    $str = file_get_contents(__DIR__ . '../initChart.php');
    $str = str_replace('<?php echo $pls; ?>', $data['plastic'], $str);
    $str = str_replace('<?php echo $pap; ?>', $data['paper'], $str);
    $str = str_replace('<?php echo $gls; ?>', $data['glass'], $str);
    $str = str_replace('<?php echo $mtl; ?>', $data['metal'], $str);
    $str = str_replace('<?php echo $plsQuantity; ?>', $data['allPlastic'], $str);
    $str = str_replace('<?php echo $papQuantity; ?>', $data['allPaper'], $str);
    $str = str_replace('<?php echo $glsQuantity; ?>', $data['allGlass'], $str);
    $str = str_replace('<?php echo $mtlQuantity; ?>', $data['allMetal'], $str);
    if($data['timeFilter'] == "Today") {
        $unit = "minute";
    } else {
        $unit = "day";
    }
    $str = str_replace('<?php echo $valUnit; ?>', $unit, $str);
    $header = $doc->createElement('h1');
    $header->setAttribute('id', 'header');
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
    $divNode->setAttribute('id', 'divNode');
    $bodyContent = $doc->getElementsByTagName('body');
    $firstScript = $doc->createElement('script');
    $firstScript->setAttribute('src', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js');
    $secondScript = $doc->createElement('script');
    $nodText = $doc->createTextNode($str);
    $secondScript->appendChild($nodText);
    $list = $doc->createElement('div');
    $hdr = $doc->createElement('h2');
    $txt = $doc->createTextNode('Country Level');
    $hdr->appendChild($txt);
    $contor = 1;
    $firstValue = $markersByCounty[0];
    if ($firstValue != 'No Markers Found') {
        foreach ($markersByCounty as $county) {
            $listEl = $doc->createElement('div');
            if ($contor <= 5) {
                $listEl->setAttribute('class', 'listElGreen');
            } else if ($contor > count($markersByCounty) - 5) {
                $listEl->setAttribute('class', 'listElRed');
            }
            $contor++;
            $txt = $doc->createTextNode($county['county'] . ' : ' . $county['quantity']);
            $listEl->appendChild($txt);
            $list->appendChild($listEl);
        }
    }
    foreach ($bodyContent as $body) {
        $body->setAttribute('id', 'body');
        $body->appendChild($firstScript);
        $body->appendChild($header);
        $body->appendChild($divNode);
        $body->appendChild($secondScript);
    }
    $childDivNode1 = $doc->createElement('div');
    $childDivNode1->setAttribute('id','childDivNode1');
    $childCanvasNode1 = $doc->createElement('canvas');
    $childCanvasNode1->setAttribute('class', 'childCanvasNode1');
    $childCanvasNode1->setAttribute('id', 'lineChart');
    $childDivNode1->appendChild($childCanvasNode1);
    $divNode->appendChild($childDivNode1);
    $childDivNode2 = $doc->createElement('div');
    $childDivNode2->setAttribute('id','childDivNode2');
    $childCanvasNode2 = $doc->createElement('canvas');
    $childCanvasNode2->setAttribute('class', 'childCanvasNode2');
    $childCanvasNode2->setAttribute('id', 'barChart');
    $childDivNode2->appendChild($childCanvasNode2);
    $divNode->appendChild($childDivNode2);
    $childDivNode3 = $doc->createElement('div');
    $childDivNode3->setAttribute('id','childDivNode3');
    $childCanvasNode3 = $doc->createElement('canvas');
    $childCanvasNode3->setAttribute('class', 'childCanvasNode3');
    $childCanvasNode3->setAttribute('id', 'pieChart');
    $childDivNode3->appendChild($childCanvasNode3);
    $divNode->appendChild($childDivNode3);
   // $divNode->appendChild($childDivNode2);
    $list->setAttribute('class', 'list');
    $divCountry = $doc->createElement('div');
    $divCountry->setAttribute('class', 'divCountryAndRegion');
    $hdr->setAttribute('class', 'headerList');
    $divCountry->appendChild($hdr);
    $divCountry->appendChild($list);
    $hdr2 = $doc->createElement('h2');
    $hdr2->setAttribute('class', 'headerList');
    $txt = $doc->createTextNode('County Level');
    $hdr2->appendChild($txt);
    $divRegion = $doc->createElement('div');
    $divRegion->setAttribute('class', 'divCountryAndRegion');
    $list2 = $doc->createElement('div');
    $divRegion->appendChild($hdr2);
    $divRegion->appendChild($list2);
    $contor = 1;
    $firstValue = $markersByRegion[0];
    if ($firstValue != 'No Markers Found') {
        foreach ($markersByRegion as $region) {
            $listEl = $doc->createElement('div');
            if ($contor <= 5) {
                $listEl->setAttribute('class', 'listElGreen');
            } else if ($contor > count($markersByRegion) - 5) {
                $listEl->setAttribute('class', 'listElRed');
            }
            $contor++;
            $txt = $doc->createTextNode($region['city'] . ' : ' . $region['quantity']);
            $listEl->appendChild($txt);
            $list2->appendChild($listEl);
        }
    }
    $divCountryAndRegion = $doc->createElement('div');
    $divCountryAndRegion->setAttribute('id', 'divCountryAndRegionContainer');
    $divCountryAndRegion->appendChild($divCountry);
    $divCountryAndRegion->appendChild($divRegion);
    $body->appendChild($divCountryAndRegion);
    $htmlAsString = $doc->saveHTML();
    echo $htmlAsString;
    //file_put_contents('report.html', $htmlAsString);
}
