@extends('dashboard.dashboard')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-900">{{ $title }}</h3>
        <a href="{{ route('dashboard.doctors.index') }}" class="text-sm text-gray-600 hover:underline">Kembali</a>
    </div>

    <form action="{{ route('dashboard.doctors.update', $doctor->employee_id) }}" method="POST" class="px-6 py-4 space-y-4">
        @csrf
        @method('PUT')

        <!-- Employee ID -->
        <div class="space-y-1">
            <label for="employee_id" class="block text-sm font-medium text-gray-700">ID Dokter</label>
            <input type="text" name="employee_id" id="employee_id" class="min-w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white" value="{{ old('employee_id', $doctor->employee_id) }}" placeholder="Contoh: DOK-001">
            @error('employee_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Name -->
        <div class="space-y-1">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Dokter</label>
            <input type="text" name="name" id="name" class="min-w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white" value="{{ old('name', $doctor->name) }}" placeholder="Contoh: Dr. Budi Santoso">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-1">
            <label for="specialist" class="block text-sm font-medium text-gray-700">Spesialis</label>
            <input type="text" name="specialist" id="specialist" class="min-w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white" value="{{ old('specialist', $doctor->specialist) }}" placeholder="Contoh: Dr. Budi Santoso">
            @error('specialist')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Phone Number -->
        <div class="space-y-1">
            <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
            <input type="text" name="phone_number" id="phone_number" class="min-w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white" value="{{ old('phone_number', $doctor->phone_number) }}" placeholder="Contoh: 08123456789">
            @error('phone_number')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-1">
            <label for="room_code" class="block text-sm font-medium text-gray-700">Ruang</label>
            <select name="room_code" id="room_code" class="min-w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white">
                <option value="">Pilih Ruangan</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->code }}" {{ old('room_code', $doctor->room_code) == $room->code ? 'selected' : '' }}>{{ $room->code . ' - ' . $room->name }}</option>
                @endforeach
            </select>
            @error('room_code')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-4 flex justify-end gap-2">
            <button type="reset" class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg border border-gray-300 transition">Reset</button>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition shadow-sm">Simpan</button>
        </div>
    </form>
</div>
@endsection
