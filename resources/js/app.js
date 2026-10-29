import 'flowbite';
import Chart from 'chart.js/auto';

Chart.defaults.font.family = "'Instrument Sans', ui-sans-serif, system-ui, sans-serif";
Chart.defaults.color = '#64748b';

const parseDataset = (canvas, key) => {
    try {
        return JSON.parse(canvas.dataset[key] ?? '[]');
    } catch {
        return [];
    }
};

const tooltipOptions = {
    backgroundColor: '#0f172a',
    padding: 10,
    cornerRadius: 8,
    titleFont: { size: 12, weight: '600' },
    bodyFont: { size: 12 },
};

const chartBuilders = {
    doughnut(canvas) {
        const labels = parseDataset(canvas, 'labels');
        const values = parseDataset(canvas, 'values');
        const colors = parseDataset(canvas, 'colors');

        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [
                    {
                        data: values,
                        backgroundColor: colors,
                        borderWidth: 0,
                        hoverOffset: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        ...tooltipOptions,
                        callbacks: {
                            label: (context) => ` ${context.label}: ${context.parsed}`,
                        },
                    },
                },
            },
        });
    },

    'horizontal-bar'(canvas) {
        const labels = parseDataset(canvas, 'labels');
        const values = parseDataset(canvas, 'values');
        const colors = parseDataset(canvas, 'colors');

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        data: values,
                        backgroundColor: colors,
                        borderRadius: 6,
                        borderSkipped: false,
                        barThickness: 12,
                    },
                ],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        ...tooltipOptions,
                        callbacks: {
                            label: (context) => ` ${context.parsed.x}% terverifikasi`,
                        },
                    },
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: '#e2e8f0',
                            drawTicks: false,
                        },
                        border: {
                            display: false,
                        },
                        ticks: {
                            stepSize: 25,
                            padding: 8,
                            callback: (value) => `${value}%`,
                            font: { size: 11 },
                        },
                    },
                    y: {
                        grid: {
                            display: false,
                        },
                        border: {
                            display: false,
                        },
                        ticks: {
                            autoSkip: false,
                            padding: 8,
                            color: '#475569',
                            font: { size: 10 },
                            callback(value) {
                                const label = this.getLabelForValue(value);

                                return label.length > 24 ? `${label.slice(0, 24)}…` : label;
                            },
                        },
                    },
                },
            },
        });
    },
};

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('[data-sidebar]');
    const backdrop = document.querySelector('[data-sidebar-backdrop]');

    const openSidebar = () => {
        sidebar?.classList.remove('-translate-x-full');
        backdrop?.classList.remove('hidden');
    };

    const closeSidebar = () => {
        sidebar?.classList.add('-translate-x-full');
        backdrop?.classList.add('hidden');
    };

    document.querySelectorAll('[data-sidebar-open]').forEach((button) => {
        button.addEventListener('click', openSidebar);
    });

    document.querySelectorAll('[data-sidebar-close]').forEach((button) => {
        button.addEventListener('click', closeSidebar);
    });

    backdrop?.addEventListener('click', closeSidebar);

    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.passwordToggle);

            if (! input) {
                return;
            }

            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            button.setAttribute('aria-label', isPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        });
    });

    document.querySelectorAll('canvas[data-chart]').forEach((canvas) => {
        const builder = chartBuilders[canvas.dataset.chart];

        if (builder) {
            builder(canvas);
        }
    });
});
