<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalisirRequest extends Model
{
    protected $fillable = [
        'user_id',
        'sk_id',
        'keperluan',
        'no_wa',
        'status',
        'file_legalisir',
        'catatan_admin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sk()
    {
        return $this->belongsTo(SK::class, 'sk_id');
    }
}
