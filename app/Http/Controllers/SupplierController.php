<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $supplier = Supplier::create($validated);

        // WAJIB DITAMBAHKAN AGAR BISA DIPANGGIL LEWAT AJAX POP-UP
        if ($request->wantsJson()) {
            return response()->json($supplier, 201);
        }

        return redirect()->route('incoming-stocks.create')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255,' . $supplier->id,
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $supplier->update($validated);

        if ($request->wantsJson()) {
            return response()->json($supplier, 200);
        }

        return redirect()->route('incoming-stocks.create')->with('success', 'Data tempat beli berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('incoming-stocks.create')->with('success', 'Data tempat beli berhasil dihapus.');
    }
}
