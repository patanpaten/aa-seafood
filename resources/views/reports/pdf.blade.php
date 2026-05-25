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
        .summary-table td { padding: 10px; border: 1px solid #ddd; width: 25%; }
        .label { font-size: 10px; font-weight: bold; color: #777; text-transform: uppercase; display: block; }
        .value { font-size: 14px; font-weight: bold; color: #000; }
        
        .detail-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .detail-table th { background-color: #f2f2f2; padding: 10px; border: 1px solid #ddd; text-align: left; font-size: 11px; text-transform: uppercase; }
        .detail-table td { padding: 8px; border: 1px solid #ddd; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; color: #999; font-style: italic; }
        .total-row { font-weight: bold; background-color: #fafafa; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Laporan Inventaris & Penjualan</h1>
        <p class="subtitle">Periode: {{ $startDate }} s/d {{ $endDate }}</p>
    </div>

    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td>
                    <span class="label">Total Masuk</span>
                    <span class="value">{{ number_format($reportData['total_incoming'], 2) }} kg</span>
                </td>
                <td>
                    <span class="label">Total Susut</span>
                    <span class="value">{{ number_format($reportData['total_shrinkage'], 2) }} kg</span>
                </td>
                <td>
                    <span class="label">Total Jual</span>
                    <span class="value">{{ number_format($reportData['total_sales_kg'], 2) }} kg</span>
                </td>
                <td>
                    <span class="label">Total Pendapatan</span>
                    <span class="value">Rp {{ number_format($reportData['total_revenue'], 0, ',', '.') }}</span>
                </td>
            </tr>
        </table>
    </div>

    <h3>Detail Stok Per Jenis Seafood</h3>
    <table class="detail-table">
        <thead>
            <tr>
                <th>Jenis Seafood</th>
                <th>Masuk (Periode)</th>
                <th>Keluar (Periode)</th>
                <th>Stok Saat Ini</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData['breakdown'] as $item)
            <tr>
                <td>{{ $item['type'] }}</td>
                <td>{{ number_format($item['incoming'], 2) }} kg</td>
                <td>{{ number_format($item['sales'], 2) }} kg</td>
                <td><strong>{{ number_format($item['current_stock'], 2) }} kg</strong></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>TOTAL GLOBAL</td>
                <td>{{ number_format($reportData['total_incoming'], 2) }} kg</td>
                <td>{{ number_format($reportData['total_sales_kg'], 2) }} kg</td>
                <td>{{ number_format($reportData['current_stock'], 2) }} kg</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada: {{ $generatedAt }} | AA Seafood System
    </div>
</body>
</html>