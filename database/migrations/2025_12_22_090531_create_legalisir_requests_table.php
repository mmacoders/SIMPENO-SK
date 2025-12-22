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
        Schema::create('legalisir_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('sk_id')->constrained('sk')->onDelete('cascade');
            $table->string('keperluan');
            $table->string('no_wa');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('file_legalisir')->nullable(); // Path file hasil legalisir
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legalisir_requests');
    }
};
