document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.DashboardConfig === 'undefined') {
        console.error('DashboardConfig is not defined. Make sure it is defined in the blade view.');
        return;
    }

    const config = window.DashboardConfig;

    // Initialisation Flatpickr
    flatpickr("#datePicker", {
        mode: "range",
        dateFormat: "Y-m-d",
        locale: "fr",
        defaultDate: config.dateRange || [config.eventStartDate, config.eventEndDate]
    });

    // Variables injectées par Laravel
    const chartLabels = config.chartLabels;
    const dataTopUp = config.dataTopUp;
    const dataPayment = config.dataPayment;

    const topStoresNames = config.topStoresNames;
    const topStoresRevenues = config.topStoresRevenues;

    // Config globale Chart.js
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.scale.grid.color = '#f8fafc';

    // --- 1. Graphique Courbe (Flux Financiers) ---
    const salesCanvas = document.getElementById('globalSalesChart');
    if (salesCanvas) {
        const ctxSales = salesCanvas.getContext('2d');

        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [
                    {
                        label: 'Rechargements (TopUp)',
                        data: dataTopUp,
                        borderColor: '#3b82f6', // blue-500
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#3b82f6',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Dépenses (CA)',
                        data: dataPayment,
                        borderColor: '#10b981', // emerald-500
                        backgroundColor: 'transparent',
                        borderWidth: 3,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#10b981',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top', labels: { usePointStyle: true, font: { weight: 'bold' } } } },
                scales: {
                    y: { beginAtZero: true, border: { display: false } },
                    x: { border: { display: false }, grid: { display: false } }
                }
            }
        });
    }

    // --- 2. Graphique Barres Horizontales (Top 5 Boutiques) ---
    if (topStoresNames && topStoresNames.length > 0) {
        const storesCanvas = document.getElementById('topStoresChart');
        if (storesCanvas) {
            const ctxStores = storesCanvas.getContext('2d');

            new Chart(ctxStores, {
                type: 'bar',
                data: {
                    labels: topStoresNames,
                    datasets: [{
                        label: 'Chiffre d\'Affaires (PTS)',
                        data: topStoresRevenues,
                        backgroundColor: '#0f172a',
                        borderRadius: 8,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 12,
                            titleFont: { size: 13, weight: 'bold' },
                            bodyFont: { size: 14, weight: 'bold' },
                            callbacks: {
                                label: function (context) { return context.parsed.x + ' PTS'; }
                            }
                        }
                    },
                    scales: {
                        x: { beginAtZero: true, border: { display: false } },
                        y: { border: { display: false }, grid: { display: false } }
                    }
                }
            });
        }
    }
});
