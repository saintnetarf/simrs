<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('nik');
            $table->string('patient_name');
            $table->date('birth_date');
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->string('phone_number');
            $table->text('address');
            $table->string('emergency_phone_number');
            $table->enum('insurance_type', ['Umum', 'BPJS Kesehatan', 'BPJS Ketenagakerjaan', 'Asuransi Swasta']);
            $table->string('bpjs_number')->nullable();
            $table->text('complaint_description');
            $table->string('doctor_employee_id')->nullable();
            $table->string('disease')->nullable();
            $table->text('doctor_note')->nullable();
            $table->integer('price')->default(0);
            $table->enum('status', ['Sudah Mendaftar', 'Menunggu Antrian Dokter', 'Selesai Ditangani', 'Menunggu Pembayaran', 'Selesai'])->default('Sudah Mendaftar');
            $table->timestamps();

            $table->foreign('doctor_employee_id')->references('employee_id')->on('users')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
