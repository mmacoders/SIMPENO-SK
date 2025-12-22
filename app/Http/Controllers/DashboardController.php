<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // // Redirect admin ke users index
        //     if (Auth::users()->role === 'admin') {
        //         return redirect()->route('users.index'); // atau 'users' sesuai route name Anda
        //     }
        // dd('Auth');
        // Data dummy untuk ditampilkan di dashboard
        $dataSK = [
            ['nomor' => '001/SK-UNG/KP/2025', 'judul' => 'dsfdsfg', 'kategori' => 'Kepegawaian', 'tanggal' => '24 Okt 2025'],
            ['nomor' => '001/SK-UNG/KU/2025', 'judul' => 'lk pelstihan', 'kategori' => 'Keuangan', 'tanggal' => '23 Okt 2025'],
        ];

        return view('dashboard', [
            'totalSK' => count($dataSK),
            'skBulan' => 2,
            'skTahun' => 2,
            'kategori' => 5,
            'dataSK' => $dataSK,
        ]);
    }
}