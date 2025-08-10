<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tanggal_lahir',
        'jenis_kelamin',
        'ruangan',
        'dokter_penjaga',
        'jadwal_jenguk',
        'kondisi',
        'foto_pasien',
        'key_access'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
