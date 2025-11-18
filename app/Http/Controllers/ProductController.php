<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $lastCode = Product::latest('code')->first()?->code ?? 'FISH_00000';
        $nextNumber = (int) substr($lastCode, 6) + 1;
        $newCode = 'FISH_' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:0',
            'exp' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
                $product = Product::create([
                    'code' => $newCode,
                    'name' => $validated['name'],
                    'stock' => $validated['stock'],
                    'price' => $validated['price'],
                    'exp' => $validated['exp'],
                ]);

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan data produk.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:0',
            'exp' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
            $product->update([
                'name' => $validated['name'],
                'stock' => $validated['stock'],
                'price' => $validated['price'],
                'exp' => $validated['exp'],
            ]);

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui data produk.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            Log::info('Product yang dihapus:', ['id' => $product->id, 'name' => $product->name]);
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
        } catch (QueryException $e) {
            return redirect()->route('products.index')->with('error', 'Produk tidak dapat dihapus karena masih digunakan di data/transaksi lain.');
        }
    }
}
