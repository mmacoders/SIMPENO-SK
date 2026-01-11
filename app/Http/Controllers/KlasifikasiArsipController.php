<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KlasifikasiArsip;
use App\Models\KategoriSk;

class KlasifikasiArsipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = KlasifikasiArsip::query();

        if ($request->has('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        $klasifikasis = $query->orderBy('kode', 'asc')->paginate(20)->withQueryString();

        return view('admin.klasifikasi.index', compact('klasifikasis'));
    }

    /**
     * Search specific for API/Ajax use.
     */
    public function search(Request $request)
    {
        $search = $request->get('term') ?? $request->q;

        $results = KlasifikasiArsip::where('kode', 'like', "%{$search}%")
            ->orWhere('nama', 'like', "%{$search}%")
            ->orderBy('kode', 'asc')
            ->limit(20)
            ->get();
            
        return response()->json($results);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Auto-fill nama from uraian since we removed nama input
        $request->merge(['nama' => $request->uraian]);

        $request->validate([
            'kode' => 'required|unique:klasifikasi_arsips,kode|max:50',
            'uraian' => 'required|string',
        ]);

         KlasifikasiArsip::create($request->all());

        return redirect()->route('klasifikasi.index')->with('success', 'Klasifikasi berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $klasifikasi = KlasifikasiArsip::findOrFail($id);

        // Auto-fill nama from uraian
        $request->merge(['nama' => $request->uraian]);

        $request->validate([
            'kode' => 'required|max:50|unique:klasifikasi_arsips,kode,' . $id,
            'uraian' => 'required|string',
        ]);

        $klasifikasi->update($request->all());

        return redirect()->route('klasifikasi.index')->with('success', 'Klasifikasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $klasifikasi = KlasifikasiArsip::findOrFail($id);
        $klasifikasi->delete();
        
        return redirect()->route('klasifikasi.index')->with('success', 'Klasifikasi berhasil dihapus.');
    }
}
