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
        Schema::table('klasifikasi_arsips', function (Blueprint $table) {
            $table->foreignId('kategori_sk_id')->nullable()->constrained('kategori_sks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('klasifikasi_arsips', function (Blueprint $table) {
            $table->dropForeign(['kategori_sk_id']);
            $table->dropColumn('kategori_sk_id');
        });
    }
};
