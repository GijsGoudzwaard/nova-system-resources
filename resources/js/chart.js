import 'chartjs-plugin-streaming';
import axios from 'axios';

export default function lineChart(usage, resource, element) {
    let chart = element.getContext("2d");

    let resources = buildData(usage);

    chart = new Chart(chart, {
        type: 'line',
        data: {
            datasets: [{
                label: 'Usage',
                data: resources,
                borderColor: '#4099de',
                backgroundColor: '#fff',
                fill: false,
                borderWidth: 2,
                pointHitRadius: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: false,
            scales: {
                xAxes: [{
                    type: 'realtime',
                    gridLines: {
                        display: false
                    }
                }],
                yAxes: [{
                    display: false,
                    ticks: {
                        min: 0,
                        max: 100,
                        stepSize: 10
                    },
                    gridLines: {
                        display: false
                    }
                }]
            },
            plugins: {
                streaming: {
                    duration: 50000,
                    delay: 10000,
                    refresh: 5000,
                    onRefresh: function (chart) {
                        axios.get('/nova-vendor/systemResources/' + resource).then(function (result) {
                            chart.data.datasets.forEach(function (dataset) {
                                dataset.data.push({
                                    x: Date.now(),
                                    y: Math.round(result.data)
                                });
                            });

                            chart.update();
                        });
                    }
                }
            }
        }
    });

    axios.get('/nova-vendor/systemResources/' + resource).then(function (result) {
        chart.data.datasets.forEach(function (dataset) {
            if (usage === null) {
                dataset.data.push({
                    x: Date.now() - (10000),
                    y: Math.round(result.data)
                });
                dataset.data.push({
                    x: Date.now() - (5000),
                    y: Math.round(result.data)
                });
            }
            dataset.data.push({
                x: Date.now(),
                y: Math.round(result.data)
            });
        });

        chart.update();
    });
}

function buildData(usage) {
    let resources = [];

    for (let i in usage) {
        resources.push({
            x: Date.now() - ((usage.length * 5000) - (i * 5000)),
            y: usage[i]
        });
    }

    return resources;
}
