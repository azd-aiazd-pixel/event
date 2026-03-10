document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.AdminDashboardConfig === 'undefined') {
        console.error('AdminDashboardConfig is not defined. Make sure it is defined in the blade view.');
        return;
    }

    const config = window.AdminDashboardConfig;

    flatpickr("#datePicker", {
        mode: "range",
        dateFormat: "Y-m-d",
        locale: "fr",
        defaultDate: config.dateRange || [config.startDate, config.endDate]
    });

    const chartLabels = config.chartLabels;
    const dataTopUp = config.dataTopUp;
    const dataPayment = config.dataPayment;

    const topEventNames = config.topEventNames;
    const topEventRevenues = config.topEventRevenues;

    const totalPayment = config.totalPayment;
    const totalRefund = config.totalRefund;
    const dormant = config.dormant;

    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#94a3b8';

    // --- GRAPHIQUE 1 : Flux Entrants vs Sortants ---
    const fluxChartEl = document.getElementById('fluxChart');
    if (fluxChartEl) {
        new Chart(fluxChartEl.getContext('2d'), {
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
                    x: { border: { display: false }, grid: { display: false }, ticks: { maxTicksLimit: 12 } }
                }
            }
        });
    }

    // --- GRAPHIQUE 2 : Camembert ---
    const ratioChartEl = document.getElementById('ratioChart');
    if (ratioChartEl) {
        new Chart(ratioChartEl, {
            type: 'doughnut',
            data: {
                labels: ['Dépensé (CA)', 'Remboursé', 'Dormant (Reste)'],
                datasets: [{
                    data: [totalPayment, totalRefund, dormant],
                    backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15, font: { size: 10, weight: 'bold' } } }
                }
            }
        });
    }

    // --- GRAPHIQUE 3 : Palmarès des Événements ---
    const topEventsChartEl = document.getElementById('topEventsChart');
    if (topEventsChartEl && topEventNames && topEventNames.length > 0) {
        new Chart(topEventsChartEl, {
            type: 'bar',
            data: {
                labels: topEventNames,
                datasets: [{
                    label: 'Chiffre d\'Affaires (PTS)',
                    data: topEventRevenues,
                    backgroundColor: '#0f172a',
                    borderRadius: 6,
                    barPercentage: 0.5
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, border: { display: false } },
                    y: { border: { display: false }, grid: { display: false } }
                }
            }
        });
    }
});
