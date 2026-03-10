document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.ParticipantDashboardConfig === 'undefined') {
        console.error('ParticipantDashboardConfig is not defined.');
        return;
    }

    const config = window.ParticipantDashboardConfig;

    // Initialisation du calendrier Flatpickr en mode "plage de dates"
    flatpickr("#datePicker", {
        mode: "range",
        dateFormat: "Y-m-d",
        locale: "fr",
        defaultDate: config.dateRange || 'today'
    });

    // Variables pour les graphiques
    const chartLabels = config.chartLabels || [];
    const chartData = config.chartData || [];
    const topNames = config.topProductsNames || [];
    const topQuantities = config.topProductsQuantities || [];

    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.scale.grid.color = '#f8fafc';

    // 1. Graphique Courbe (Dépenses)
    const salesChartCanvas = document.getElementById('salesChart');
    if (salesChartCanvas) {
        const ctxSales = salesChartCanvas.getContext('2d');
        let gradient = ctxSales.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(15, 23, 42, 0.15)'); // slate-900 (Noir chic)
        gradient.addColorStop(1, 'rgba(15, 23, 42, 0)');

        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Dépenses (PTS)',
                    data: chartData,
                    borderColor: '#0f172a', // slate-900
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
    const topProductsChartCanvas = document.getElementById('topProductsChart');
    if (topProductsChartCanvas && topNames.length > 0) {
        new Chart(topProductsChartCanvas, {
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
});
