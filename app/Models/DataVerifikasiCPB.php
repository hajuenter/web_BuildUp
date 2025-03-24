<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataVerifikasiCPB extends Model
{
    use HasFactory;

    protected $table = "data_verifikasi_cpb";

    protected $fillable = [
        'foto_kk',
        'foto_ktp',
        'nik',
        'penutup_atap',
        'foto_penutup_atap',
        'rangka_atap',
        'foto_rangka_atap',
        'kolom',
        'foto_kolom',
        'ring_balok',
        'foto_ring_balok',
        'dinding_pengisi',
        'foto_dinding_pengisi',
        'kusen',
        'foto_kusen',
        'pintu',
        'foto_pintu',
        'jendela',
        'foto_jendela',
        'struktur_bawah',
        'foto_struktur_bawah',
        'penutup_lantai',
        'foto_penutup_lantai',
        'pondasi',
        'foto_pondasi',
        'sloof',
        'foto_sloof',
        'mck',
        'foto_mck',
        'air_kotor',
        'foto_air_kotor',
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
