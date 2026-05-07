@extends('dashboard.dashboard')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 space-y-8">

        <!-- 1. Informasi Pasien & Pendaftaran -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-gray-100 pb-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="bg-blue-50 p-2 rounded-full text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Data Pasien & Pendaftaran</h4>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nama Pasien</span>
                        <span class="font-semibold text-gray-900">{{ $treatment->patient_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">NIK</span>
                        <span class="font-semibold text-gray-900">{{ $treatment->nik }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal Lahir</span>
                        <span class="font-semibold text-gray-900">{{
                            Carbon\Carbon::parse($treatment->birth_date)->translatedFormat('d F Y')
                        }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Jenis Kelamin</span>
                        <span class="font-semibold text-gray-900">{{ $treatment->gender }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Alamat</span>
                        <span class="font-semibold text-gray-900 text-right">{{ $treatment->address }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">No. HP</span>
                        <span class="font-semibold text-gray-900">+62 {{ $treatment->phone_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">No. HP Darurat</span>
                        <span class="font-semibold text-gray-900">+62 {{ $treatment->emergency_phone_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Alamat</span>
                        <span class="font-semibold text-gray-900 text-right">{{ $treatment->address }}</span>
                    </div>
                    <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal Pendaftaran</span>
                        <span class="font-semibold text-gray-900">
                            {{ $treatment->created_at->translatedFormat('d F Y H:i')}}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Asuransi</span>
                        <span class="font-semibold text-gray-900">
                            @if($treatment->insurance_type)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ ucfirst($treatment->insurance_type) }}
                                </span>
                            @else
                                <span class="text-gray-400">Umum</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nomor BPJS</span>
                        <span class="font-semibold text-gray-900">{{ $treatment->bpjs_number ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Keluhan</span>
                        <span class="font-semibold text-gray-900 text-right">{{ $treatment->complaint_description ?? '-' }}</span>
                    </div>
                </div>
                </div>
            </div>

            <div class="md:pl-8 border-t md:border-t-0 md:border-l border-gray-100 pt-6 md:pt-0">
                <div class="flex items-center gap-2 mb-4">
                    <div class="bg-green-50 p-2 rounded-full text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Informasi Penanganan</h4>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal Ditangani</span>
                        <span class="font-semibold text-gray-900">
                            {{ $treatment->updated_at->translatedFormat('d F Y H:i')}}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Dokter</span>
                        <span class="font-semibold text-gray-900">{{ $treatment->doctor ? $treatment->doctor->name : '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Ruangan</span>
                        <span class="font-semibold text-gray-900">{{ $treatment->doctor ? $treatment->doctor->room->name : '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Diagnosa Penyakit</span>
                        <span class="font-semibold text-gray-900">{{ $treatment->disease ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Catatan Dokter</span>
                        <span class="font-semibold text-gray-900">{{ $treatment->doctor_note ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total Biaya</span>
                        <span class="font-semibold text-gray-900">Rp. {{ number_format($treatment->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span class="font-semibold text-gray-900">
                            @if($treatment->status === 'Sudah Mendaftar')
                                <span class="px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">Sudah Mendaftar</span>
                            @elseif($treatment->status === 'Menunggu Antrian Dokter')
                                <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">Menunggu Antrian Dokter</span>
                            @elseif($treatment->status === 'Selesai Ditangani')
                                <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Selesai Ditangani</span>
                            @elseif($treatment->status === 'Menunggu Pembayaran')
                                <span class="px-2 py-1 text-xs font-medium text-orange-800 bg-orange-100 rounded-full">Menunggu Pembayaran</span>
                            @elseif($treatment->status === 'Selesai')
                                <span class="px-2 py-1 text-xs font-medium text-purple-800 bg-purple-100 rounded-full">Selesai</span>
                            @endif
                        </span>
                    </div>
                    @can('role', 'Dokter')
                    @if($treatment->status == 'Menunggu Antrian Dokter')
                    <form action="{{ route('dashboard.treatments.doctor-action', $treatment->code) }}" method="POST" class="border rounded-lg border-gray-100 p-4">
                        @csrf
                        @method('PUT')
                        <!-- Diagnosa -->
                        <h3 class="text-center text-lg font-bold text-gray-900 mb-4">Form Menangani Pasien</h3>
                        <div class="mb-3">
                            <label for="disease" class="block text-sm font-medium text-gray-700 mb-2">Diagnosa</label>
                            <input type="text" name="disease" id="disease" class="min-w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white" value="{{ old('disease') }}" placeholder="Contoh: Pilek">
                            @error('disease')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Catatan Dokter -->
                        <div class="mb-3">
                            <label for="doctor_note" class="block text-sm font-medium text-gray-700 mb-2">Catatan Dokter</label>
                            <textarea name="doctor_note" id="doctor_note" class="min-w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white" value="{{ old('doctor_note') }}" placeholder="Contoh: ..."></textarea>
                            @error('doctor_note')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                Tangani
                            </button>
                        </div>
                    </form>
                    @endif
                    @endcan
                    @can('role', 'Apoteker')
                    @if($treatment->status == 'Menunggu Pembayaran')
                    <form action="{{ route('dashboard.treatments.apoteker-action', $treatment->code) }}" method="POST" class="border rounded-lg border-gray-100 p-4">
                        @csrf
                        @method('PUT')
                        <!-- price -->
                        <h3 class="text-center text-lg font-bold text-gray-900 mb-4">Form Menangani Pasien</h3>
                        <div class="mb-3">
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Total Biaya</label>
                            <input type="number" name="price" id="price" class="min-w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white" value="{{ old('price') }}" placeholder="Contoh: 10000">
                            @error('price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                Tangani
                            </button>
                        </div>
                    </form>
                    @endif
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

