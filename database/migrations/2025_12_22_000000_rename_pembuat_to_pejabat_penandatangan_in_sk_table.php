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
            $table->renameColumn('pembuat', 'pejabat_penandatangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sk', function (Blueprint $table) {
            $table->renameColumn('pejabat_penandatangan', 'pembuat');
        });
    }
};
