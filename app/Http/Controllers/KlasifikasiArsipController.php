<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KlasifikasiArsipController extends Controller
{
    /**
     * Search classifications by code or name.
     */
    public function search(\Illuminate\Http\Request $request)
    {
        $query = $request->get('q');
        
        $results = \App\Models\KlasifikasiArsip::where('kode', 'like', "%{$query}%")
            ->orWhere('nama', 'like', "%{$query}%")
            ->orderBy('kode', 'asc')
            ->paginate(10);

        return response()->json($results);
    }
}
