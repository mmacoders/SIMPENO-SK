<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sk', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_sk')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('jenis_surat');
            $table->string('kode_klasifikasi');
            $table->date('tanggal_ditetapkan');
            $table->string('pembuat');
            $table->text('perihal')->nullable();
            $table->string('file_pdf')->nullable();
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sk');
    }
};