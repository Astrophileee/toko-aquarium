import $ from 'jquery';
window.$ = window.jQuery = $;

import Chart from 'chart.js/auto';
window.Chart = Chart;

import './bootstrap';
import Alpine from 'alpinejs';

import 'datatables.net-dt/css/dataTables.dataTables.min.css';
import DataTable from 'datatables.net-dt';
import Swal from 'sweetalert2';


window.Alpine = Alpine;
Alpine.start();

window.Swal = Swal;

window.showToast = function (type, message) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
};


$(document).ready(function () {
    const flashMessage = document.getElementById('flash-message');
    if (flashMessage) {
        const type = flashMessage.dataset.type;
        const message = flashMessage.dataset.message;

        if (type && message) {
            window.showToast(type, message);
        }
    }else {
        console.log('Flash message not found.');
    }

    // DataTable
    $('#usersTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthChange: false,
        language: {
            searchPlaceholder: 'Cari...',
            search: '',
        },
    });

    $('#productsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthChange: false,
        language: {
            searchPlaceholder: 'Cari...',
            search: '',
        },
    });

    $('#consumersTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthChange: false,
        language: {
            searchPlaceholder: 'Cari...',
            search: '',
        },
    });

    $('#transactionsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthChange: false,
        language: {
            searchPlaceholder: 'Cari...',
            search: '',
        },
    });

    $('#detailsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthChange: false,
        language: {
            searchPlaceholder: 'Cari...',
            search: '',
        },
    });

});

document.addEventListener('DOMContentLoaded', () => {
    const makeChart = (id, type, labels, data, label, color = '#3b82f6') => {
        const el = document.getElementById(id);
        if (!el) return;

        new Chart(el, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: color,
                    borderColor: color,
                    fill: true,
                    tension: 0.3,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    };

    makeChart('dailyRevenueChart', 'bar', window.dailyLabels, window.dailyData, 'Pendapatan Harian', '#3b82f6');
    makeChart('weeklyRevenueChart', 'line', window.weeklyLabels, window.weeklyData, 'Pendapatan Mingguan', '#f59e0b');
    makeChart('monthlyRevenueChart', 'bar', window.monthlyLabels, window.monthlyData, 'Pendapatan Bulanan', '#10b981');
    makeChart('bestProductChart', 'doughnut', window.productNames, window.productSales, 'Produk Terlaris', [
        '#3b82f6', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6'
    ]);
});


