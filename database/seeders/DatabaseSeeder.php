<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Treatment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = Carbon::now();
        // 1. Seed Rooms
        Room::insert([
            [
                'code' => 'R-UMUM-01',
                'name' => 'Poli Umum 1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'R-GIGI-01',
                'name' => 'Poli Gigi',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'R-ANAK-01',
                'name' => 'Poli Anak',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'R-DALAM-01',
                'name' => 'Poli Penyakit Dalam',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 2. Seed Users (Admin & Doctors)
        User::insert([
            // Admin
            [
                'employee_id' => 'ADM-001',
                'name' => 'Dr. Sarah Amalia',
                'specialist' => null,
                'phone_number' => '081234567890',
                'role' => 'Admin',
                'room_code' => null,
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Dokter Umum
            [
                'employee_id' => 'DR-UMUM-001',
                'name' => 'Dr. Budi Santoso',
                'specialist' => 'Dokter Umum',
                'phone_number' => '081288889999',
                'role' => 'Dokter',
                'room_code' => 'R-UMUM-01',
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Dokter Spesialis Penyakit Dalam
            [
                'employee_id' => 'DR-DALAM-001',
                'name' => 'Dr. Reza Mahendra, Sp.PD',
                'specialist' => 'Spesialis Penyakit Dalam',
                'phone_number' => '081377776666',
                'role' => 'Dokter',
                'room_code' => 'R-DALAM-01',
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Apoteker
            [
                'employee_id' => 'APO-001',
                'name' => 'Apt. Citra Lestari',
                'specialist' => null,
                'phone_number' => '081288889999',
                'role' => 'Apoteker',
                'room_code' => null,
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 3. Seed Treatments
        Treatment::insert([
            [
                'code' => (string) Str::orderedUuid(),
                'nik' => '1801200199990001',
                'patient_name' => 'Joko Widodo',
                'birth_date' => '1980-05-12',
                'gender' => 'Laki-laki',
                'address' => 'Jl. Merdeka No. 12, Bandar Lampung',
                'phone_number' => '085712341234',
                'emergency_phone_number' => '085712341235',
                'insurance_type' => 'BPJS Kesehatan',
                'bpjs_number' => '0001234567890',
                'complaint_description' => 'Sakit di bagian ulu hati, mual dan muntah sejak 2 hari yang lalu.',
                'doctor_employee_id' => 'DR-DALAM-001',
                'disease' => 'Gastritis',
                'doctor_note' => 'Istirahat cukup. Minum omeprazole.',
                'price' => 150000,
                'status' => 'Selesai',
                'created_at' => $now->subDays(2),
                'updated_at' => $now->subDays(2),
            ],
            [
                'code' => (string) Str::orderedUuid(),
                'nik' => '1801200199990002',
                'patient_name' => 'Siti Aminah',
                'birth_date' => '1995-08-22',
                'gender' => 'Perempuan',
                'address' => 'Jl. Raden Intan No. 45, Bandar Lampung',
                'phone_number' => '089698769876',
                'emergency_phone_number' => '089698769877',
                'insurance_type' => 'Umum',
                'bpjs_number' => null,
                'complaint_description' => 'Demam tinggi dan sakit kepala.',
                'doctor_employee_id' => 'DR-UMUM-001',
                'disease' => null,
                'doctor_note' => null,
                'price' => 0,
                'status' => 'Menunggu Antrian Dokter',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);
    }
}
