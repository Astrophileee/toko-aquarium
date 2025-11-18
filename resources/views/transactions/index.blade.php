@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Transaksi</h1>
        <a href="{{ route('transactions.create') }}">
            <button class="bg-black text-white px-4 py-2 rounded-md shadow hover:bg-gray-800">
                Tambah
            </button>
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table id="transactionsTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Transaksi Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kasir</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Konsumen</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $transaction->transaction_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ ucwords($transaction->user->name) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ ucwords($transaction->consumer->name) }}</td>
                        <td class="px-6 py-4 text-gray-700">
                            @foreach ($transaction->transactionDetails as $detail)
                            <span class="text-xs bg-gray-500 text-white py-1 px-3 rounded-full mx-2 inline-block max-w-[120px] break-words">
                                {{ $detail->product->name }}
                            </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">Rp. {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                            <a href="{{ route('transactions.detail', $transaction->id) }}"
                                class="px-3 py-1 text-sm font-medium text-white bg-orange-400 rounded-lg shadow hover:bg-green-600">
                                Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
<script>


</script>

@if (session('success') || session('error'))
    <div id="flash-message"
         data-type="{{ session('success') ? 'success' : 'error' }}"
         data-message="{{ session('success') ?? session('error') }}">
    </div>
@endif



@endsection
