<?php
if ($data['timeFilter'] != 'Last Month' && $data['timeFilter'] != 'Last Week' && $data['timeFilter'] != 'Today') {
    $timeFlt = 'All Time';
}
if ($data['timeFilter'] == 'LastMonth') {
    $timeFlt = 'Last Month';
}
if ($data['timeFilter'] == 'LastWeek') {
    $timeFlt = 'Last Week';
}
if ($_SESSION['city'] != 'none') {
    $timeFlt = $timeFlt . ' Report ' . $_SESSION['city'];
} else {
    $timeFlt = $timeFlt . ' Report';
}

$cleanestCounties = 'Cleanest Counties: \r\n\r\n';
$dirtiestCounties = 'Dirtiest Counties: \r\n\r\n';
$contor = 1;
if ($data['markersByCounty'] != null) {
    foreach ($data['markersByCounty'] as $county) {
        if ($contor <= 5) {
            $cleanestCounties = $cleanestCounties . $county['county'] . ' : ' . $county['quantity'] . '\r\n';
        } else if ($contor > count($data['markersByCounty']) - 5) {
            $dirtiestCounties = $dirtiestCounties . $county['county'] . ' : ' . $county['quantity'] . '\r\n';
        }
        $contor++;
    }
}

$cleanestRegions = 'Cleanest Cities: \r\n\r\n';
$dirtiestRegions = 'Dirtiest Cities: \r\n\r\n';
$contor = 1;
if ($data['markersByRegion'] != null) {
    foreach ($data['markersByRegion'] as $region) {
        if ($contor <= 5) {
            $cleanestRegions = $cleanestRegions . $region['city'] . ' : ' . $region['quantity'] . '\r\n';
        } else if ($contor > count($data['markersByRegion']) - 5) {
            $dirtiestRegions = $dirtiestRegions . $region['city'] . ' : ' . $region['quantity'] . '\r\n';
        }
        $contor++;
    }
}

?>
var flt = '<?php echo $timeFlt; ?>';
var cleanestCounties= '<?php echo $cleanestCounties ?>';
var dirtiestCounties= '<?php echo $dirtiestCounties ?>';
var cleanestRegions= '<?php echo $cleanestRegions ?>';
var dirtiestRegions= '<?php echo $dirtiestRegions ?>';

document.getElementById("secondButton").addEventListener("click", myFunction);
function myFunction() {
var pdf = new jsPDF('p', 'mm', [360, 400]);
var canvas1 = document.querySelector("#chartContainer .canvasjs-chart-canvas");
var canvas2 = document.querySelector("#pieChart .canvasjs-chart-canvas");
var dataURL1 = canvas1.toDataURL('image1/JPEG', 1);
var dataURL2 = canvas2.toDataURL('image2/JPEG', 1);
pdf.text(flt, 150, 10);
pdf.line(0, 15, 400, 15);
pdf.addImage(dataURL1, 'JPEG', 0, 25);
pdf.line(0, 135, 400, 135);
pdf.addImage(dataURL2, 'JPEG', 110, 150);
pdf.line(0, 245, 400, 245);
pdf.text(cleanestCounties, 15, 255);
pdf.text(dirtiestCounties, 290, 255);
pdf.line(0, 300, 400, 300);
pdf.text(cleanestRegions, 15, 310);
pdf.text(dirtiestRegions, 290, 310);
pdf.save("download.pdf");
}