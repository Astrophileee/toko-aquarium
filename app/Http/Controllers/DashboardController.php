<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaction;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // === 1. Pendapatan Harian (7 Hari Terakhir) ===
        $dailyRevenue = Transaction::select(
                DB::raw("strftime('%Y-%m-%d', date) as date"),
                DB::raw('SUM(total_price) as total')
            )
            ->where('date', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dailyLabels = $dailyRevenue->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'));
        $dailyData = $dailyRevenue->pluck('total');

        // === 2. Pendapatan Mingguan (1 Bulan Terakhir) ===
        $weeklyRevenue = Transaction::select(
                DB::raw("strftime('%Y-W%W', date) as week"),
                DB::raw('SUM(total_price) as total')
            )
            ->where('date', '>=', Carbon::now()->subMonth())
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        $weeklyLabels = $weeklyRevenue->pluck('week')->map(fn($w) => 'Minggu ' . substr($w, -2));
        $weeklyData = $weeklyRevenue->pluck('total');

        // === 3. Pendapatan Bulanan (1 Tahun Terakhir) ===
        $monthlyRevenue = Transaction::select(
                DB::raw("strftime('%m', date) as month"),
                DB::raw('SUM(total_price) as total')
            )
            ->where('date', '>=', Carbon::now()->startOfYear())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyLabels = $monthlyRevenue->pluck('month')->map(fn($m) => Carbon::create()->month((int)$m)->format('M'));
        $monthlyData = $monthlyRevenue->pluck('total');

        // === 4. Produk Terlaris ===
        $bestProducts = DetailTransaction::select(
                'products.name',
                DB::raw('SUM(detail_transactions.qty) as total_sold')
            )
            ->join('products', 'detail_transactions.product_id', '=', 'products.id')
            ->groupBy('products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $productNames = $bestProducts->pluck('name');
        $productSales = $bestProducts->pluck('total_sold');

        return view('dashboard', compact(
            'dailyLabels', 'dailyData',
            'weeklyLabels', 'weeklyData',
            'monthlyLabels', 'monthlyData',
            'productNames', 'productSales'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
