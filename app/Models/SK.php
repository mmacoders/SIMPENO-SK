<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SK extends Model
{
    use HasFactory;

    protected $table = 'sk';
    
    protected $fillable = [
        'nomor_sk',
        'nomor_sertifikat',
        'jenis_surat',
        'tanggal_ditetapkan',
        'kode_klasifikasi',
        'pejabat_penandatangan',
        'perihal',
        'file_pdf',
        'user_id',
        'kategori_sk_id',
        'jumlah_penerima',
    ];

    protected $casts = [
        'tanggal_ditetapkan' => 'date',
    ];

    /**
     * Relasi: SK milik satu KategoriSk.
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriSk::class, 'kategori_sk_id', 'id');
    }

    /**
     * Relasi: SK milik satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Format panjang tanggal (misal: 14 November 2025).
     */
    public function getTanggalDitetapkanFormattedAttribute()
    {
        return $this->tanggal_ditetapkan
            ? $this->tanggal_ditetapkan->translatedFormat('d F Y')
            : null;
    }

    /**
     * Format pendek tanggal (misal: 14 Nov 2025).
     */
    public function getTanggalDitetapkanShortAttribute()
    {
        return $this->tanggal_ditetapkan
            ? $this->tanggal_ditetapkan->translatedFormat('d M Y')
            : null;
    }
}