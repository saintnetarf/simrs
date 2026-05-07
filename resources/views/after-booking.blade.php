<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Online - RS Cepat Sembuh</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-poppins text-gray-700 antialiased bg-gray-50 flex flex-col min-h-screen">

    <!-- NAVBAR (Sama dengan Landing Page) -->
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
                    <a href="/history" class="text-gray-600 hover:text-green-600 font-medium transition">Riwayat Berobat</a>
                    <span class="text-green-600 font-bold border-b-2 border-green-600 pb-1">Pendaftaran</span>
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
            <span class="px-3 py-2 text-green-600 font-bold border-b-2 border-green-600 pb-1">Pendaftaran</span>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="flex-grow pt-28 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if( $isSuccess )
            <section>
                <div class="bg-white rounded-3xl shadow-lg border border-green-100 overflow-hidden">
                    <!-- Top Banner (Hijau) -->
                    <div class="bg-green-600 h-32 flex items-center justify-center relative">
                        <!-- Decorative Circle -->
                        <div class="absolute -bottom-12 bg-white rounded-full p-4 shadow-lg">
                            <div class="bg-green-100 rounded-full p-4 text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="pt-16 pb-10 px-6 sm:px-10 text-center">
                        <h2 class="text-xl md:text-3xl font-bold text-gray-900 mb-2">Pendaftaran Berhasil!</h2>
                        <p class="text-sm md:text-md text-gray-500 mb-8">Silakan tunggu di poliklinik untuk diarahkan ke dokter yang bersangkutan. Jangan lupa membawa KTP atau kartu berobat.</p>

                        <!-- Ticket Card -->
                        <div class="bg-green-50 rounded-2xl p-6 border-2 border-dashed border-green-200 mb-8 max-w-sm mx-auto relative">
                            <!-- Punched Hole Effect -->
                            <div class="absolute -left-3 top-1/2 w-6 h-6 bg-gray-50 rounded-full transform -translate-y-1/2"></div>
                            <div class="absolute -right-3 top-1/2 w-6 h-6 bg-gray-50 rounded-full transform -translate-y-1/2"></div>

                            <p class="text-xs font-semibold text-green-700 uppercase tracking-widest mb-1">Detail Pengajuan</p>

                            <div class="w-full border-b border-green-200 my-4"></div>

                            <div class="space-y-2 text-left text-sm text-gray-700">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Nama Pasien</span>
                                    <span class="font-medium">{{ $treatment->patient_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">NIK</span>
                                    <span class="font-medium">{{ $treatment->nik }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Tanggal Lahir</span>
                                    <span class="font-medium">{{ $treatment->birth_date->translatedFormat('d F Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Jenis Kelamin</span>
                                    <span class="font-medium">{{ $treatment->gender }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Nomor Telepon</span>
                                    <span class="font-medium">{{ $treatment->phone_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Nomor Telepon Darurat</span>
                                    <span class="font-medium">{{ $treatment->emergency_phone_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Alamat</span>
                                    <span class="font-medium">{{ $treatment->address }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Jenis Pasien</span>
                                    <span class="font-medium">{{ $treatment->insurance_type }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Jenis Pasien</span>
                                    <span class="font-medium">{{ $treatment->insurance_type }}</span>
                                </div>
                                @if( $treatment->insurance_type == 'BPJS Kesehatan' || $treatment->insurance_type == 'BPJS Ketenagakerjaan' )
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Nomor BPJS</span>
                                    <span class="font-medium">{{ $treatment->bpjs_number }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Gejala/Keluhan</span>
                                    <span class="font-medium">{{ $treatment->complaint_description }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <a href="/" class="px-4 py-2 md:px-8 md:py-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold rounded-xl transition">
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            @else
            <section>
                <div class="bg-white rounded-3xl shadow-lg border border-red-100 overflow-hidden">
                    <!-- Top Banner (Red) -->
                    <div class="bg-red-500 h-32 flex items-center justify-center relative">
                        <!-- Decorative Circle -->
                        <div class="absolute -bottom-12 bg-white rounded-full p-4 shadow-lg">
                            <div class="bg-red-100 rounded-full p-4 text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="pt-16 pb-10 px-6 sm:px-10 text-center">
                        <h2 class="text-xl md:text-3xl font-bold text-gray-900 mb-2">Pendaftaran Gagal</h2>
                        <p class="text-sm md:text-md text-gray-500 mb-8">Mohon maaf, terjadi kesalahan saat memproses data pendaftaran Anda.</p>

                        <!-- Error Detail Box -->
                        <div class="bg-red-50 rounded-xl p-6 border border-red-100 mb-8 text-left max-w-lg mx-auto">
                            <div class="flex gap-3">
                                <div class="shrink-0 text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-red-800 mb-1">{{ $reason }}</h4>
                                    <p class="text-sm text-red-700 leading-relaxed">{{ $description }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <a href="/booking" class="px-4 py-2 md:px-8 md:py-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold rounded-xl transition flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Kembali & Coba Lagi
                            </a>
                            <a href="#" class="px-4 py-2 md:px-8 md:py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg shadow-red-200 transition">
                                Hubungi Admin
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            @endif
        </div>
    </main>

    <!-- FOOTER (Sama dengan Landing Page) -->
    <footer class="bg-gray-900 text-gray-300 pt-12 pb-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8 text-center md:text-left">
                <div>
                    <div class="flex items-center justify-center md:justify-start gap-2 mb-4 text-white">
                        <div class="bg-green-600 p-1 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <span class="font-bold text-lg">RS Cepat Sembuh</span>
                    </div>
                    <p class="text-sm">Melayani dengan hati, merawat dengan teknologi.</p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Kontak</h4>
                    <p class="text-sm">Jl. Soekarno Hatta, Bandar Lampung</p>
                    <p class="text-sm">(0721) 123-4567</p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Bantuan</h4>
                    <p class="text-sm">Jadwal Dokter</p>
                    <p class="text-sm">Cek Antrian</p>
                    <p class="text-sm">Konfirmasi Pembayaran</p>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 text-center text-xs text-gray-500">
                &copy; block px-3 py-2 RS Cepat Sembuh Lampung. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Script Logic untuk BPJS -->
    <script>
        function toggleBPJS() {
            const jenisPasien = document.getElementById('jenis_pasien').value;
            const bpjsSection = document.getElementById('bpjs_section');
            const bpjsInput = document.getElementById('no_bpjs');

            if (jenisPasien === 'BPJS Kesehatan' || jenisPasien == 'BPJS Ketenagakerjaan') {
                // Tampilkan section
                bpjsSection.classList.remove('hidden');
                bpjsInput.required = true;
            } else {
                // Sembunyikan section
                bpjsSection.classList.add('hidden');
                bpjsInput.required = false;
                bpjsInput.value = ''; // Reset value
            }
        }

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
