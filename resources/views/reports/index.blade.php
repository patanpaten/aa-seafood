@extends('layouts.app')

@section('title', 'Laporan Barang & Penjualan')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight uppercase">Laporan Barang & Penjualan</h1>
            <p class="text-slate-500 font-medium text-sm mt-1">Ringkasan barang masuk, penjualan, dan sisa stok.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('reports.export-pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'group' => $selectedGroup]) }}" 
                class="inline-flex items-center justify-center px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-2xl shadow-lg shadow-rose-200 transition-all transform active:scale-95 uppercase text-xs tracking-widest">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 mb-10">
        <form action="{{ route('reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" 
                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" 
                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Kelompok Barang</label>
                <select name="group" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700 appearance-none">
                    <option value="">Semua Grup</option>
                    @foreach($categoryGroups as $groupName)
                        <option value="{{ $groupName }}" @selected($selectedGroup === $groupName)>{{ $groupName }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-extrabold py-5 rounded-2xl shadow-lg transition-all transform active:scale-[0.98] uppercase tracking-widest text-sm">
                Filter Laporan
            </button>
        </form>
    </div>

    @if($selectedGroup)
        <div class="mb-6">
            <span class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-2xl text-xs font-black uppercase tracking-[0.2em] border border-blue-100">
                Kelompok Yang Dipilih: {{ $selectedGroup }}
            </span>
        </div>
    @endif

    <!-- Summary Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group hover:border-blue-200 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <svg class="w-16 h-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </div>
            <p class="text-[10px] font-bold text-blue-400 uppercase tracking-[0.2em] mb-3">Barang Masuk</p>
            <p class="text-3xl font-black text-slate-900">{{ number_format($reportData['total_incoming'], 2) }} <span class="text-sm font-bold text-slate-400">kg</span></p>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group hover:border-rose-200 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <svg class="w-16 h-16 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            </div>
            <p class="text-[10px] font-bold text-rose-400 uppercase tracking-[0.2em] mb-3">Selisih Berat</p>
            <p class="text-3xl font-black text-slate-900">{{ number_format($reportData['total_shrinkage'], 2) }} <span class="text-sm font-bold text-slate-400">kg</span></p>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group hover:border-emerald-200 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <svg class="w-16 h-16 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-[0.2em] mb-3">Barang Terjual</p>
            <p class="text-3xl font-black text-slate-900">{{ number_format($reportData['total_sales_kg'], 2) }} <span class="text-sm font-bold text-slate-400">kg</span></p>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group hover:border-indigo-200 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <svg class="w-16 h-16 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-[0.2em] mb-3">Pendapatan</p>
            <p class="text-3xl font-black text-slate-900"><span class="text-sm font-bold text-slate-400">Rp</span> {{ number_format($reportData['total_revenue'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Rincian Per Barang</h3>
            <span class="px-3 py-1 bg-white rounded-full text-[10px] font-bold text-slate-400 border border-slate-200 uppercase tracking-widest">Data Periode</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/20 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Kelompok</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Nama Barang</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Barang Masuk</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Barang Terjual</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-blue-400 uppercase tracking-[0.2em]">Sisa Stok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($reportData['breakdown'] as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center px-3 py-2 bg-slate-100 text-slate-500 rounded-xl text-xs font-bold uppercase tracking-widest">{{ $item['group'] }}</span>
                        </td>
                        <td class="px-8 py-6 font-bold text-slate-700">{{ $item['type'] }}</td>
                        <td class="px-8 py-6 text-sm font-medium text-slate-500">{{ number_format($item['incoming'], 2) }} kg</td>
                        <td class="px-8 py-6 text-sm font-medium text-slate-500">{{ number_format($item['sales'], 2) }} kg</td>
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-xl font-black text-sm">
                                {{ number_format($item['current_stock'], 2) }} kg
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-slate-400 font-medium italic">Tidak ada data untuk periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-900">
                    <tr class="font-bold">
                        <td class="px-8 py-6 text-slate-400 uppercase text-[10px] tracking-[0.2em]">TOTAL GLOBAL</td>
                        <td class="px-8 py-6 text-white text-lg">{{ $selectedGroup ?: 'Semua Grup' }}</td>
                        <td class="px-8 py-6 text-white text-lg">{{ number_format($reportData['total_incoming'], 2) }} kg</td>
                        <td class="px-8 py-6 text-white text-lg">{{ number_format($reportData['total_sales_kg'], 2) }} kg</td>
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl font-black text-lg shadow-lg shadow-blue-900/50">
                                {{ number_format($reportData['current_stock'], 2) }} kg
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
