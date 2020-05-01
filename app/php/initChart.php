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

var unit = '<?php echo $valUnit; ?>';

var ctx = document.getElementById('lineChart').getContext('2d');
                var lineChart = new Chart(ctx, {
                    // The type of chart we want to create
                    type: 'line',
                    fill: false,
                    // The data for our dataset
                    data: {
                        datasets: [{
                                label: 'Plastic',
                                borderColor: 'red',
                                data: dpsPlastic
                            },
                            {
                                label: 'Paper',
                                borderColor: 'yellow',
                                data: dpsPaper
                            },
                            {
                                label: 'Glass',
                                borderColor: 'blue',
                                data: dpsGlass
                            },
                            {
                                label: 'Metal',
                                borderColor: 'green',
                                data: dpsMetal
                            }
                        ]
                    },

                    // Configuration options go here
                    options: {
                        maintainAspectRatio: false,
                        scales: {
                            xAxes: [{
                                type: 'time',
                                time: {
                                    unit: unit
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    stepSize: 1
                                }
                            }]
                        }
                    }
                });

var ctx2 = document.getElementById('barChart').getContext('2d');
                var barChart = new Chart(ctx2, {
                    // The type of chart we want to create
                    type: 'bar',
                    // The data for our dataset
                    data: {
                        datasets: [{
                                label: 'Plastic',
                                fill: false,
                                data: dpsPlastic,
                                backgroundColor: 'red',
                                borderColor: 'red',
                                borderWidth: 1
                            },
                            {
                                label: 'Paper',
                                fill: false,
                                data: dpsPaper,
                                backgroundColor: 'yellow',
                                borderColor: 'yellow',
                                borderWidth: 1
                            },
                            {
                                label: 'Glass',
                                fill: false,
                                data: dpsGlass,
                                backgroundColor: 'blue',
                                borderColor: 'blue',
                                borderWidth: 1
                            },
                            {
                                label: 'Metal',
                                fill: false,
                                data: dpsMetal,
                                backgroundColor: 'green',
                                borderColor: 'green',
                                borderWidth: 1
                            }
                        ]
                    },

                    // Configuration options go here
                    options: {
                        maintainAspectRatio: false,
                        scales: {
                            xAxes: [{
                                type: 'time',
                                time: {
                                    unit: unit
                                }
                            }]
                        },
                    }
                });

                var ctx3 = document.getElementById("pieChart");
                        pieChart = new Chart(ctx3, {
                            type: 'pie',
                            data: {
                                labels: ["Plastic", "Paper", "Glass", "Metal"],
                                datasets: [{
                                    label: "Reports",
                                    backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9"],
                                    data: [quantitiesPlastic, quantitiesPaper, quantitiesGlass, quantitiesMetal]
                                }]
                            },
                            options: {
                               // maintainAspectRatio: false
                            }
                        });
