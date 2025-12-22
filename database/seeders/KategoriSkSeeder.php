<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriSk;

class KategoriSkSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            ['jenis_surat' => 'Surat Keputusan'],
            ['jenis_surat' => 'Surat Tugas'],
            ['jenis_surat' => 'Surat Perintah'],
            ['jenis_surat' => 'Surat Edaran'],
            ['jenis_surat' => 'Surat Undangan'],
            ['jenis_surat' => 'Pengunduran Diri'],
            ['jenis_surat' => 'Pengangkatan'],
            ['jenis_surat' => 'Pemberhentian'],
        ];

        foreach ($kategori as $item) {
            KategoriSk::firstOrCreate(
                ['jenis_surat' => $item['jenis_surat']],
                $item
            );
        }
    }
}