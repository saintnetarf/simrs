@extends('dashboard.dashboard')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-900">{{ $title }}</h3>
        <a href="{{ route('dashboard.rooms.index') }}" class="text-sm text-gray-600 hover:underline">Kembali</a>
    </div>

    <form action="{{ route('dashboard.rooms.update', $room->code) }}" method="POST" class="px-6 py-4 space-y-4">
        @csrf
        @method('PUT')
        <!-- Name -->
        <div class="space-y-1">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Ruangan</label>
            <input type="text" name="name" id="name" class="min-w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white" value="{{ old('name', $room->name) }}" placeholder="Contoh: Ruang Mawar">
            @error('name')
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
