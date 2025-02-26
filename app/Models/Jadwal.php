<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'id_user',
        'kategori',
        'judul',
        'alamat',
        'waktu_start',
        'waktu_end',
        'tanggal_start',
        'tanggal_end'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
