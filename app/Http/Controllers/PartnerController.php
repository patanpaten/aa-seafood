<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::latest()->paginate(10);
        return view('partners.index', compact('partners'));
    }

    public function create()
    {
        return view('partners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:partners,name',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $partner = Partner::create($validated);

        if ($request->wantsJson()) {
            return response()->json($partner, 201);
        }

        return redirect()->route('sales.create')->with('success', 'Data pelanggan berhasil ditambahkan.');
    }

    public function edit(Partner $partner)
    {
        return view('partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:partners,name,' . $partner->id,
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $partner->update($validated);

        if ($request->wantsJson()) {
            return response()->json($partner, 200);
        }

        return redirect()->route('sales.create')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();
        return redirect()->route('sales.create')->with('success', 'Data pelanggan berhasil dihapus.');
    }
}
