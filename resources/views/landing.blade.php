<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RS Cepat Sembuh - Lampung</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="font-poppins text-gray-700 antialiased bg-gray-50">

    <!-- NAVBAR -->
    <nav class="bg-white shadow-sm fixed w-full z-50 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <!-- Icon Logo Sederhana (SVG) -->
                        <div class="bg-green-600 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <span class="font-bold text-2xl text-gray-800 tracking-tight">RS <span class="text-green-600">Cepat Sembuh</span></span>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#beranda" class="text-gray-600 hover:text-green-600 font-medium transition">Home</a>
                    <a href="#layanan" class="text-gray-600 hover:text-green-600 font-medium transition">Layanan</a>
                    <a href="#tentang" class="text-gray-600 hover:text-green-600 font-medium transition">Tentang Kami</a>
                    <a href="#kontak" class="text-gray-600 hover:text-green-600 font-medium transition">Kontak</a>
                    <a href="/history" class="text-gray-600 hover:text-green-600 font-medium transition">Riwayat Berobat</a>
                    <a href="/booking" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-full font-medium transition shadow-lg shadow-green-200">Daftar Online</a>
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
            <a href="#beranda" class="block px-3 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-md font-medium">Home</a>
            <a href="#layanan" class="block px-3 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-md font-medium">Layanan</a>
            <a href="#tentang" class="block px-3 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-md font-medium">Tentang Kami</a>
            <a href="#kontak" class="block px-3 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-md font-medium">Kontak</a>
            <a href="/history" class="block px-3 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-md font-medium">Riwayat Berobat</a>
            <a href="/booking" class="block w-full text-center mt-4 bg-green-600 text-white px-4 py-2 rounded-lg font-medium">Daftar Online</a>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section id="beranda" class="pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div class="text-center lg:text-left">
                    <div class="inline-block bg-green-100 text-green-700 px-4 py-1.5 rounded-full text-sm font-semibold mb-6">
                        Pelayanan Kesehatan Terbaik di Lampung
                    </div>
                    <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                        Kesehatan Anda Adalah <br>
                        <span class="text-green-600">Prioritas Utama Kami</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        RS Cepat Sembuh hadir di jantung kota Lampung dengan fasilitas modern dan tenaga medis profesional. Kami siap melayani Anda dengan sepenuh hati 24 jam non-stop.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#kontak" class="px-8 py-4 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition shadow-xl shadow-green-200 text-center">
                            Hubungi Kami
                        </a>
                        <a href="#layanan" class="px-8 py-4 bg-white text-green-600 border border-gray-200 rounded-xl font-semibold hover:bg-gray-50 transition text-center">
                            Lihat Layanan
                        </a>
                    </div>
                </div>

                <!-- Image Content -->
                <div class="relative">
                    <!-- Decorative Circle Background -->
                    <div class="absolute -top-10 -right-10 w-72 h-72 bg-green-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
                    <div class="absolute -bottom-10 -left-10 w-72 h-72 bg-yellow-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>

                    <!-- Main Image -->
                    <img src="https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?q=80&w=1000&auto=format&fit=crop"
                         alt="Dokter memeriksa pasien"
                         class="relative rounded-3xl shadow-2xl w-full object-cover h-[400px] lg:h-[500px] transform hover:scale-[1.02] transition duration-500">

                    <!-- Floating Card -->
                    <div class="absolute bottom-6 left-6 bg-white p-4 rounded-2xl shadow-lg hidden md:block max-w-xs">
                        <div class="flex items-center gap-3">
                            <div class="bg-green-100 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Layanan Darurat</p>
                                <p class="text-sm font-bold text-gray-800">Siap 24 Jam</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- LAYANAN SECTION -->
    <section id="layanan" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Layanan Unggulan Kami</h2>
                <p class="text-gray-600">Kami menyediakan berbagai layanan medis komprehensif untuk memenuhi kebutuhan kesehatan keluarga Anda di Lampung.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-gray-50 p-8 rounded-2xl hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-gray-100 group">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6 group-hover:bg-green-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Instalasi Gawat Darurat (IGD)</h3>
                    <p class="text-gray-600">Penanganan medis cepat tanggap untuk kondisi kritis dengan tim dokter spesialis berpengalaman.</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-gray-50 p-8 rounded-2xl hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-gray-100 group">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6 group-hover:bg-green-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Laboratorium</h3>
                    <p class="text-gray-600">Fasilitas cek laboratorium lengkap dengan hasil akurat dan cepat untuk diagnosa yang tepat.</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-gray-50 p-8 rounded-2xl hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-gray-100 group">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6 group-hover:bg-green-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Poli Spesialis</h3>
                    <p class="text-gray-600">Layanan poli spesialis penyakit dalam, anak, kandungan, hingga bedah dengan teknologi terkini.</p>
                </div>

                 <!-- Card 4 -->
                 <div class="bg-gray-50 p-8 rounded-2xl hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-gray-100 group">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6 group-hover:bg-green-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Farmasi & Apotek</h3>
                    <p class="text-gray-600">Layanan resep obat yang lengkap, tersedia 24 jam dengan konsultasi apoteker profesional.</p>
                </div>

                 <!-- Card 5 -->
                 <div class="bg-gray-50 p-8 rounded-2xl hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-gray-100 group">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6 group-hover:bg-green-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Medical Check Up</h3>
                    <p class="text-gray-600">Paket pemeriksaan kesehatan rutin untuk individu maupun perusahaan untuk deteksi dini.</p>
                </div>

                 <!-- Card 6 -->
                 <div class="bg-gray-50 p-8 rounded-2xl hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-gray-100 group">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6 group-hover:bg-green-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Rawat Inap</h3>
                    <p class="text-gray-600">Kamar rawat inap yang nyaman, bersih, dan dilengkapi fasilitas modern untuk pemulihan pasien.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TENTANG KAMI / STATISTIK -->
    <section id="tentang" class="py-20 bg-green-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="order-2 lg:order-1">
                    <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?q=80&w=1000&auto=format&fit=crop" alt="Interior RS Cepat Sembuh" class="rounded-2xl shadow-xl w-full h-[400px] object-cover">
                </div>
                <div class="order-1 lg:order-2">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Mengapa Memilih RS Cepat Sembuh?</h2>
                    <p class="text-gray-600 mb-6">
                        Berlokasi strategis di <strong>Bandar Lampung</strong>, kami berkomitmen memberikan pelayanan kesehatan yang berkualitas, terjangkau, dan paripurna. Kami percaya bahwa setiap warga Lampung berhak mendapatkan perawatan kesehatan terbaik.
                    </p>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-700">Dokter Spesialis Berpengalaman</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-700">Fasilitas Medis Modern & Terlengkap</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-700">Pelayanan Ramah & Berorientasi Pasien</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-16 border-t border-green-200 pt-10">
                <div class="text-center">
                    <p class="text-4xl font-bold text-green-700 mb-2">24+</p>
                    <p class="text-gray-600 font-medium">Jam Layanan</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold text-green-700 mb-2">50+</p>
                    <p class="text-gray-600 font-medium">Dokter Spesialis</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold text-green-700 mb-2">100+</p>
                    <p class="text-gray-600 font-medium">Kamar Rawat Inap</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold text-green-700 mb-2">10k+</p>
                    <p class="text-gray-600 font-medium">Pasien Sembuh</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION -->
    <section class="py-20 bg-green-600">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Butuh Bantuan Medis Segera?</h2>
            <p class="text-green-100 text-lg mb-8 max-w-2xl mx-auto">Jangan ragu untuk menghubungi kami. Tim medis kami siap melayani Anda kapan saja, siang maupun malam.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="tel:0721123456" class="inline-flex items-center justify-center px-8 py-4 bg-white text-green-700 rounded-xl font-bold hover:bg-gray-100 transition shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    (0721) 123-4567
                </a>
                <a href="#" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-white rounded-xl font-bold hover:bg-white hover:text-green-700 transition">
                    WhatsApp Kami
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer id="kontak" class="bg-gray-900 text-gray-300 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                <!-- About -->
                <div>
                    <div class="flex items-center gap-2 mb-6 text-white">
                        <div class="bg-green-600 p-1.5 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <span class="font-bold text-xl tracking-tight">RS Cepat Sembuh</span>
                    </div>
                    <p class="text-sm leading-relaxed mb-6">
                        Rumah sakit pilihan keluarga di Lampung. Mengutamakan kenyamanan dan kesembuhan pasien dengan standar pelayanan internasional.
                    </p>
                    <div class="flex space-x-4">
                        <!-- Social Icons Placeholder -->
                        <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-green-600 transition text-white">FB</a>
                        <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-green-600 transition text-white">IG</a>
                        <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-green-600 transition text-white">YT</a>
                    </div>
                </div>

                <!-- Links -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-6">Tautan Cepat</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-green-400 transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-green-400 transition">Jadwal Dokter</a></li>
                        <li><a href="#" class="hover:text-green-400 transition">Fasilitas & Layanan</a></li>
                        <li><a href="#" class="hover:text-green-400 transition">Karir</a></li>
                        <li><a href="#" class="hover:text-green-400 transition">Berita & Artikel</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-6">Layanan Kami</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-green-400 transition">Rawat Inap</a></li>
                        <li><a href="#" class="hover:text-green-400 transition">Rawat Jalan</a></li>
                        <li><a href="#" class="hover:text-green-400 transition">Medical Check Up</a></li>
                        <li><a href="#" class="hover:text-green-400 transition">Laboratorium</a></li>
                        <li><a href="#" class="hover:text-green-400 transition">Ambulans 24 Jam</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-6">Hubungi Kami</h3>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-green-600 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Jl. Soekarno Hatta No. 123, Rajabasa,<br>Kec. Kedaton, Kota Bandar Lampung,<br>Lampung 35145</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>(0721) 123-4567</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>info@rscepatsembuh.co.id</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} RS Cepat Sembuh Lampung. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Simple Script for Mobile Menu -->
    <script>
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
