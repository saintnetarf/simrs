<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'employee_id' => 'EMP_ADMIN_001',
                'name' => 'Administrator SIMRS',
                'full_name' => 'Administrator SIMRS',
                'password' => Hash::make('admin123'),
                'password_hash' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Kasir user
        User::updateOrCreate(
            ['username' => 'kasir001'],
            [
                'employee_id' => 'EMP_KASIR_001',
                'name' => 'Budi Kasir',
                'full_name' => 'Budi Kasir',
                'password' => Hash::make('kasir123'),
                'password_hash' => Hash::make('kasir123'),
                'role' => 'kasir',
                'is_active' => true,
            ]
        );

        // Dokter user
        User::updateOrCreate(
            ['username' => 'dokter001'],
            [
                'employee_id' => 'EMP_DOKTER_001',
                'name' => 'Dr. Ahmad Wijaya',
                'full_name' => 'Dr. Ahmad Wijaya',
                'password' => Hash::make('dokter123'),
                'password_hash' => Hash::make('dokter123'),
                'role' => 'dokter',
                'is_active' => true,
            ]
        );

        // Perawat user
        User::updateOrCreate(
            ['username' => 'perawat001'],
            [
                'employee_id' => 'EMP_PERAWAT_001',
                'name' => 'Siti Perawat',
                'full_name' => 'Siti Perawat',
                'password' => Hash::make('perawat123'),
                'password_hash' => Hash::make('perawat123'),
                'role' => 'perawat',
                'is_active' => true,
            ]
        );

        // Admin Apotek user
        User::updateOrCreate(
            ['username' => 'admin_apotek001'],
            [
                'employee_id' => 'EMP_APOTEK_001',
                'name' => 'Rudi Admin Apotek',
                'full_name' => 'Rudi Admin Apotek',
                'password' => Hash::make('apotek123'),
                'password_hash' => Hash::make('apotek123'),
                'role' => 'admin_apotik',
                'is_active' => true,
            ]
        );

        // Pasien user
        User::updateOrCreate(
            ['username' => 'pasien001'],
            [
                'employee_id' => 'EMP_PASIEN_001',
                'name' => 'Jono Pasien',
                'full_name' => 'Jono Pasien',
                'password' => Hash::make('pasien123'),
                'password_hash' => Hash::make('pasien123'),
                'role' => 'pasien',
                'is_active' => true,
            ]
        );
    }
}
