<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataCPB extends Model
{
    use HasFactory;

    protected $table = "data_cpb";

    protected $fillable = [
        'nama',
        'alamat',
        'nik',
        'no_kk',
        'pekerjaan',
        'email',
        'foto_rumah',
        'koordinat',
        'status',
        'pengecekan'
    ];

    public function verifikasi()
    {
        return $this->hasOne(DataVerifikasiCPB::class, 'nik', 'nik');
    }
}
