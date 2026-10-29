import 'flowbite';
import DataTable from 'datatables.net-dt';
import Swal from 'sweetalert2';
import Chart from 'chart.js/auto';

DataTable.ext.errMode = 'none';

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

    document.querySelectorAll('table[data-datatable]').forEach((table) => {
        const headers = Array.from(table.querySelectorAll('thead th'));

        const columns = headers.map((header, index) => ({
            data: header.dataset.column,
            orderable: header.dataset.orderable !== 'false',
            searchable: header.dataset.searchable !== 'false',
            className: header.dataset.className ?? '',
            createdCell: (cell) => {
                cell.dataset.label = headers[index].textContent.trim();
            },
        }));

        const orderColumn = Number.parseInt(table.dataset.orderColumn ?? '0', 10);
        const orderDirection = table.dataset.orderDirection === 'desc' ? 'desc' : 'asc';

        new DataTable(table, {
            processing: true,
            serverSide: true,
            ajax: {
                url: table.dataset.url,
                type: 'GET',
            },
            columns,
            order: [[orderColumn, orderDirection]],
            pageLength: 10,
            language: {
                processing: 'Memuat data...',
                search: 'Cari:',
                searchPlaceholder: 'Cari data...',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '(difilter dari _MAX_ total data)',
                zeroRecords: 'Data tidak ditemukan',
                emptyTable: 'Belum ada data',
                paginate: {
                    first: 'Awal',
                    previous: 'Sebelumnya',
                    next: 'Berikutnya',
                    last: 'Akhir',
                },
            },
        });
    });

    document.addEventListener('submit', (event) => {
        const form = event.target.closest('form[data-confirm]');

        if (! form || form.dataset.confirmed === 'true') {
            return;
        }

        event.preventDefault();

        Swal.fire({
            title: form.dataset.confirmTitle ?? 'Konfirmasi',
            text: form.dataset.confirm,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: form.dataset.confirmButton ?? 'Ya, lanjutkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#047857',
            cancelButtonColor: '#64748b',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                form.dataset.confirmed = 'true';
                form.requestSubmit();
            }
        });
    });

    const showSweetAlertNotifications = async () => {
        const notifications = document.querySelectorAll('[data-sweet-alert]');

        for (const notification of notifications) {
            await Swal.fire({
                icon: notification.dataset.icon ?? 'info',
                title: notification.dataset.title ?? 'Informasi',
                text: notification.dataset.text ?? '',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#047857',
            });
        }
    };

    showSweetAlertNotifications();
});
