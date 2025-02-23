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
        'jenis_kelamin',
        'umur',
        'nik',
        'no_kk',
        'alamat'
    ];

    public function verifikasi()
    {
        return $this->hasOne(DataVerifikasiCPB::class, 'nik', 'nik');
    }
}
