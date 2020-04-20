<?php
if (isset($_GET['downloadHTML'])) {
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
    $str = file_get_contents('../app/php/charts.php');
    $str = str_replace('<?php echo $pls; ?>', $pls, $str);
    $str = str_replace('<?php echo $pap; ?>', $pap, $str);
    $str = str_replace('<?php echo $gls; ?>', $gls, $str);
    $str = str_replace('<?php echo $mtl; ?>', $mtl, $str);
    $str = str_replace('<?php echo $plsQuantity; ?>', $plsQuantity, $str);
    $str = str_replace('<?php echo $papQuantity; ?>', $papQuantity, $str);
    $str = str_replace('<?php echo $glsQuantity; ?>', $glsQuantity, $str);
    $str = str_replace('<?php echo $mtlQuantity; ?>', $mtlQuantity, $str);
    $str = str_replace('<?php if ($data[\'garbageToShow\'][\'plastic\'] === true) echo \'true\';
                            else echo \'false\'; ?>', 'true', $str);
    $str = str_replace('<?php if ($data[\'garbageToShow\'][\'paper\'] === true) echo \'true\';
                            else echo \'false\'; ?>', 'true', $str);
    $str = str_replace('<?php if ($data[\'garbageToShow\'][\'glass\'] === true) echo \'true\';
                            else echo \'false\'; ?>', 'true', $str);
    $str = str_replace('<?php if ($data[\'garbageToShow\'][\'metal\'] === true) echo \'true\';
                            else echo \'false\'; ?>', 'true', $str);
    $header = $doc->createElement('h1');
    $header->setAttribute('style', 'display:flex; justify-content:center; align-items:center; background-color: #cacbc8; margin-top:0em; font-size:3em; padding-bottom:1em; padding-top: 0.5em;');
    if ($data['timeFilter'] === "AllTime") {
        $data['timeFilter'] = "All Time";
    }
    if ($_SESSION['city'] != 'none') {
        $nodText2 = $doc->createTextNode($data['timeFilter'] . ' Report ' . $_SESSION['city']);
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
    foreach ($data['markersByCounty'] as $county) {
        $listEl = $doc->createElement('div');
        if ($contor <= 5) {
            $listEl->setAttribute('style', 'background-color: #0ed145;');
        } else if ($contor > count($data['markersByCounty']) - 5) {
            $listEl->setAttribute('style', 'background-color: #e03131;');
        }
        $contor++;
        $txt = $doc->createTextNode($county['county'] . ' : ' . $county['quantity']);
        $listEl->appendChild($txt);
        $list->appendChild($listEl);
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
    $divNode->appendChild($childDivNode2);
    $list->setAttribute('style', 'display: flex; justify-content:center; flex-direction:column; font: bold;');
    $divCountry = $doc->createElement('div');
    $divCountry->setAttribute('style', 'width: 25%; text-align: center; border: 2px solid black; background-color: white; margin-top:2em; font-family: sens-serif;');
    $hdr->setAttribute('style', 'background-color: #cacbc8; padding-top: 1em; padding-bottom: 1em; margin-bottom: 0em; margin-top: 0em; font-size: 1.5em; height: 10%;');
    $divCountry->appendChild($hdr);
    $divCountry->appendChild($list);
    $body->appendChild($divCountry);
    $htmlAsString = $doc->saveHTML();
    file_put_contents(__DIR__ . '../../report.html', $htmlAsString);
    $file = __DIR__ . '../../report.html';
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}
?>