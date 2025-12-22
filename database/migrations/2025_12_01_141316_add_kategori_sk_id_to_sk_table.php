<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sk', function (Blueprint $table) {
            // Tambahkan kolom kategori_sk_id
            $table->foreignId('kategori_sk_id')
                  ->nullable()
                  ->after('kode_klasifikasi')
                  ->constrained('kategori_sks') // relasi ke tabel kategori_sks
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sk', function (Blueprint $table) {
            // Hapus foreign key constraint
            $table->dropForeign(['kategori_sk_id']);
            
            // Hapus kolom
            $table->dropColumn('kategori_sk_id');
        });
    }
};