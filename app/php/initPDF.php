<?php
    if($data['timeFilter'] == 'AllTime') {
        $data['timeFilter'] = 'All Time';
    }
        if ($_SESSION['city'] != 'none') {
            $timeFlt = $data['timeFilter'] . ' Report ' . $_SESSION['city'];
        } else {
        $timeFlt = $data['timeFilter'] . ' Report';
        }?>
        var flt = '<?php echo $timeFlt; ?>';
        document.getElementById("secondButton").addEventListener("click", myFunction);
        function myFunction() {
            var pdf = new jsPDF('p', 'mm', [360, 400]);
            var canvas1 = document.querySelector("#chartContainer .canvasjs-chart-canvas");
            var canvas2 = document.querySelector("#pieChart .canvasjs-chart-canvas");
            var dataURL1 = canvas1.toDataURL('image1/JPEG', 1);
            var dataURL2 = canvas2.toDataURL('image2/JPEG', 1);
            pdf.text(flt, 160, 5);
            pdf.addImage(dataURL1, 'JPEG', 0, 20);
            pdf.line(0, 130, 400, 130);
            pdf.addImage(dataURL2, 'JPEG', 110, 145);
            pdf.save("download.pdf");
        }
