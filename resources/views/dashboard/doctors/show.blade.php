@extends('dashboard.dashboard')

@section('content')

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Sukses!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-900">{{ $title }}</h3>
        <div class="flex items-center gap-2">
            <form action="{{ route('dashboard.doctors.show', $doctor) }}" method="GET" class="flex items-stretch h-10">
                <select name="status" id="status" class="px-3 text-sm rounded-l-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white">
                    <option value="">All Status</option>
                    <option value="{{ 'Sudah Mendaftar' }}" {{ $statusSelected == 'Sudah Mendaftar' ? 'selected' : '' }}>Sudah Mendaftar</option>
                    <option value="{{ 'Menunggu Antrian Dokter' }}" {{ $statusSelected == 'Menunggu Antrian Dokter' ? 'selected' : '' }}>Menunggu Antrian Dokter</option>
                    <option value="{{ 'Selesai Ditangani' }}" {{ $statusSelected == 'Selesai Ditangani' ? 'selected' : '' }}>Selesai Ditangani</option>
                    <option value="{{ 'Menunggu Pembayaran' }}" {{ $statusSelected == 'Menunggu Pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="{{ 'Selesai' }}" {{ $statusSelected == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                <input type="text" name="search" id="search" class="min-w-[200px] text-sm px-3 border-x-0 border-y border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white" value="{{ request()->search }}" placeholder="Cari...">
                <button type="submit" class="px-4 bg-green-600 hover:bg-green-700 text-white rounded-e-lg text-sm font-medium transition">Cari</button>
            </form>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Tanggal Lahir</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Nomor HP</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Jenis Pasien</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($treatments as $treatment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $treatment->nik }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $treatment->patient_name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $treatment->birth_date->translatedFormat('d F Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $treatment->gender }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $treatment->phone_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $treatment->insurance_type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">
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
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                            <div class="flex gap-x-2">
                                <a href="{{ route('dashboard.treatments.show', $treatment) }}" class="text-sm text-blue-600 font-medium hover:underline">Detail</a>
                                @can('role', 'Admin')
                                @if($treatment->status == 'Menunggu Antrian Dokter' || $treatment->status == 'Sudah Mendaftar')
                                    <a href="{{ route('dashboard.treatments.edit', $treatment) }}" class="text-sm text-green-600 font-medium hover:underline">Edit</a>
                                    <form action="{{ route('dashboard.treatments.destroy', $treatment) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 font-medium hover:underline">Hapus</button>
                                    </form>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700" colspan="4">Data Dokter tidak ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
