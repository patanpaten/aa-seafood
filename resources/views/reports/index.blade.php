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
    <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end mb-8 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
    
        <div>
            <label for="start_date" class="block text-sm font-semibold text-slate-700 mb-1">Dari Tanggal</label>
            <input type="date" name="start_date" id="start_date" value="{{ request('start_date', $startDate) }}" 
                   class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-slate-700">
        </div>
        
        <div>
            <label for="end_date" class="block text-sm font-semibold text-slate-700 mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" id="end_date" value="{{ request('end_date', $endDate) }}" 
                   class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-slate-700">
        </div>
    
        <div>
            <label for="group" class="block text-sm font-semibold text-slate-700 mb-1">Grup</label>
            <select name="group" id="group" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-slate-700">
                <option value="">Semua Grup</option>
                @foreach($categoryGroups as $groupOption)
                    <option value="{{ $groupOption }}" {{ request('group', $selectedGroup) == $groupOption ? 'selected' : '' }}>
                        {{ $groupOption }}
                    </option>
                @endforeach
            </select>
        </div>
    
        <div>
            <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-1">Barang</label>
            <select name="category_id" id="category_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-slate-700">
                <option value="">Semua Barang</option>
                @foreach($categoriesList as $category)
                    <option value="{{ $category->id }}" {{ request('category_id', $selectedCategory) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
    
        <div>
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-2 bg-sky-600 text-white font-medium rounded-xl hover:bg-sky-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                </svg>
                Terapkan Filter
            </button>
        </div>
    </form>

    @if($selectedGroup)
        <div class="mb-6">
            <span class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-2xl text-xs font-black uppercase tracking-[0.2em] border border-blue-100">
                Kelompok Yang Dipilih: {{ $selectedGroup }}
            </span>
        </div>
    @endif

    <!-- Summary Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-10">
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

        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group hover:border-amber-200 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <svg class="w-16 h-16 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2h6c0-1.105-1.343-2-3-2zm0 0V6m0 12v-2m-6-2h12M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <p class="text-[10px] font-bold text-amber-400 uppercase tracking-[0.2em] mb-3">Modal Belanja</p>
            <p class="text-3xl font-black text-slate-900"><span class="text-sm font-bold text-slate-400">Rp</span> {{ number_format($reportData['total_purchase_cost'], 0, ',', '.') }}</p>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group hover:border-indigo-200 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <svg class="w-16 h-16 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-[0.2em] mb-3">Pendapatan</p>
            <p class="text-3xl font-black text-slate-900"><span class="text-sm font-bold text-slate-400">Rp</span> {{ number_format($reportData['total_revenue'], 0, ',', '.') }}</p>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group hover:border-emerald-200 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <svg class="w-16 h-16 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>
            </div>
            <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-[0.2em] mb-3">Laba Kotor</p>
            <p class="text-3xl font-black {{ $reportData['gross_profit'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                <span class="text-sm font-bold text-slate-400">Rp</span> {{ number_format($reportData['gross_profit'], 0, ',', '.') }}
            </p>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden mt-10">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Detail Aktivitas</h3>
            <span class="px-3 py-1 bg-white rounded-full text-[10px] font-bold text-slate-400 border border-slate-200 uppercase tracking-widest">Barang Masuk & Penjualan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/20 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Jenis</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Group</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Tempat / Pembeli</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Barang</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Qty</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Harga Beli</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Harga Jual</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Total Transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($reportData['activity_details'] as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-5 text-sm font-medium text-slate-500">{{ $item['date'] }}</td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center px-3 py-2 rounded-xl text-xs font-bold uppercase tracking-widest {{ $item['type'] === 'Penjualan' ? 'bg-emerald-50 text-emerald-600' : 'bg-blue-50 text-blue-600' }}">
                                    {{ $item['type'] }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center px-3 py-2 bg-slate-100 text-slate-500 rounded-xl text-xs font-bold uppercase tracking-widest">
                                    {{ $item['group_name'] }}
                                </span>
                            </td>
                            <td class="px-6 py-5 font-bold text-slate-700">{{ $item['party_name'] }}</td>
                            <td class="px-6 py-5 text-sm font-medium text-slate-500">{{ $item['category_name'] }}</td>
                            <td class="px-6 py-5 text-sm font-medium text-slate-500">{{ number_format($item['quantity'], 2) }} kg</td>
                            <td class="px-6 py-5 text-sm font-bold text-amber-600">
                                {{ $item['purchase_price_per_kg'] !== null ? 'Rp ' . number_format($item['purchase_price_per_kg'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-5 text-sm font-bold text-emerald-600">
                                {{ $item['sale_price_per_kg'] !== null ? 'Rp ' . number_format($item['sale_price_per_kg'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-5 text-sm font-black text-slate-900">
                                {{ $item['total_price'] !== null ? 'Rp ' . number_format($item['total_price'], 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-10 text-center text-slate-400 font-medium italic">Belum ada aktivitas pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
