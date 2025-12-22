<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LegalisirRequest;
use App\Models\SK;
use Illuminate\Support\Facades\Auth;

class LegalisirController extends Controller
{
    public function index()
    {
        $query = LegalisirRequest::with(['user', 'sk'])->latest();

        // Jika bukan pimpinan, hanya tampilkan request milik user yang login
        // 'admin' sekarang tidak melihat daftar request, kecuali kita definisikan.
        // Konsep "Pindahkan" berarti admin tidak lagi handle.
        // Jika admin juga tidak boleh lihat, maka logic ini benar (admin akan kena filter default 'else' user biasa?
        // Tapi admin biasanya role != pimpinan, jadi masuk sini.
        // Masalahnya: jika role === 'admin', dia juga masuk ke filter 'user_id' = Auth::id().
        // Karena admin bukan pimpinan. 
        // Ini sesuai request: "fitur ... pindahkan ke pimpinan".
        if (Auth::user()->role !== 'pimpinan') {
            $query->where('user_id', Auth::id());
        }

        $requests = $query->paginate(10);
        return view('legalisir.index', compact('requests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sk_id' => 'required|exists:sk,id',
            'keperluan' => 'required|string|max:255',
            'no_wa' => 'required|string|max:20', // Format: 08xx atau 62xx
        ]);

        // Cek duplicate pending request
        $exists = LegalisirRequest::where('user_id', Auth::id())
            ->where('sk_id', $request->sk_id)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda masih memiliki permohonan legalisir yang sedang diproses untuk SK ini.');
        }

        LegalisirRequest::create([
            'user_id' => Auth::id(),
            'sk_id' => $request->sk_id,
            'keperluan' => $request->keperluan,
            'no_wa' => $request->no_wa,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Permohonan legalisir berhasil dikirim. Harap tunggu notifikasi via WhatsApp.');
    }

    public function updateStatus(Request $request, $id)
    {
        // Hanya Pimpinan yang boleh update status
        if (Auth::user()->role !== 'pimpinan') {
            abort(403);
        }

        $legalisir = LegalisirRequest::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan_admin' => 'nullable|string'
        ]);

        $legalisir->status = $request->status;
        $legalisir->catatan_admin = $request->catatan_admin;

        // Jika diapprove, idealnya di sini kita generate PDF baru yang ada cap legalisirnya
        // $legalisir->file_legalisir = $generatedPdfPath; 

        $legalisir->save();

        // Kirim Notifikasi WA
        $this->sendWhatsAppNotification($legalisir);

        return back()->with('success', 'Status permohonan diperbarui dan notifikasi dikirim.');
    }

    private function sendWhatsAppNotification($legalisir)
    {
        // LOGIC WA GATEWAY DISINI
        // Contoh Pseudo-code integrasi Fonnte/Wablas:
        
        $nomor = $legalisir->no_wa;
        $status = ucfirst($legalisir->status);
        $skNomor = $legalisir->sk->nomor_sk;
        
        $message = "Halo " . $legalisir->user->name . ",\n\n";
        $message .= "Permohonan legalisir SK Anda (No: $skNomor) telah diperbarui menjadi: *$status*.\n";
        
        if ($legalisir->status == 'approved') {
            $message .= "Silakan unduh dokumen Anda di dashboard aplikasi kami.\n";
        } elseif ($legalisir->status == 'rejected') {
            $message .= "Catatan: " . $legalisir->catatan_admin . "\n";
        }
        
        $message .= "\nTerima kasih.";

        // TODO: Kirim $message ke $nomor via CURL API
        // \Log::info("WA dikirim ke $nomor: $message");
        
        return true;
    }
}
