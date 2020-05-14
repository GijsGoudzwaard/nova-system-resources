import 'chartjs-plugin-streaming';
import axios from 'axios';
import * as moment from 'moment';

export default function lineChart(ctx, element) {
    let chart = element.getContext("2d");
    let resources = buildData(ctx.card.usage);

    moment.locale(ctx.card.locale);

    chart = new Chart(chart, {
        type: 'line',
        data: {
            datasets: [{
                label: ctx.$root.__('usage'),
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
                        axios.get('/nova-vendor/systemResources/' + ctx.resource).then(function (result) {
                            chart.data.datasets.forEach(function (dataset) {
                                dataset.data.push({
                                    x: moment(),
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

    axios.get('/nova-vendor/systemResources/' + ctx.resource).then(function (result) {
        chart.data.datasets.forEach(function (dataset) {
            if (ctx.card.usage === null) {
                dataset.data.push({
                    x: moment().subtract(10, 'seconds'),
                    y: Math.round(result.data)
                });
                dataset.data.push({
                    x: moment().subtract(5, 'seconds'),
                    y: Math.round(result.data)
                });
            }
            dataset.data.push({
                x: moment(),
                y: Math.round(result.data)
            });
        });

        chart.update();
    });

    return chart;
}

function buildData(usage) {
    let resources = [];

    for (let i in usage) {
        resources.push({
            x: moment().subtract((usage.length * 5) - (i * 5), 'seconds'),
            y: usage[i]
        });
    }

    return resources;
}
