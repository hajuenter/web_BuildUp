<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_Verifikasi_CPB extends Model
{
    use HasFactory;

    protected $table = "data_verifikasi_cpb";

    protected $fillable = [
        'nik',
        'penutup_atap',
        'rangka_atap',
        'kolom',
        'ring_balok',
        'dinding_pengisi',
        'kusen',
        'pintu',
        'jendela',
        'struktur_bawah',
        'penutup_lantai',
        'pondasi',
        'sloof',
        'mck',
        'air_kotor',
        'kesanggupan_berswadaya',
        'tipe'
    ];

    public function cpb()
    {
        return $this->belongsTo(Data_CPB::class, 'nik', 'nik');
    }
}
