<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConsumerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consumers = Consumer::all();

        return view('consumers.index', compact('consumers'));
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|numeric|phone:ID',
            'address' => 'required|string|max:255',
            'note' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
                $consumers = Consumer::create([
                    'name' => $validated['name'],
                    'phone_number' => $validated['phone_number'],
                    'address' => $validated['address'],
                    'note' => $validated['note'],
                ]);

            DB::commit();

            return redirect()->route('consumers.index')->with('success', 'Konsumen berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan data produk.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Consumer $consumer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consumer $consumer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consumer $consumer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|numeric|phone:ID',
            'address' => 'required|string|max:255',
            'note' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $consumer->update([
                'name' => $validated['name'],
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'],
                'note' => $validated['note'],
            ]);

            DB::commit();

            return redirect()->route('consumers.index')->with('success', 'Konsumen berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui data konsumen.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consumer $consumer)
    {
        try {
            Log::info('Product yang dihapus:', ['id' => $consumer->id, 'name' => $consumer->name]);
            $consumer->delete();
            return redirect()->route('consumers.index')->with('success', 'Konsumen berhasil dihapus.');
        } catch (QueryException $e) {
            return redirect()->route('consumers.index')->with('error', 'Konsumen tidak dapat dihapus karena masih digunakan di data/transaksi lain.');
        }
    }
}
