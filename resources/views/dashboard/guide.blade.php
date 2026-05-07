@extends('dashboard.dashboard')

@section('content')
<!-- JUDUL HALAMAN -->
<div class="flex justify-between items-end border-b border-gray-200 pb-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Showcase Komponen UI</h1>
        <p class="text-sm text-gray-500 mt-1">Template ini berisi contoh elemen yang dapat Anda gunakan di halaman lain.</p>
    </div>
    <div class="hidden sm:block">
        <button class="bg-green-50 text-green-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-100 transition">Download Guide</button>
    </div>
</div>

<!-- A. TYPOGRAPHY -->
<section class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
        <span class="w-1 h-6 bg-green-600 rounded-full"></span>
        Typography
    </h2>
    <div class="space-y-4">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Heading 1 - RS Cepat Sembuh</h1>
        </div>
        <div>
            <h2 class="text-3xl font-semibold text-gray-800">Heading 2 - Sub Judul Halaman</h2>
        </div>
        <div>
            <h3 class="text-2xl font-medium text-gray-800">Heading 3 - Nama Pasien</h3>
        </div>
        <p class="text-base text-gray-600 leading-relaxed">
            Ini adalah paragraf <span class="font-bold text-gray-900">bold</span>, <span class="italic">italic</span>, dan <span class="underline">underline</span>.
            Rumah Sakit Cepat Sembuh adalah pilihan terbaik untuk kesehatan keluarga Anda.
        </p>
        <p class="text-sm text-gray-500">Text kecil untuk keterangan tambahan (small text).</p>
    </div>
</section>

<!-- B. BADGES & BUTTONS -->
<section class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Badges -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Badges & Status</h2>
        <div class="flex flex-wrap gap-3">
            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Umum</span>
            <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">BPJS</span>
            <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Proses</span>
            <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Darurat</span>
            <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Draft</span>
        </div>
    </div>

    <!-- Buttons -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Buttons</h2>
        <div class="flex flex-wrap gap-3">
            <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">Primary</button>
            <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg text-sm font-medium transition">Secondary</button>
            <button class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium transition">Danger</button>
            <button class="px-4 py-2 text-green-600 hover:bg-green-50 rounded-lg text-sm font-medium transition">Text Only</button>
        </div>
    </div>
</section>

<!-- C. CARDS & TABLES -->
<section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Simple Card -->
    <div class="lg:col-span-1 space-y-4">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Total Pasien</p>
                    <h3 class="text-2xl font-bold text-gray-900">1,240</h3>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-green-50 rounded-xl text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Sembuh Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-900">45</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-900">Antrian Terbaru</h3>
            <button class="text-xs text-green-600 font-medium hover:underline">Lihat Semua</button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">No. RM</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Poli</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">RM-001</td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">Budi Santoso</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">Penyakit Dalam</td>
                        <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span></td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">RM-002</td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">Siti Aminah</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">Anak</td>
                        <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Periksa</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- D. CARD HALAMAN AKUN (PROFILE CARD) -->
<section>
    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
        <span class="w-1 h-6 bg-green-600 rounded-full"></span>
        Card Halaman Akun
    </h2>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="md:flex">
            <!-- Kiri: Foto Profil -->
            <div class="md:w-1/3 bg-gray-50 p-8 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-gray-100">
                <img src="https://i.pravatar.cc/300?img=32" alt="Profile" class="h-32 w-32 rounded-full border-4 border-white shadow-lg mb-4 object-cover">
                <h3 class="text-xl font-bold text-gray-900">Dr. Reza Mahendra</h3>
                <p class="text-sm text-green-600 font-medium">Spesialis Jantung</p>
                <div class="mt-6 w-full space-y-3">
                    <button class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Ganti Foto
                    </button>
                    <button class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-50 text-red-600 rounded-lg text-sm font-medium hover:bg-red-100 transition">
                        Hapus Akun
                    </button>
                </div>
            </div>
            <!-- Kanan: Detail Info -->
            <div class="md:w-2/3 p-8">
                <h4 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-100 pb-2">Informasi Pribadi</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">ID Dokter</label>
                        <p class="text-gray-900 font-medium">DR-2024-009</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Email</label>
                        <p class="text-gray-900 font-medium">reza.dokter@rscepatsembuh.co.id</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">No. Telepon</label>
                        <p class="text-gray-900 font-medium">+62 812 3456 7890</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- E. FORM COMPONENTS (Input, Select, Radio, Checkbox, File) -->
<section>
    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
        <span class="w-1 h-6 bg-green-600 rounded-full"></span>
        Form Elements
    </h2>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <form action="#" class="space-y-6">
            <!-- Row 1: Text & Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" placeholder="Masukkan nama...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" placeholder="******">
                </div>
            </div>

            <!-- Row 2: Select & Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jadwal Praktek</label>
                    <select class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white">
                        <option>Senin - Rabu</option>
                        <option>Kamis - Sabtu</option>
                        <option>Minggu (Libur)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">
                </div>
            </div>

            <!-- Row 3: Radio & Checkbox -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin (Radio)</label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="gender" class="form-radio text-green-600 h-4 w-4" checked>
                            <span class="ml-2 text-gray-700">Laki-laki</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="gender" class="form-radio text-green-600 h-4 w-4">
                            <span class="ml-2 text-gray-700">Perempuan</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Poli Layanan (Checkbox)</label>
                    <div class="flex flex-wrap gap-3">
                        <label class="inline-flex items-center bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200 cursor-pointer hover:bg-green-50">
                            <input type="checkbox" class="form-checkbox text-green-600 rounded h-4 w-4">
                            <span class="ml-2 text-gray-700 text-sm">Umum</span>
                        </label>
                        <label class="inline-flex items-center bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200 cursor-pointer hover:bg-green-50">
                            <input type="checkbox" class="form-checkbox text-green-600 rounded h-4 w-4">
                            <span class="ml-2 text-gray-700 text-sm">Gigi</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Row 4: Textarea -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                <textarea rows="3" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" placeholder="Tulis catatan disini..."></textarea>
            </div>

            <!-- Row 5: File Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Dokumen Pendukung</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition cursor-pointer">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <span class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                <span>Upload a file</span>
                            </span>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PDF, JPG up to 10MB</p>
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button type="button" class="w-full sm:w-auto px-6 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</section>
@endsection
