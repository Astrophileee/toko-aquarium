<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use App\Models\DetailTransaction;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with(['transactionDetails.product', 'user', 'consumer'])->get();
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $users = User::all();
        return view('transactions.create', compact('products','users'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'like', "%$query%")->get();
        return response()->json($products);
    }
    public function searchConsumer(Request $request)
    {
        $query = $request->input('query');
        $consumers = Consumer::where('name', 'like', "%$query%")
                        ->orWhere('email', 'like', "%$query%")
                        ->orWhere('phone_number', 'like', "%$query%")
                        ->get(['id', 'name', 'email', 'phone_number', 'address']);

        return response()->json($consumers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $lastCode = Product::latest('code')->first()?->code ?? 'TRANSACTION_00000';
        $nextNumber = (int) substr($lastCode, 6) + 1;
        $newCode = 'TRANSACTION_' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        $request->validate([
            'total_price' => 'required|numeric|min:0',
            'consumer_id' => 'required|numeric|exists:consumers,id',
            'cart' => 'required|array',
            'cart.*.productId' => 'required|exists:products,id',
            'cart.*.qty' => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric|min:0',
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'transaction_number' => $newCode,
            'total_price' => $request->total_price,
            'date' => $request->date,
            'consumer_id' => $request->consumer_id,
        ]);

        foreach ($request->cart as $item) {
            $product = Product::findOrFail($item['productId']);
            if ($product->stock < $item['qty']) {
                throw new \Exception("Stok produk {$product->name} tidak mencukupi.");
            }
            $product->decrement('stock', $item['qty']);

            $total_price = $item['qty'] * $item['price'];
            
            DetailTransaction::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['productId'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'total_price' => $total_price
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load('transactionDetails');
        return view('transactions.detail', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
