@extends('layouts.app')

@section('title', 'Catat Stok Masuk')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 sm:p-12">
                <div class="flex items-center space-x-4 mb-10">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">Stok Masuk</h1>
                        <p class="text-slate-500 text-sm font-medium">Input penerimaan seafood dari supplier.</p>
                    </div>
                </div>

                <form action="{{ route('incoming-stocks.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Tanggal Masuk</label>
                            <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" 
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700" required>
                        </div>

                        <div>
                            <label for="category_id" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Jenis Seafood</label>
                            <select name="category_id" id="category_id" 
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700 appearance-none" required>
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="supplier_id" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Supplier</label>
                        <select name="supplier_id" id="supplier_id" 
                            class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700 appearance-none" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-blue-50/50 rounded-[2rem] border border-blue-100">
                        <div>
                            <label for="receipt_weight" class="block text-xs font-bold text-blue-400 uppercase tracking-widest mb-3 ml-1">Berat Nota (Kg)</label>
                            <input type="number" step="0.01" name="receipt_weight" id="receipt_weight" placeholder="0.00"
                                class="w-full px-5 py-4 bg-white border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-black text-2xl text-blue-600" required>
                        </div>
                        <div>
                            <label for="actual_weight" class="block text-xs font-bold text-blue-400 uppercase tracking-widest mb-3 ml-1">Berat Riil (Kg)</label>
                            <input type="number" step="0.01" name="actual_weight" id="actual_weight" placeholder="0.00"
                                class="w-full px-5 py-4 bg-white border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-black text-2xl text-blue-600" required>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-5 rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-[0.98] uppercase tracking-widest text-sm">
                            Simpan Data Stok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection