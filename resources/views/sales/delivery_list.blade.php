@extends('layouts.app') 
@section('content')
<div class="container">
    <h2>Daftar Pengiriman Barang</h2>
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Nota / Pembeli</th>
                <th>Pengantar</th> 
                <th>Tipe Seafood</th>
                <th>Qty (Kg)</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deliveries as $sale)
            <tr>
                <td>{{ $sale->display_buyer_name }}</td>
                
                {{-- Menampilkan Nama Pengantar / Driver --}}
                <td>
                    @if($sale->driver_name)
                        <span class="fw-bold text-dark">{{ $sale->driver_name }}</span>
                        @if($sale->driver_phone)
                            <br><small class="text-muted">({{ $sale->driver_phone }})</small>
                        @endif
                    @else
                        <span class="text-muted font-italic">Belum diatur</span>
                    @endif
                </td>

                <td>{{ $sale->category->name ?? '-' }}</td>
                <td>{{ $sale->quantity_sold_kg }} Kg</td>
                <td>
                    <span class="badge {{ $sale->status == 'dalam perjalanan' ? 'bg-warning' : 'bg-secondary' }}">
                        {{ ucfirst($sale->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('delivery.input', $sale->id) }}" class="btn btn-primary btn-sm">
                        {{ $sale->status == 'sedang diproses' ? 'Proses & Antar' : 'Konfirmasi Sampai' }}
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection