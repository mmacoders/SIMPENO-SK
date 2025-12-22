<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sk', function (Blueprint $table) {
            $table->string('nomor_sertifikat')->nullable()->after('jenis_surat');
        });
    }

    public function down()
    {
        Schema::table('sk', function (Blueprint $table) {
            $table->dropColumn('nomor_sertifikat');
        });
    }
};