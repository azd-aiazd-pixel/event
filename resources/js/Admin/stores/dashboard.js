document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.StoreDashboardConfig === 'undefined') {
        console.error('StoreDashboardConfig is not defined. Make sure it is defined in the blade view.');
        return;
    }

    const config = window.StoreDashboardConfig;

    flatpickr("#datePicker", {
        mode: "range",
        dateFormat: "Y-m-d",
        locale: "fr",
        defaultDate: config.dateRange || [config.eventStartDate, config.eventEndDate]
    });

    const chartLabels = config.chartLabels;
    const chartData = config.chartData;
    const topNames = config.topProductsNames;
    const topQuantities = config.topProductsQuantities;

    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.scale.grid.color = '#f8fafc';

    // 1. Graphique Courbe
    const salesCanvas = document.getElementById('salesChart');
    if (salesCanvas) {
        const ctxSales = salesCanvas.getContext('2d');
        let gradient = ctxSales.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(15, 23, 42, 0.15)');
        gradient.addColorStop(1, 'rgba(15, 23, 42, 0)');

        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Revenus (PTS)',
                    data: chartData,
                    borderColor: '#0f172a',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#0f172a',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, border: { display: false } },
                    x: { border: { display: false }, grid: { display: false } }
                }
            }
        });
    }

    // 2. Graphique Camembert
    if (topNames && topNames.length > 0) {
        const topProductsCanvas = document.getElementById('topProductsChart');
        if (topProductsCanvas) {
            new Chart(topProductsCanvas, {
                type: 'doughnut',
                data: {
                    labels: topNames,
                    datasets: [{
                        data: topQuantities,
                        backgroundColor: ['#0f172a', '#334155', '#64748b', '#94a3b8', '#cbd5e1'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, font: { size: 11, weight: 'bold' } } } }
                }
            });
        }
    }
});
