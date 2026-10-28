import Chart from 'chart.js/auto';

const canvas = document.getElementById('stock-movements-chart');

if (canvas instanceof HTMLCanvasElement && window.dashboardSeries) {
    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: window.dashboardSeries.labels,
            datasets: [
                {
                    label: 'Entradas',
                    data: window.dashboardSeries.entries,
                    backgroundColor: '#198754',
                    borderRadius: 4,
                },
                {
                    label: 'Salidas',
                    data: window.dashboardSeries.exits,
                    backgroundColor: '#6c757d',
                    borderRadius: 4,
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                    },
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                    },
                },
            },
        },
    });
}
