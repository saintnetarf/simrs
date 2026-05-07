@extends('dashboard.dashboard')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-900">{{ $title }}</h3>
        <a href="{{ route('dashboard.treatments.index') }}" class="text-sm text-gray-600 hover:underline">Kembali</a>
    </div>

    <form action="{{ route('dashboard.treatments.store') }}" method="POST" class="px-6 py-4 space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- NIK -->
            <div class="space-y-1">
                <label for="nik" class="block text-sm font-medium text-gray-700">NIK</label>
                <input type="text" name="nik" id="nik" class="min-w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white" value="{{ old('nik') }}" placeholder="Contoh: 3571050512345678">
                @error('nik')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <!-- Patient Name -->
            <div class="space-y-1">
                <label for="patient_name" class="block text-sm font-medium text-gray-700">Nama Pasien</label>
                <input type="text" name="patient_name" id="patient_name" class="min-w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white" value="{{ old('patient_name') }}" placeholder="Contoh: Budi Santoso">
                @error('patient_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <!-- Birth Date -->
            <div class="space-y-1">
                <label for="birth_date" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                <input type="date" name="birth_date" id="birth_date" class="min-w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white" value="{{ old('birth_date') }}">
                @error('birth_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Gender -->
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                <div class="flex gap-4">
                    <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 px-4 py-2 rounded-lg border @error('gender') border-red-500 @else border-gray-200 @enderror hover:bg-green-50 transition">
                        <input type="radio" name="gender" value="Laki-laki" required class="text-green-600 focus:ring-green-500" {{ old('gender') == 'Laki-laki' ? 'checked' : '' }}>
                        <span class="text-gray-700">Laki-laki</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 px-4 py-2 rounded-lg border @error('gender') border-red-500 @else border-gray-200 @enderror hover:bg-green-50 transition">
                        <input type="radio" name="gender" value="Perempuan" class="text-green-600 focus:ring-green-500" {{ old('gender') == 'Perempuan' ? 'checked' : '' }}>
                        <span class="text-gray-700">Perempuan</span>
                    </label>
                </div>
                @error('gender')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone Number -->
            <div class="space-y-1">
                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP / WhatsApp</label>
                <div class="flex">
                    <span class="inline-flex items-center px-4 rounded-l-lg border border-r-0 border-gray-300 bg-gray-100 text-gray-500 text-sm font-medium">
                        +62
                    </span>
                    <input type="number" id="phone_number" name="phone_number" required class="flex-1 text-sm min-w-0 block w-full px-4 py-2 rounded-r-lg border @error('phone_number') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-green-500 focus:border-green-500 transition outline-none" placeholder="81234567890" value="{{ old('phone_number') }}">
                </div>
                @error('phone_number')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Emergency Phone Number -->
            <div class="space-y-1">
                <label for="emergency_phone_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP / WhatsApp Darurat</label>
                <div class="flex">
                    <span class="inline-flex items-center px-4 rounded-l-lg border border-r-0 border-gray-300 bg-gray-100 text-gray-500 text-sm font-medium">
                        +62
                    </span>
                    <input type="number" id="emergency_phone_number" name="emergency_phone_number" required class="flex-1 text-sm min-w-0 block w-full px-4 py-2 rounded-r-lg border @error('emergency_phone_number') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-green-500 focus:border-green-500 transition outline-none" placeholder="81234567890" value="{{ old('emergency_phone_number') }}">
                </div>
                @error('emergency_phone_number')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Address -->
            <div class="space-y-1">
                <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="address" id="address" class="min-w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white" placeholder="Contoh: Jl. Contoh No. 123">{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Insurance Selection (Dropdown) -->
            <div class="space-y-1">
                <label for="insurance_type" class="block text-sm font-medium text-gray-700">Jenis Pasien</label>
                <select name="insurance_type" id="insurance_type" class="min-w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white">
                    <option value="">Pilih Asuransi...</option>
                    <option value="Umum" {{ old('insurance_type') == 'Umum' ? 'selected' : '' }}>Umum</option>
                    <option value="BPJS Kesehatan" {{ old('insurance_type') == 'BPJS Kesehatan' ? 'selected' : '' }}>BPJS Kesehatan</option>
                    <option value="BPJS Ketenagakerjaan" {{ old('insurance_type') == 'BPJS Ketenagakerjaan' ? 'selected' : '' }}>BPJS Ketenagakerjaan</option>
                    <option value="Asuransi Swasta" {{ old('insurance_type') == 'Asuransi Swasta' ? 'selected' : '' }}>Asuransi Swasta</option>
                </select>
                @error('insurance_type')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- BPJS Number -->
            <div class="space-y-1">
                <label for="bpjs_number" class="block text-sm font-medium text-gray-700">Nomor BPJS</label>
                <input type="text" name="bpjs_number" id="bpjs_number" class="min-w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white" value="{{ old('bpjs_number') }}" placeholder="Contoh: 1234567890123456">
                @error('bpjs_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Complaint Description -->
            <div class="space-y-1">
                <label for="complaint_description" class="block text-sm font-medium text-gray-700">Keluhan</label>
                <textarea name="complaint_description" id="complaint_description" class="min-w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white" placeholder="Contoh: Sakit kepala">{{ old('complaint_description') }}</textarea>
                @error('complaint_description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Doctor -->
            <div class="space-y-1 md:col-span-2">
                <label for="doctor_employee_id" class="block text-sm font-medium text-gray-700">Dokter</label>
                <select name="doctor_employee_id" id="doctor_employee_id" class="min-w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white">
                    <option value="">Pilih Dokter...</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->employee_id }}" {{ old('doctor_employee_id') == $doctor->employee_id ? 'selected' : '' }}>{{ $doctor->name }}</option>
                    @endforeach
                </select>
                @error('doctor_employee_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="pt-4 flex justify-end gap-2">
            <button type="reset" class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg border border-gray-300 transition">Reset</button>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition shadow-sm">Simpan</button>
        </div>
    </form>
</div>
@endsection
