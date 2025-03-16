<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataVerifikasiCPB extends Model
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
        'tipe',
        'penilaian_kerusakan',
        'nilai_bantuan',
        'catatan'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($verifikasi) {
            
            DataCPB::where('nik', $verifikasi->nik)->update([
                'pengecekan' => 'Sudah Dicek',
            ]);

            if ($verifikasi->nilai_bantuan > 0) {
                DataCPB::where('nik', $verifikasi->nik)->update([
                    'status' => 'Terverifikasi',
                ]);
            }
        });
    }

    public function cpb()
    {
        return $this->belongsTo(DataCPB::class, 'nik', 'nik');
    }
}
