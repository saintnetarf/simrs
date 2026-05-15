<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update role enum to include all SIMRS roles
        // First, we need to change the column type since MySQL ENUM changes require table recreation

        if (Schema::hasTable('users')) {
            DB::statement("ALTER TABLE users MODIFY role ENUM('Admin', 'Dokter', 'Apoteker', 'perawat', 'kasir', 'admin_apotik', 'pasien') NOT NULL DEFAULT 'pasien'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            DB::statement("ALTER TABLE users MODIFY role ENUM('Admin', 'Dokter', 'Apoteker') NOT NULL");
        }
    }
};
