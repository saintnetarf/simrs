<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Riwayat Berobat - RS Cepat Sembuh</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-poppins text-gray-700 antialiased bg-gray-50 flex flex-col min-h-screen">

    <!-- NAVBAR -->
    <nav class="bg-white shadow-sm fixed w-full z-50 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <div class="bg-green-600 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <span class="font-bold text-2xl text-gray-800 tracking-tight">RS <span class="text-green-600">Cepat Sembuh</span></span>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-gray-600 hover:text-green-600 font-medium transition">Home</a>
                    <a href="/#layanan" class="text-gray-600 hover:text-green-600 font-medium transition">Layanan</a>
                    <a href="/#tentang" class="text-gray-600 hover:text-green-600 font-medium transition">Tentang Kami</a>
                    <a href="/#kontak" class="text-gray-600 hover:text-green-600 font-medium transition">Kontak</a>
                    <span class="text-green-600 font-bold border-b-2 border-green-600 pb-1">Riwayat Berobat</span>
                    <a href="/booking" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-full font-medium transition shadow-lg shadow-green-200">Daftar Online</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button id="mobile-menu-btn" class="text-gray-600 hover:text-green-600 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 px-4 pt-2 pb-4 space-y-2 shadow-lg">
            <a href="/" class="block px-3 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-md font-medium">Home</a>
            <a href="/#layanan" class="block px-3 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-md font-medium">Layanan</a>
            <a href="/#tentang" class="block px-3 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-md font-medium">Tentang Kami</a>
            <a href="/#kontak" class="block px-3 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-md font-medium">Kontak</a>
            <a href="/history" class="block px-3 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-md font-medium">Riwayat Berobat</a>
            <a href="/booking" class="block w-full text-center mt-4 bg-green-600 text-white px-4 py-2 rounded-lg font-medium">Daftar Online</a>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="flex-grow pt-28 pb-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">

            <!-- 1. HEADER & SEARCH SECTION -->
            <div class="text-center mb-10">
                <form action="/history" method="GET">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Cek Riwayat Berobat</h1>
                    <p class="text-gray-500 mb-8">Masukkan NIK Anda untuk melihat riwayat kunjungan medis.</p>

                    <div class="max-w-md mx-auto relative">
                        <input type="number" name="nik" value="{{ request('nik') }}" class="w-full p-3 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-0 outline-none text-sm md:text-lg transition" placeholder="Masukkan NIK">
                        <button type="submit" class="absolute right-2 top-2 bottom-2 bg-green-600 hover:bg-green-700 text-white px-6 rounded-lg font-medium transition">
                            Cari
                        </button>
                    </div>
                </form>
                @if(request('nik') && (!isset($treatments) || $treatments->isEmpty()))
                <p id="error-msg" class="text-red-500 text-sm mt-2">NIK tidak ditemukan dalam database kami atau belum memiliki riwayat berobat.</p>
                @endif
            </div>

            @if(isset($treatments) && $treatments->isNotEmpty())
            @php $firstTreatment = $treatments->first(); @endphp
            <div class="space-y-8">

                <!-- 2. KARTU PASIEN (SUMMARY) -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row items-center gap-6">
                    <div class="bg-green-50 p-4 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="text-center md:text-left flex-1">
                        <h2 class="text-xl font-bold text-gray-900">{{ $firstTreatment->patient_name }}</h2>
                        <p class="text-sm text-gray-500">NIK: {{ $firstTreatment->nik }}</p>
                        <div class="mt-2 flex justify-center md:justify-start gap-2">
                             <span class="px-2 py-0.5 rounded text-xs bg-blue-50 text-blue-600 font-medium">{{ $firstTreatment->insurance_type }}</span>
                             <span class="px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-600 font-medium">{{ $firstTreatment->gender }}</span>
                        </div>
                    </div>
                    <a href="{{ route('treatments.print', ['nik' => request('nik')]) }}" target="_blank" class="w-full md:w-auto border border-green-600 text-green-600 hover:bg-green-50 px-4 py-2 rounded-lg text-sm font-medium transition flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Riwayat
                    </a>
                </div>

                <!-- 3. LIST ACCORDION HISTORY -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-800 px-2">Daftar Kunjungan</h3>

                    @foreach($treatments as $treatment)
                    <!-- ACCORDION ITEM -->
                    <details class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 open:shadow-md open:border-green-200">
                        <summary class="flex items-center justify-between p-5 cursor-pointer select-none hover:bg-gray-50 transition-colors list-none">
                            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-6 w-full">
                                <!-- Kiri: Tanggal & Status -->
                                <div class="flex items-start justify-between gap-4 min-w-max">
                                    <div class="text-left">
                                        <p class="text-xs text-gray-400 font-semibold uppercase">Tanggal</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $treatment->updated_at->translatedFormat('d F Y') }}</p>
                                    </div>
                                    <div class="h-8 w-px bg-gray-200 hidden md:block"></div>
                                    <span class="inline-flex md:hidden items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $treatment->status == 'Selesai' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $treatment->status }}
                                    </span>
                                </div>

                                <!-- Tengah: Info Dokter -->
                                <div class="flex-1 md:ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ optional($treatment->doctor)->name ?? 'Belum Ditentukan' }}</p>
                                    <p class="text-sm text-gray-500">{{ optional($treatment->doctor)->specialist ?? '-' }}</p>
                                </div>

                                <div class="h-8 w-px bg-gray-200 hidden md:block"></div>
                                <span class="hidden md:inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $treatment->status == 'Selesai' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $treatment->status }}
                                </span>

                                <!-- Kanan: Chevron Icon -->
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300 group-open:rotate-180 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </summary>

                        <!-- Accordion Content (Animated) -->
                        <div class="grid grid-rows-[0fr] transition-all duration-300 group-open:grid-rows-[1fr]">
                            <div class="overflow-hidden border-t border-gray-100 bg-gray-50/50">
                                <div class="p-6 space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">Jenis Pasien</label>
                                            <p class="text-sm text-gray-800">{{ $treatment->insurance_type ?? '-' }}</p>
                                        </div>
                                        @if( $treatment->insurance_type == 'BPJS Kesehatan' || $treatment->insurance_type == 'BPJS Ketenagakerjaan' )
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">Nomor BPJS</label>
                                            <p class="text-sm text-gray-800">{{ $treatment->bpjs_number ?? '-' }}</p>
                                        </div>
                                        @endif
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">Keluhan</label>
                                            <p class="text-sm text-gray-800">{{ $treatment->complaint_description ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">Dokter</label>
                                            <p class="text-sm text-gray-800">{{ optional($treatment->doctor)->name ?? 'Belum Ditentukan' }} ({{ optional($treatment->doctor)->specialist ?? '-' }})</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">Ruangan</label>
                                            <p class="text-sm text-gray-800">{{ optional(optional($treatment->doctor)->room)->name ?? 'Belum Ditentukan' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">Diagnosa</label>
                                            <p class="text-sm text-gray-800">{{ $treatment->disease ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">Total Biaya</label>
                                            <p class="text-sm text-gray-800">Rp {{ number_format($treatment->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>

                                    @if($treatment->doctor_note)
                                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100">
                                        <div class="flex items-start gap-3">
                                            <div class="text-yellow-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-yellow-800 uppercase mb-1">Catatan Dokter (Untuk Apoteker)</p>
                                                <p class="text-sm text-yellow-800 leading-relaxed">{{ $treatment->doctor_note }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </details>
                    @endforeach

                </div>

                <!-- 4. PAGINATION -->
                {{ $treatments->links('vendor.pagination.history') }}

            </div>
            @endif
            <!-- END SEARCH RESULT -->

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-gray-300 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm">
            <p>&copy; {{ date('Y') }} RS Cepat Sembuh Lampung. All rights reserved.</p>
        </div>
    </footer>

    <!-- Simple Script for Mobile Menu -->
    <script>
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        if(btn && menu) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
