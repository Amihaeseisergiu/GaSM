<?php
if (isset($_GET['downloadCSV'])) {
    file_put_contents(__DIR__ . '../../report.csv', $data['CSVString']);
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
?>