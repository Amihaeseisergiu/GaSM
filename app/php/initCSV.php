<?php
if (isset($_GET['downloadCSV'])) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://localhost:80/proiect/GaSM/app/api/markers/read/getCSV.php?filter=" . $data['timeFilter'] . '&country=' . $_SESSION['country'] . '&city=' . $_SESSION['city'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache"
        ),
    ));
    $CSVString = curl_exec($curl);
    $CSVString = json_decode($CSVString, true);
    $err = curl_error($curl);

    curl_close($curl);
    file_put_contents(__DIR__ . '../../report.csv', $CSVString);
    $file = __DIR__ . '../../report.csv';
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
