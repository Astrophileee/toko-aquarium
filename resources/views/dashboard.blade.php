@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <h1 class="text-xl font-bold mb-6">Dashboard</h1>

    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form id="transaction-report-form" class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-3 md:space-y-0" method="GET" action="{{ route('transactions.report') }}" target="_blank">
            <div class="flex flex-col">
                <label for="start_date" class="text-sm text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" id="start_date" name="start_date" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ now()->startOfMonth()->toDateString() }}" required>
            </div>
            <div class="flex flex-col">
                <label for="end_date" class="text-sm text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" id="end_date" name="end_date" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ now()->toDateString() }}" required>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700">
                    Cetak Laporan PDF
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2">Pendapatan 7 Hari Terakhir</h3>
            <canvas id="dailyRevenueChart"></canvas>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2">Pendapatan Mingguan (1 Bulan)</h3>
            <canvas id="weeklyRevenueChart"></canvas>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2">Pendapatan Bulanan (1 Tahun)</h3>
            <canvas id="monthlyRevenueChart"></canvas>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2">Produk Terlaris</h3>
            <canvas id="bestProductChart"></canvas>
        </div>
    </div>
</div>

<script>
    window.dailyLabels = @json($dailyLabels);
    window.dailyData = @json($dailyData);
    window.weeklyLabels = @json($weeklyLabels);
    window.weeklyData = @json($weeklyData);
    window.monthlyLabels = @json($monthlyLabels);
    window.monthlyData = @json($monthlyData);
    window.productNames = @json($productNames);
    window.productSales = @json($productSales);

    const reportForm = document.getElementById('transaction-report-form');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    reportForm.addEventListener('submit', function (e) {
        if (!startDateInput.value || !endDateInput.value) {
            e.preventDefault();
            alert('Lengkapi tanggal mulai dan tanggal akhir.');
            return;
        }

        if (startDateInput.value > endDateInput.value) {
            e.preventDefault();
            alert('Tanggal akhir harus setelah atau sama dengan tanggal mulai.');
        }
    });
</script>
@endsection
