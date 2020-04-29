var dateArrayPlastic = <?php echo $pls; ?>;

var quantitiesPlastic = <?php echo $plsQuantity; ?>;

var dpsPlastic = [];
for (var i = 0; i < dateArrayPlastic.length; i++) {
    dpsPlastic.push({
        x: new Date(dateArrayPlastic[i]["time"]),
        y: dateArrayPlastic[i]["quantity"]
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

var dateArrayPaper = <?php echo $pap; ?>;

var quantitiesPaper = <?php echo $papQuantity; ?>;

var dpsPaper = [];
for (var i = 0; i < dateArrayPaper.length; i++) {
    dpsPaper.push({
        x: new Date(dateArrayPaper[i]["time"]),
        y: dateArrayPaper[i]["quantity"]
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

var dateArrayGlass = <?php echo $gls; ?>;

var quantitiesGlass = <?php echo $glsQuantity; ?>;

var dpsGlass = [];
for (var i = 0; i < dateArrayGlass.length; i++) {
    dpsGlass.push({
        x: new Date(dateArrayGlass[i]["time"]),
        y: dateArrayGlass[i]["quantity"]
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

var dateArrayMetal = <?php echo $mtl; ?>;



var quantitiesMetal = <?php echo $mtlQuantity; ?>;
var dpsMetal = [];
for (var i = 0; i < dateArrayMetal.length; i++) {
    dpsMetal.push({
        x: new Date(dateArrayMetal[i]["time"]),
        y: dateArrayMetal[i]["quantity"]
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
            visible: true,
            type: "line",
            dataPoints: dpsPlastic
        },
        {
            showInLegend: true,
            name: "series2",
            legendText: "Paper",
            visible: true,
            type: "line",
            dataPoints: dpsPaper
        },
        {
            showInLegend: true,
            name: "series3",
            legendText: "Glass",
            visible: true,
            type: "line",
            dataPoints: dpsGlass
        },
        {
            showInLegend: true,
            name: "series4",
            legendText: "Metal",
            visible: true,
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
                y: quantitiesPlastic,
                indexLabel: "Plastic"
            },
            {
                y: quantitiesPaper,
                indexLabel: "Paper"
            },
            {
                y: quantitiesGlass,
                indexLabel: "Glass"
            },
            {
                y: quantitiesMetal,
                indexLabel: "Metal"
            }
        ]
    }]
}); 

chart2.render();

var plasticToBar = dpsPlastic;
            var paperToBar = dpsPaper;
            var glassToBar = dpsGlass;
            var metalToBar = dpsMetal;
            plasticToBar['label'] = plasticToBar['x'];
            delete plasticToBar['x'];
            paperToBar['label'] = paperToBar['x'];
            delete paperToBar['x'];
            glassToBar['label'] = glassToBar['x'];
            delete glassToBar['x'];
            metalToBar['label'] = metalToBar['x'];
            delete metalToBar['x'];

var barChart = new CanvasJS.Chart("barChart", {
                animationEnabled: true,
                zoomEnabled: true,
                title: {
                    text: "Garbage Distribution"
                },
                axisY: {
                    title: "Reports",
                    titleFontColor: "#4F81BC",
                    lineColor: "#4F81BC",
                    labelFontColor: "#4F81BC",
                    tickColor: "#4F81BC"
                },
                toolTip: {
                    shared: true
                },
                legend: {
                    cursor: "pointer"
                },
                data: [{
                        type: "column",
                        name: "Plastic",
                        legendText: "Plastic",
                        visible: true,
                        showInLegend: true,
                        dataPoints: plasticToBar
                    },
                    {
                        type: "column",
                        name: "Paper",
                        legendText: "Paper",
                        visible: true,
                        axisYType: "secondary",
                        showInLegend: true,
                        dataPoints: paperToBar
                    },
                    {
                        type: "column",
                        name: "Glass",
                        legendText: "Glass",
                        visible: true,
                        axisYType: "secondary",
                        showInLegend: true,
                        dataPoints: glassToBar
                    },
                    {
                        type: "column",
                        name: "Metal",
                        legendText: "Metal",
                        visible: true,
                        axisYType: "secondary",
                        showInLegend: true,
                        dataPoints: metalToBar
                    }
                ]
            });
            barChart.render();