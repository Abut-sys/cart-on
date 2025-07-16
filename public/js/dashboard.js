let ordersChart = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS animations
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true
    });

    initChart();
    setupFilters();

    // Handle Turbolinks/Livewire if used
    document.addEventListener('turbolinks:load', function() {
        initChart();
        setupFilters();
    });
});

function initChart() {
    const ctx = document.getElementById('weekdayOrdersChart');
    if (!ctx) return;

    if (ordersChart) {
        ordersChart.destroy();
    }

    const weekdayOrders = JSON.parse(document.getElementById('weekdayOrdersData').value);
    const dayLabels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    let orderData = Array(7).fill(0);
    weekdayOrders.forEach(order => {
        orderData[order.day_of_week] = order.count;
    });

    // Create gradient
    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 350);
    gradient.addColorStop(0, 'rgba(102, 126, 234, 0.8)');
    gradient.addColorStop(1, 'rgba(118, 75, 162, 0.1)');

    ordersChart = new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels: dayLabels,
            datasets: [{
                label: 'Orders',
                data: orderData,
                backgroundColor: gradient,
                borderColor: '#667eea',
                borderWidth: 3,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointHitRadius: 15,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#667eea',
                    borderWidth: 1,
                    cornerRadius: 12,
                    displayColors: false,
                    callbacks: {
                        title: function(context) {
                            return dayLabels[context[0].dataIndex];
                        },
                        label: function(context) {
                            return `${context.parsed.y} orders`;
                        }
                    }
                },
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 12
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
}

function setupFilters() {
    const yearFilter = document.getElementById('yearFilter');
    const monthFilter = document.getElementById('monthFilter');

    if (!yearFilter || !monthFilter) return;

    yearFilter.removeEventListener('change', handleFilterChange);
    monthFilter.removeEventListener('change', handleFilterChange);

    yearFilter.addEventListener('change', handleFilterChange);
    monthFilter.addEventListener('change', handleFilterChange);
}

let filterDebounce;

function handleFilterChange() {
    clearTimeout(filterDebounce);

    filterDebounce = setTimeout(() => {
        const year = document.getElementById('yearFilter').value;
        const month = document.getElementById('monthFilter').value;

        // Show loading state with modern spinner
        const chartContainer = document.querySelector('.chart-body');
        if (chartContainer) {
            chartContainer.innerHTML = `
                <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100%; color: #667eea;">
                    <div style="width: 40px; height: 40px; border: 3px solid #e5e7eb; border-top: 3px solid #667eea; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 1rem;"></div>
                    <p style="margin: 0; font-weight: 500;">Updating chart...</p>
                </div>
                <style>
                    @keyframes spin {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }
                </style>
            `;
        }

        window.location.href = `/dashboard?year=${year}&month=${month}`;
    }, 500);
}
