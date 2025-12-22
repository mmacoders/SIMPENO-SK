<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SK;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SKExport;
use App\Models\KategoriSk;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogger;

class SKController extends Controller
{
    /**
     * Display a listing of the resource for dashboard.
     */
    public function dashboard()
    {
        // Pastikan view dashboard diakses sebagai statistik umum
        $dataSK = SK::with(['user', 'kategori'])
            ->latest()
            ->paginate(10);

        $totalSK    = SK::count();
        $skBulan    = SK::whereMonth('tanggal_ditetapkan', now()->month)->count();
        $skTahun    = SK::whereYear('tanggal_ditetapkan', now()->year)->count();
        $totalSertifikat = SK::where('jenis_surat', 'like', '%sertifikat%')->count();
        $jenis_surat = SK::distinct('jenis_surat')->count('jenis_surat');
        // Ambil kategori untuk filter dropdown
        $kategoriSK = KategoriSk::orderBy('jenis_surat')->get();

        return view('dashboard', compact('dataSK', 'totalSK', 'skBulan', 'skTahun', 'jenis_surat', 'totalSertifikat', 'kategoriSK'));    
    }

    /**
     * Show the form for creating a new resource.
     */
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoriSK = KategoriSk::orderBy('jenis_surat')->get();

        // Gunakan helper baru untuk nomor SK (default tahun sekarang)
        $nextNomor = $this->generateNextNomorSK(date('Y'));
        
        // Ambil nomor sertifikat terakhir
        $lastCertificate = SK::where('nomor_sertifikat', '!=', null)
            ->where('nomor_sertifikat', '!=', '')
            ->where(function($query) {
                $query->where('jenis_surat', 'like', '%sertifikat%')
                      ->orWhere('jenis_surat', 'like', '%Sertifikat%')
                      ->orWhere('jenis_surat', 'like', '%SERTIFIKAT%');
            })
            ->orderBy('created_at', 'desc')
            ->first();
        
        $lastCertificateNumber = null;
        if ($lastCertificate) {
            $lastCertificateNumber = $lastCertificate->nomor_sertifikat;
        }
        
        // Generate nomor sertifikat berikutnya
        $nextCertificateNumber = $this->generateNextCertificateNumber($lastCertificateNumber);
        
