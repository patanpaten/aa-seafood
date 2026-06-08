@extends('layouts.app')

@section('title', 'Riwayat Cek Stok')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight uppercase">Riwayat Cek Stok</h1>
            <p class="text-slate-500 font-medium text-sm mt-1">Lihat riwayat perbedaan antara catatan stok dan jumlah barang asli.</p>
        </div>
        <a href="{{ route('stock-adjustments.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-95 uppercase text-xs tracking-widest">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Cek Stok Baru
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Tanggal</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Kelompok</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Nama Barang</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Stok Tercatat</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Stok Hasil Hitung</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Selisih</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Catatan</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Dicek Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($adjustments as $adj)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <span class="text-sm font-medium text-slate-500">{{ $adj->created_at->format('d/m/Y H:i') }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center px-3 py-2 bg-slate-100 text-slate-500 rounded-xl text-xs font-bold uppercase tracking-widest">
                                {{ $adj->category->display_group_name }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="font-bold text-slate-700 group-hover:text-blue-600 transition-colors">{{ $adj->category->name }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-bold text-slate-500">{{ number_format($adj->previous_stock, 2) }} kg</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-bold text-slate-500">{{ number_format($adj->actual_stock, 2) }} kg</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-black {{ $adj->difference >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $adj->difference > 0 ? '+' : '' }}{{ number_format($adj->difference, 2) }} kg
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-medium text-slate-400 italic">"{{ $adj->reason }}"</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-medium text-slate-500">{{ $adj->user->name }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-8 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H4a2 2 0 00-2 2v7m18 0v5a2 2 0 01-2 2H4a2 2 0 01-2-2v-5m18 0l-2-2m-2-2l-2-2m-2-2l-2-2m-2-2L4 13"></path></svg>
                                </div>
                                <p class="text-slate-400 font-medium italic">Belum ada riwayat cek stok.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($adjustments->hasPages())
            <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                {{ $adjustments->links() }}
            </div>
        @endif
    </div>
@endsection
