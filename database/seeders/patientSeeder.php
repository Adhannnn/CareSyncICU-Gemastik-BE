<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Str;

class patientSeeder extends Seeder
{
    public function run()
    {
        $patients = [
            [
                'name' => 'Budi Santoso',
                'tanggal_lahir' => '1980-05-15',
                'jenis_kelamin' => 'Laki-laki',
                'ruangan' => '101A',
                'dokter_penjaga' => 'Dr. Andi',
                'jadwal_jenguk' => 'Senin, Rabu, Jumat',
                'kondisi' => 'Stabil',
                'foto_pasien' => null,
                'key_access' => Str::random(8),
            ],
            [
                'name' => 'Siti Aminah',
                'tanggal_lahir' => '1975-10-20',
                'jenis_kelamin' => 'Perempuan',
                'ruangan' => '102B',
                'dokter_penjaga' => 'Dr. Budi',
                'jadwal_jenguk' => 'Selasa, Kamis',
                'kondisi' => 'Perlu pengawasan',
                'foto_pasien' => null,
                'key_access' => Str::random(8),
            ],
        ];

        foreach ($patients as $p) {
            Patient::create($p);
        }

        // Buat user penunggu yang sudah terhubung ke pasien pertama (misal)
        User::create([
            'name' => 'Ibu Ani',
            'phone' => '081234567890',
            'role' => 'patient',
            'first_login' => false,
            'patient_id' => Patient::first()->id,
        ]);

        // Buat admin user
        User::create([
            'name' => 'Admin',
            'phone' => 'R1chP3rson',
            'role' => 'admin',
            'first_login' => false,
            'patient_id' => null,
        ]);
    }
}
