<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KlasifikasiArsip;
use Illuminate\Support\Facades\File;

class KlasifikasiArsipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = 'd:\Priv\SK\klasifikasi arsip.txt';

        if (!File::exists($filePath)) {
            $this->command->error("File not found: $filePath");
            return;
        }

        $content = File::get($filePath);
        $lines = explode("\n", $content);

        $currentCode = null;
        $currentNama = '';

        foreach ($lines as $line) {
            $line = trim($line);
            
            if (empty($line)) continue;

            // Check if line starts with a code pattern (letters/dots followed by |)
            // Regex: Start with chars, digits, dots, spaces, then |
            if (preg_match('/^([A-Z0-9\.]+)\s*\|\s*(.*)$/', $line, $matches)) {
                
                // Save previous record if exists
                if ($currentCode) {
                    KlasifikasiArsip::updateOrCreate(
                        ['kode' => $currentCode],
                        ['nama' => trim($currentNama), 'uraian' => trim($currentNama)]
                    );
                }

                // Start new record
                $currentCode = trim($matches[1]);
                $currentNama = trim($matches[2]);
            } else {
                // Continuation of previous description
                if ($currentCode) {
                    $currentNama .= ' ' . $line;
                }
            }
        }

        // Save the last record
        if ($currentCode) {
            KlasifikasiArsip::updateOrCreate(
                ['kode' => $currentCode],
                ['nama' => trim($currentNama), 'uraian' => trim($currentNama)]
            );
        }
    }
}