        return view('sk.create', compact('nextNomor', 'kategoriSK', 'lastCertificateNumber', 'nextCertificateNumber'));
    }

    /**
     * Helper untuk generate Nomor SK berikutnya
     * Reset per Tahun dan per Kantor User
     */
    private function generateNextNomorSK($year)
    {
        $user = Auth::user();
        $kantor = $user->kantor; // Pastikan kolom kantor ada di tabel users

        $query = SK::whereYear('tanggal_ditetapkan', $year)
                   ->whereHas('user', function($q) use ($kantor) {
                       if (is_null($kantor)) {
                           $q->whereNull('kantor');
                       } else {
                           $q->where('kantor', $kantor);
                       }
                   });

        $last = $query->orderByRaw('CAST(nomor_sk AS UNSIGNED) DESC')->first();

        if (!$last) {
            return '001';
        }

        $lastNumber = intval($last->nomor_sk);
        $nextNumber = $lastNumber + 1;
        
        return str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate next certificate number
     */
    private function generateNextCertificateNumber($lastNumber = null)
    {
        if (!$lastNumber) {
            return 'SERTIFIKAT-001';
        }
        
        // Ekstrak angka dari format SERTIFIKAT-001 atau variasi lainnya
        preg_match('/(\d+)$/', $lastNumber, $matches);
        
        if (isset($matches[1])) {
            $nextNum = (int)$matches[1] + 1;
            return 'SERTIFIKAT-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
        }
        
        // Jika tidak ada pola angka yang cocok, buat nomor baru
        return 'SERTIFIKAT-001';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Validasi input
        $validated = $request->validate([
            'jenis_surat'         => 'required|string|max:100',
            'tanggal_ditetapkan'  => 'required|date',
            'kode_klasifikasi'    => 'required|string|max:50',
            'pejabat_penandatangan' => 'required|string|max:255',
            'perihal'             => 'nullable|string',
            'nomor_sertifikat'    => 'nullable|string|max:100|unique:sk,nomor_sertifikat',
            'jumlah_penerima'     => 'nullable|integer|min:1',
            'file_pdf'            => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Generate Nomor SK berdasarkan Tahun Tanggal Ditetapkan dan Kantor User
        $year = date('Y', strtotime($validated['tanggal_ditetapkan']));
        $nomorSurat = $this->generateNextNomorSK($year);

        // Cari kategori berdasarkan jenis_surat yang dipilih
        $kategori = KategoriSk::where('jenis_surat', $validated['jenis_surat'])->first();
        
        if (!$kategori) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jenis surat tidak valid. Silakan pilih dari daftar yang tersedia.');
        }

        // Upload PDF (opsional)
        $filePath = null;
        if ($request->hasFile('file_pdf')) {
            $filePath = $request->file('file_pdf')->store('uploads/sk', 'public');
        }

        // Otomatis generate nomor sertifikat jika jenis surat adalah sertifikat
        $nomorSertifikat = $validated['nomor_sertifikat'] ?? null;
        if (str_contains(strtolower($validated['jenis_surat']), 'sertifikat') && empty($nomorSertifikat)) {
            // Cari nomor sertifikat terakhir
            $lastCertificate = SK::where('nomor_sertifikat', '!=', null)
                ->where('nomor_sertifikat', '!=', '')
                ->orderBy('created_at', 'desc')
                ->first();
            
            $lastNumber = $lastCertificate ? $lastCertificate->nomor_sertifikat : null;
            $nomorSertifikat = $this->generateNextCertificateNumber($lastNumber);
        }

        // Simpan ke database dengan SEMUA FIELD termasuk kategori_sk_id
        $sk = SK::create([
            'nomor_sk'              => $nomorSurat,
            'nomor_sertifikat'      => $nomorSertifikat,
            'jenis_surat'           => $validated['jenis_surat'],
            'tanggal_ditetapkan'    => $validated['tanggal_ditetapkan'],
            'kode_klasifikasi'      => $validated['kode_klasifikasi'],
            'pejabat_penandatangan' => $validated['pejabat_penandatangan'],
            'perihal'               => $validated['perihal'] ?? null,
            'file_pdf'              => $filePath,
            'user_id'               => $user->id,
            'kategori_sk_id'        => $kategori->id,
            'jumlah_penerima'       => $validated['jumlah_penerima'] ?? null,
        ]);

        ActivityLogger::log('Create SK', "Menambahkan SK baru: {$sk->nomor_sk}");

        return redirect()->route('sk.archive')->with('success', 
            'Surat Keputusan berhasil ditambahkan! '
        );
    }

    /**
     * Display the archive page.
     */
    public function archive()
    {
        $dataSK = SK::with(['user', 'kategori'])
            ->latest()
            ->get();
            
        $totalSK = SK::count();
        $skBulan = SK::whereMonth('tanggal_ditetapkan', now()->month)->count();
        $skTahun = SK::whereYear('tanggal_ditetapkan', now()->year)->count();
        $perihal = SK::distinct('perihal')->count('perihal');
        
        // Ambil data kategori untuk dropdown edit
        $kategoriSK = KategoriSk::orderBy('jenis_surat')->get();

        return view('archive.arsip', compact('dataSK', 'totalSK', 'skBulan', 'skTahun', 'perihal', 'kategoriSK'));
    }

    /**
     * Download PDF file.
     */
    public function download(Request $request)
    {   
        $id = $request->id;
        $sk = SK::find($id);
        
        if (!$sk) {
            return redirect()->back()->with('error', 'Data SK tidak ditemukan');
        }

        if (!$sk->file_pdf) {
            return redirect()->back()->with('error', 'File PDF tidak tersedia');
        }

        $filePath = storage_path("app/public/{$sk->file_pdf}");

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server');
        }

        $safeFileName = str_replace(['/', '\\'], '-', $sk->nomor_sk);
        $fileName = "SK-{$safeFileName}.pdf";

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$fileName.'"',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Validasi dengan nama field dari form
        $validated = $request->validate([
            'id'                  => 'required|exists:sk,id',
            'nomor_sk'            => 'required|string|max:50|unique:sk,nomor_sk,' . $request->id,
            'kode_klasifikasi'    => 'required|string|max:50',
            'jenis_surat'         => 'required|string|max:100',
            'tanggal_ditetapkan'  => 'required|date',
            'pejabat_penandatangan' => 'required|string|max:255',
            'perihal'             => 'nullable|string',
            'nomor_sertifikat'    => 'nullable|string|max:100|unique:sk,nomor_sertifikat,' . $request->id,
            'jumlah_penerima'     => 'nullable|integer|min:1',
            'file_pdf'            => 'nullable|mimes:pdf|max:2048',
        ]);

        $sk = SK::findOrFail($validated['id']);

        // Cari kategori berdasarkan jenis_surat yang dipilih
        $kategori = KategoriSk::where('jenis_surat', $validated['jenis_surat'])->first();
        
        if (!$kategori) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jenis surat tidak valid.');
        }

        // Handle file upload
        if ($request->hasFile('file_pdf')) {
            // Hapus file lama jika ada
            if ($sk->file_pdf && Storage::disk('public')->exists($sk->file_pdf)) {
                Storage::disk('public')->delete($sk->file_pdf);
            }

            // Upload file baru
            $filePath = $request->file('file_pdf')->store('uploads/sk', 'public');
            $sk->file_pdf = $filePath;
        }

        // Update data ke database
        $sk->nomor_sk              = $validated['nomor_sk'];
        $sk->jenis_surat           = $validated['jenis_surat'];
        $sk->kode_klasifikasi      = $validated['kode_klasifikasi'];
        $sk->tanggal_ditetapkan    = $validated['tanggal_ditetapkan'];
        $sk->pejabat_penandatangan = $validated['pejabat_penandatangan'];
        $sk->perihal               = $validated['perihal'] ?? null;
        $sk->nomor_sertifikat      = $validated['nomor_sertifikat'] ?? null;
        $sk->jumlah_penerima       = $validated['jumlah_penerima'] ?? null;
        $sk->kategori_sk_id        = $kategori->id;

        $sk->save();

        ActivityLogger::log('Update SK', "Mengubah data SK: {$sk->nomor_sk}");

        return redirect()->route('sk.archive')->with('success', 
            'Data SK berhasil diperbarui! '
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sk = SK::findOrFail($id);
        
        // Hapus file PDF jika ada
        if ($sk->file_pdf && Storage::disk('public')->exists($sk->file_pdf)) {
            Storage::disk('public')->delete($sk->file_pdf);
        }
        
        $nomor = $sk->nomor_sk;
        $sk->delete();

        ActivityLogger::log('Delete SK', "Menghapus SK: {$nomor}");

        return redirect()->route('sk.archive')->with('success', 'SK berhasil dihapus!');
    }

    /**
     * View PDF in browser
     */
    public function viewPdf($id)
    {
        $sk = SK::findOrFail($id);

        if (!$sk->file_pdf) {
            return redirect()->back()->with('error', 'File PDF tidak ditemukan.');
        }

        $filePath = storage_path("app/public/{$sk->file_pdf}");

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server.');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$sk->nomor_sk.'.pdf"',
        ]);
    }

    /**
     * Export data to Excel
     */
    public function export(Request $request)
    {
        $year  = $request->query('year');
        $month = $request->query('month');
        $q     = $request->query('q');
        $nomor = $request->query('nomor');

        $filename = 'arsip-sk';
        if ($year)  $filename .= "-$year";
        if ($month) $filename .= "-$month";
        if ($q)     $filename .= "-q";
        if ($nomor) $filename .= "-nomor";
        $filename .= '.xlsx';

        return Excel::download(new SKExport($year, $month, $q, $nomor), $filename);
    }
}