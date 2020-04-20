    var dateArrayPlastic = [<?php echo $pls; ?>];

    var quantitiesPlastic = [<?php echo $plsQuantity; ?>];

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

    var dateArrayPaper = [<?php echo $pap; ?>];

    var quantitiesPaper = [<?php echo $papQuantity; ?>];

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

    var dateArrayGlass = [<?php echo $gls; ?>];

    var quantitiesGlass = [<?php echo $glsQuantity; ?>];

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

    var dateArrayMetal = [<?php echo $mtl; ?>];



    var quantitiesMetal = [<?php echo $mtlQuantity; ?>];
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
        fileName: "LineChart",
        title: {
            text: "Garbage distribution"
        },
        axisX:{
            tickColor: "red",
            tickLength: 5,
            tickThickness: 2
        },
        axisY:{
        tickLength: 15,
        tickColor: "DarkSlateBlue" ,
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
            fileName: "PieChart",
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