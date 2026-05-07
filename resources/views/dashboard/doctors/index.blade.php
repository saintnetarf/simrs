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
            <form action="{{ route('dashboard.doctors.index') }}" method="GET" class="flex items-stretch h-10">
                <select name="specialist" id="specialist" class="px-3 text-sm rounded-l-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white">
                    <option value="">All Spesialist</option>
                    @foreach ($specialists as $specialist)
                        <option value="{{ $specialist }}" {{ request()->specialist == $specialist ? 'selected' : '' }}>{{ $specialist }}</option>
                    @endforeach
                </select>
                <input type="text" name="search" id="search" class="min-w-[200px] text-sm px-3 border-x-0 border-y border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white" value="{{ request()->search }}" placeholder="Cari...">
                <button type="submit" class="px-4 bg-green-600 hover:bg-green-700 text-white rounded-e-lg text-sm font-medium transition">Cari</button>
            </form>
            <a href="{{ route('dashboard.doctors.create') }}" class="text-sm text-green-600 font-medium hover:underline">Tambah</a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">ID Dokter</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Nama Dokter</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Spesialis</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Nomor HP</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($doctors as $doctor)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $doctor->employee_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $doctor->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $doctor->specialist }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $doctor->phone_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                            <div class="flex gap-x-2">
                                <a href="{{ route('dashboard.doctors.show', $doctor) }}" class="text-sm text-blue-600 font-medium hover:underline">Detail</a>
                                <a href="{{ route('dashboard.doctors.edit', $doctor) }}" class="text-sm text-green-600 font-medium hover:underline">Edit</a>
                                <form action="{{ route('dashboard.doctors.destroy', $doctor) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 font-medium hover:underline">Hapus</button>
                                </form>
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
