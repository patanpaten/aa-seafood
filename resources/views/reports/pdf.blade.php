<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan AA Seafood</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; text-transform: uppercase; margin: 0; }
        .subtitle { font-size: 12px; color: #666; margin: 5px 0 0 0; }
        
        .summary-box { margin-bottom: 30px; }
        .summary-table { width: 100%; border-collapse: collapse; }
        .summary-table td { padding: 10px; border: 1px solid #ddd; width: 33.33%; }
        .label { font-size: 10px; font-weight: bold; color: #777; text-transform: uppercase; display: block; }
        .value { font-size: 14px; font-weight: bold; color: #000; }
        
        .detail-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .detail-table th { background-color: #f2f2f2; padding: 10px; border: 1px solid #ddd; text-align: left; font-size: 11px; text-transform: uppercase; }
        .detail-table td { padding: 8px; border: 1px solid #ddd; }
        .section-title { margin-top: 28px; margin-bottom: 8px; font-size: 14px; font-weight: bold; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; color: #999; font-style: italic; }
        .total-row { font-weight: bold; background-color: #fafafa; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Laporan Barang & Penjualan</h1>
        <p class="subtitle">Periode: {{ $startDate }} s/d {{ $endDate }}</p>
        <p class="subtitle">Kelompok Barang: {{ $selectedGroup ?: 'Semua Kelompok' }}</p>
    </div>

    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td>
                    <span class="label">Barang Masuk</span>
                    <span class="value">{{ number_format($reportData['total_incoming'], 2) }} kg</span>
                </td>
                <td>
                    <span class="label">Selisih Berat</span>
                    <span class="value">{{ number_format($reportData['total_shrinkage'], 2) }} kg</span>
                </td>
                <td>
                    <span class="label">Barang Terjual</span>
                    <span class="value">{{ number_format($reportData['total_sales_kg'], 2) }} kg</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Modal Belanja</span>
                    <span class="value">Rp {{ number_format($reportData['total_purchase_cost'], 0, ',', '.') }}</span>
                </td>
                <td>
                    <span class="label">Total Pendapatan</span>
                    <span class="value">Rp {{ number_format($reportData['total_revenue'], 0, ',', '.') }}</span>
                </td>
                <td>
                    <span class="label">Laba Kotor</span>
                    <span class="value">Rp {{ number_format($reportData['gross_profit'], 0, ',', '.') }}</span>
                </td>
            </tr>
        </table>
    </div>

    <h3 class="section-title">Detail Aktivitas</h3>
    <table class="detail-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Group</th>
                <th>Tempat / Pembeli</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Total Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData['activity_details'] as $item)
            <tr>
                <td>{{ $item['date'] }}</td>
                <td>{{ $item['type'] }}</td>
                <td>{{ $item['group_name'] }}</td>
                <td>{{ $item['party_name'] }}</td>
                <td>{{ $item['category_name'] }}</td>
                <td>{{ number_format($item['quantity'], 2) }} kg</td>
                <td>{{ $item['purchase_price_per_kg'] !== null ? 'Rp ' . number_format($item['purchase_price_per_kg'], 0, ',', '.') : '-' }}</td>
                <td>{{ $item['sale_price_per_kg'] !== null ? 'Rp ' . number_format($item['sale_price_per_kg'], 0, ',', '.') : '-' }}</td>
                <td>{{ $item['total_price'] !== null ? 'Rp ' . number_format($item['total_price'], 0, ',', '.') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9">Belum ada aktivitas pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ $generatedAt }} | AA Seafood System
    </div>
</body>
</html>
