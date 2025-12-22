<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriSk extends Model
{
    use HasFactory;

    protected $table = 'kategori_sks';

    protected $fillable = [
        'jenis_surat',
        'kode_klasifikasi',
    ];

    /**
     * Relasi: satu kategori punya banyak SK.
     */
    public function sk()
    {
        // foreign key di tabel sk = kategori_sk_id
        return $this->hasMany(SK::class, 'kategori_sk_id', 'id');
    }
}