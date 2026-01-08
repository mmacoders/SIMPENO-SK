<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlasifikasiArsip extends Model
{
    protected $fillable = [
        'kode', 
        'nama', 
        'uraian'
    ];
}
