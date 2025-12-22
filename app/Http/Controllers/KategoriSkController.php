<?php

namespace App\Http\Controllers;

use App\Models\KategoriSK;
use Illuminate\Http\Request;

class KategoriSKController extends Controller
{
    public function index()
    {
        $kategoris = KategoriSK::orderBy('jenis_surat')->paginate(10);
        return view('kategori_sks.index', compact('kategoris'));
    }

    public function create()
    {
        return view('kategori_sks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat' => 'required|string|max:100|unique:kategori_sks,jenis_surat',
            'kode_klasifikasi' => 'nullable|string|max:50',
        ]);

        KategoriSK::create($validated);

        return redirect()->route('kategori-sks.index')
            ->with('success', 'Kategori SK berhasil ditambahkan.');
    }

    public function edit(KategoriSK $kategori_sk)
    {
        return view('kategori_sks.edit', ['kategori' => $kategori_sk]);
    }

    public function update(Request $request, KategoriSK $kategori_sk)
    {
        $validated = $request->validate([
            'jenis_surat' => 'required|string|max:100|unique:kategori_sks,jenis_surat,' . $kategori_sk->id,
            'kode_klasifikasi' => 'nullable|string|max:50',
        ]);

        $kategori_sk->update($validated);

        return redirect()->route('kategori-sks.index')
            ->with('success', 'Kategori SK berhasil diperbarui.');
    }

    public function destroy(KategoriSK $kategori_sk)
    {
        $kategori_sk->delete();

        return redirect()->route('kategori-sks.index')
            ->with('success', 'Kategori SK berhasil dihapus.');
    }
}