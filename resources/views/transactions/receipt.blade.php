<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    :root{
      --paper-width: 80mm; 
      --font-size: 12px;
    }

    * { box-sizing: border-box; }
    body{
      margin: 0;
      padding: 10px;
      font-family: "Courier New", Courier, monospace;
      font-size: var(--font-size);
      color: #000;
      background: #fff;
    }

    .receipt{
      width: var(--paper-width);
      margin: 0 auto;
      line-height: 1.25;
    }

    .center{ text-align: center; }
    .bold{ font-weight: 700; }
    .small{ font-size: 11px; }
    .muted{ opacity: .9; }

    .row{
      display: flex;
      justify-content: space-between;
      gap: 10px;
    }
    .row > div:last-child{ text-align: right; }

    .hr{
      border-top: 1px dashed #000;
      margin: 8px 0;
    }

    .block{ margin: 6px 0; }

    .kv .k{ width: 22mm; flex: 0 0 22mm; }
    .kv .v{ flex: 1; text-align: left; word-break: break-word; }
    .kv .row{ align-items: flex-start; }

    .items{ margin-top: 4px; }
    .item{ margin: 6px 0; }
    .item-name{
      font-weight: 700;
      word-break: break-word;
    }
    .item-meta{
      display: flex;
      justify-content: space-between;
      gap: 8px;
    }
    .item-meta .left{ text-align: left; }
    .item-meta .right{ text-align: right; white-space: nowrap; }

    .totals .row{ margin: 4px 0; }
    .totals .grand{
      font-weight: 800;
      font-size: 13px;
    }

    @media print{
      body{ padding: 0; }
      .receipt{ margin: 0; }
      @page{
        margin: 4mm;
        size: 80mm auto;
      }
    }
  </style>
</head>

<body>
  <div class="receipt">
    <!-- HEADER TOKO -->
    <div class="center bold" style="font-size: 14px;">TOKO AQUARIUM</div>
    <div class="center small muted">Jl. Contoh Alamat No. 123, Kota</div>
    <div class="center small muted">Telp: 08xx-xxxx-xxxx</div>

    <div class="hr"></div>

    <!-- META TRANSAKSI -->
    @php
      $dateValue = $transaction->date ?? $transaction->created_at;
      $formattedDate = $dateValue ? \Carbon\Carbon::parse($dateValue)->format('d/m/Y H:i') : '-';
    @endphp

    <div class="block">
      <div class="row">
        <div>No. Transaksi</div>
        <div class="bold">{{ $transaction->transaction_number }}</div>
      </div>
      <div class="row">
        <div>Tanggal</div>
        <div>{{ $formattedDate }}</div>
      </div>
      <div class="row">
        <div>Kasir</div>
        <div>{{ $transaction->user->name ?? '-' }}</div>
      </div>
    </div>

    <div class="hr"></div>

    <div class="block kv">
      <div class="bold">KONSUMEN</div>
      <div class="row">
        <div class="k">Nama</div>
        <div class="v">: {{ $transaction->consumer->name ?? '-' }}</div>
      </div>
    </div>

    <div class="hr"></div>

    <!-- ITEM LIST -->
    <div class="items">
      <div class="row bold">
        <div>ITEM</div>
        <div>SUBTOTAL</div>
      </div>

      <div class="hr" style="margin-top:6px;"></div>

      @foreach ($transaction->transactionDetails as $detail)
        @php
          $subtotal = $detail->total_price ?? ($detail->price * $detail->qty);
        @endphp

        <div class="item">
          <div class="item-name">{{ $detail->product->name ?? '-' }}</div>
          <div class="item-meta small">
            <div class="left">
              {{ $detail->qty }} x Rp {{ number_format($detail->price, 0, ',', '.') }}
            </div>
            <div class="right">
              Rp {{ number_format($subtotal, 0, ',', '.') }}
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="hr"></div>

    <!-- TOTAL -->
    <div class="totals">
      <div class="row grand">
        <div>TOTAL</div>
        <div>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</div>
      </div>

      {{--
      <div class="row">
        <div>TUNAI</div>
        <div>Rp {{ number_format($transaction->paid_amount ?? 0, 0, ',', '.') }}</div>
      </div>
      <div class="row">
        <div>KEMBALI</div>
        <div>Rp {{ number_format($transaction->change_amount ?? 0, 0, ',', '.') }}</div>
      </div>
      --}}
    </div>

    <div class="hr"></div>

    <!-- FOOTER -->
    <div class="center small">
      Terima kasih telah berbelanja<br/>
      Barang yang sudah dibeli tidak dapat dikembalikan.
    </div>
  </div>
</body>
</html>
