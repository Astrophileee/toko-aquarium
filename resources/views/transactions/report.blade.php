<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    :root{
      --text: #111827;
      --muted: #6b7280;
      --line: #e5e7eb;
      --soft: #f9fafb;
      --soft2:#f3f4f6;
      --radius: 12px;
    }

    *{ box-sizing:border-box; }
    body{
      margin:0;
      padding:24px;
      font-family: Arial, Helvetica, sans-serif;
      color: var(--text);
      font-size: 12px;
      background: #fff;
    }

    .wrap{
      max-width: 980px;
      margin: 0 auto;
    }

    .header{
      display:flex;
      justify-content:space-between;
      align-items:flex-start;
      gap:16px;
      padding: 16px 18px;
      border: 1px solid var(--line);
      border-radius: var(--radius);
      background: var(--soft);
    }
    .brand{
      font-weight: 800;
      font-size: 16px;
      letter-spacing: .3px;
      text-transform: uppercase;
      margin-bottom: 4px;
    }
    .brand-sub{
      color: var(--muted);
      font-size: 11px;
      line-height: 1.35;
    }
    .meta{
      text-align:right;
      min-width: 260px;
    }
    .meta .title{
      font-weight: 800;
      font-size: 14px;
      margin-bottom: 6px;
    }
    .meta .row{
      display:flex;
      justify-content:flex-end;
      gap:8px;
      margin: 2px 0;
      color: var(--muted);
    }
    .meta .row b{ color: var(--text); font-weight:700; }

    .grid{
      display:grid;
      grid-template-columns: 1fr 1fr 1fr 1fr;
      gap: 12px;
      margin-top: 14px;
    }
    .card{
      border: 1px solid var(--line);
      border-radius: var(--radius);
      background: #fff;
      padding: 12px 14px;
      min-height: 74px;
    }
    .card .label{
      color: var(--muted);
      font-size: 11px;
      margin-bottom: 6px;
    }
    .card .value{
      font-size: 14px;
      font-weight: 800;
    }

    .table-wrap{
      margin-top: 14px;
      border: 1px solid var(--line);
      border-radius: var(--radius);
      overflow: hidden;
    }

    table{
      width:100%;
      border-collapse: collapse;
      background:#fff;
    }
    thead th{
      background: var(--soft2);
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: .3px;
      color:#374151;
      padding: 10px 10px;
      border-bottom: 1px solid var(--line);
      text-align:left;
      white-space: nowrap;
    }
    tbody td{
      padding: 10px 10px;
      border-bottom: 1px solid var(--line);
      vertical-align: top;
    }
    tbody tr:last-child td{ border-bottom: none; }

    .text-right{ text-align:right; }
    .text-center{ text-align:center; }
    .muted{ color: var(--muted); font-size: 11px; }
    .nowrap{ white-space: nowrap; }

    .tfoot{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:12px;
      padding: 12px 14px;
      background: var(--soft);
      border-top: 1px solid var(--line);
    }
    .tfoot .note{ color: var(--muted); font-size: 11px; }
    .tfoot .grand{
      font-size: 13px;
      font-weight: 900;
    }

    /* Print friendly */
    @media print{
      body{ padding: 0; }
      .wrap{ max-width: 100%; }
      .header, .card, .table-wrap{ box-shadow:none; }
      @page{ margin: 10mm; }
    }
  </style>
</head>

<body>
  <div class="wrap">
    @php
      $printedAt = \Carbon\Carbon::now()->format('d/m/Y H:i');
      // optional tambahan:
      // $avg = $transactions->count() ? ($totalRevenue / $transactions->count()) : 0;
    @endphp

    <!-- HEADER -->
    <div class="header">
      <div>
        <div class="brand">Toko Aquarium</div>
        <div class="brand-sub">
          Jl. Contoh Alamat No. 123, Kota<br/>
          Telp: 08xx-xxxx-xxxx
        </div>
      </div>

      <div class="meta">
        <div class="title">Laporan Transaksi</div>
        <div class="row">
          <span>Periode:</span>
          <b>{{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</b>
        </div>
        <div class="row">
          <span>Dicetak:</span>
          <b>{{ $printedAt }}</b>
        </div>
      </div>
    </div>

    <!-- RINGKASAN -->
    <div class="grid">
        <div class="label">Total Transaksi : {{ $transactions->count() }}</div>
        <div class="label">Total Pendapatan : Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>

    <!-- TABEL -->
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:5%;">No</th>
            <th style="width:18%;">Tanggal</th>
            <th style="width:18%;">No. Transaksi</th>
            <th style="width:24%;">Konsumen</th>
            <th style="width:15%;">Kasir</th>
            <th style="width:20%;" class="text-right">Total</th>
          </tr>
        </thead>

        <tbody>
          @forelse ($transactions as $transaction)
            @php
              $tDate = \Carbon\Carbon::parse($transaction->date ?? $transaction->created_at)->format('d/m/Y H:i');
            @endphp
            <tr>
              <td class="text-center">{{ $loop->iteration }}</td>
              <td class="nowrap">{{ $tDate }}</td>
              <td class="nowrap">{{ $transaction->transaction_number }}</td>
              <td>
                <div>{{ $transaction->consumer->name ?? '-' }}</div>
                <div class="muted">{{ $transaction->consumer->phone_number ?? '-' }}</div>
              </td>
              <td>{{ $transaction->user->name ?? '-' }}</td>
              <td class="text-right nowrap">
                Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center muted" style="padding:18px;">
                Tidak ada transaksi pada periode ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>

      <!-- FOOTER TOTAL -->
      <div class="tfoot">
        <div class="grand">
          TOTAL: Rp {{ number_format($totalRevenue, 0, ',', '.') }}
        </div>
      </div>
    </div>
  </div>
</body>
</html>
