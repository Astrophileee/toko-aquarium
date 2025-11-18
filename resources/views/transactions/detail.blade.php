@extends('layouts.app')

@section('content')
    <a href="{{ request('back') ?? route('transactions.index') }}"><i class="fa-solid fa-arrow-left"></i> back</a>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Detail Informasi transaksi</h1>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <table class="table-auto w-full text-left text-sm">
            <tr>
                <th class="w-1/4 text-gray-600">Nomor Transaksi</th>
                <td>{{ $transaction->transaction_number }}</td>
            </tr>
            <tr>
                <th class="w-1/4 text-gray-600">Nama Konsumen</th>
                <td>{{ $transaction->consumer->name }}</td>
            </tr>
            <tr>
                <th class="text-gray-600">Nama Kasir</th>
                <td>{{ $transaction->user->name }}</td>
            </tr>
            <tr>
                <th class="text-gray-600">Tanggal</th>
                <td>{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
            </tr>
            <tr>
                <th class="text-gray-600">Total Harga</th>
                <td> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-lg font-semibold mb-4">Barang</h2>
        @if($transaction->transactionDetails->count())
        <table class="table-auto w-full text-left text-sm">
            <thead>
                <tr>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->transactionDetails as $detail)
                    <tr>
                        <td>{{ strtoupper($detail->product->code) }}</td>
                        <td>{{ $detail->product->name }}</td>
                        <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td>{{ $detail->qty }}</td>
                        <td>Rp {{ number_format($detail->price * $detail->qty, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
<script>


</script>

@if (session('success') || session('error'))
    <div id="flash-message"
         data-type="{{ session('success') ? 'success' : 'error' }}"
         data-message="{{ session('success') ?? session('error') }}">
    </div>
@endif



@endsection


