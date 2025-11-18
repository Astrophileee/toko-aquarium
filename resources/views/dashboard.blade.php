@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <h1 class="text-xl font-bold mb-6">Dashboard</h1>

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
</script>
@endsection
