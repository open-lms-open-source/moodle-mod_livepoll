// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Live poll Chart controller module.
 *
 * @package mod_livepoll
 * @copyright Copyright (c) 2018 Open LMS
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/* jshint esversion: 6 */

class ChartCtrl{
    constructor(chartTyp) {
        switch (chartTyp){
            case 'barchart' :
                this.chartType = 'bar';
                break;
            case 'piechart' :
                this.chartType = 'pie';
                break;
            case 'doughnutchart' :
                this.chartType = 'doughnut';
                break;
            case 'polarareachart':
                this.chartType = 'polarArea';
                break;
            case 'radarchart':
                this.chartType = 'radar';
                break;
            default:
                this.chartType = 'bar';
                break;
        }
        this.chart = null;
    }

    initChart(optionList){
        var ctx = document.getElementById("livepoll-chart").getContext("2d");

        var labels = [], votes = [];
        optionList.forEach((item) => {
            labels.push(item.title);
            votes.push(0);
        });

        // we use ChartJS embedded with moodle mobile app :-)
        this.chart = new window.Chart(ctx, {
            type: this.chartType,
            data: {
                labels: labels,
                datasets: [{
                    label: "Votes",
                    data: votes,
                    backgroundColor: [
                        "#ff6384",
                        "#36a2eb",
                        "#cc65fe",
                        "#ffce56",
                    ],
                }]
            },
            options: this.getChartOptions()
        });
    }

    performUpdate(optionList) {
        if (!this.chart) {
            this.initChart(optionList);
        }

        this.chart.data.datasets[0].data = [];

        optionList.forEach((item) => {
            this.chart.data.datasets[0].data.push(item.voteCnt);
        });

        this.chart.update();
    }

    getChartOptions() {
        switch (this.chartType) {
            case 'bar':
                return {
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            gridLines: {
                                color: 'rgba(255, 255, 255, 0.25)',
                                zeroLineColor: 'rgba(255, 255, 255, 0.25)',
                            }
                        }],
                        yAxes: [{
                            display: true,
                            gridLines: {
                                color: 'rgba(255, 255, 255, 0.25)',
                            },
                            scaleLabel: {
                                display: false,
                                fontColor: 'rgba(255, 255, 255, 0.25)'
                            },
                            ticks: {
                                display: false,
                            },
                        }]
                    },
                    animation: {
                        onComplete: function () {
                            var chartInstance = this.chart,
                                ctx = chartInstance.ctx;
                            ctx.font = window.Chart.helpers.fontString('18', 'bold', window.Chart.defaults.global.defaultFontFamily);
                            ctx.fillStyle = 'rgb(0, 0, 0)';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';

                            this.data.datasets.forEach(function (dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function (bar, index) {
                                    var data = dataset.data[index];
                                    if (data != 0) {
                                        ctx.fillText(data, bar._model.x, bar._model.y + 25);
                                    }
                                });
                            });
                        }
                    }
                };
            case 'pie':
            case 'doughnut':
            case 'polarArea':
            case 'radar':
                return {
                    ...this.chartType === 'polarArea' ? {
                        scale :{
                            ticks: {
                                display:false
                            }
                        }
                    } : {},
                    legend: {
                        position: 'right',
                    },
                    animation: {
                        onComplete: function () {
                            const ctx = this.chart.ctx;
                            ctx.font = window.Chart.helpers.fontString(
                                '18', 'bold',
                                window.Chart.defaults.global.defaultFontFamily);
                            ctx.fillStyle = 'rgb(0, 0, 0)';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'center';
                            const dataset = this.data.datasets[0]; // we have only one dataset
                            var gradeCnt = 1;
                            for (let idx = 0; idx < dataset.data.length; idx++) {
                                const model = dataset._meta[Object.keys(dataset._meta)[0]].data[idx]._model,
                                    //total = dataset._meta[Object.keys(dataset._meta)[0]].total,
                                    mid_radius = model.innerRadius + (model.outerRadius - model.innerRadius) / 1.5,
                                    start_angle = model.startAngle,
                                    end_angle = model.endAngle,
                                    mid_angle = start_angle + (end_angle - start_angle) / 2;

                                const x = mid_radius * Math.cos(mid_angle);
                                const y = mid_radius * Math.sin(mid_angle);

                                ctx.fillStyle = 'black';

                                if (dataset.data[idx] !== 0) {
                                    ctx.font = 'bold 18px Arial';
                                    var value = dataset.data[idx];
                                    ctx.fillText(value, model.x + x, model.y + y);
                                }
                                gradeCnt++;
                            }
                        },
                    },
                };
        }
    }
}