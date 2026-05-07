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
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

                <!-- KIRI: Informasi & Visual -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Daftar Online</h2>
                        <p class="text-gray-600 mb-6">Isi formulir di samping untuk melakukan pendaftaran pasien baru secara online. Hindari antrean panjang di loket pendaftaran dengan mendaftar dari rumah.</p>

                        <div class="bg-green-50 rounded-xl p-4 flex items-start gap-3">
                            <div class="bg-green-200 p-2 rounded-full text-green-700 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-green-800 text-sm">Catatan Penting</h4>
                                <p class="text-xs text-green-700 mt-1">Pastikan data yang Anda masukkan sesuai dengan KTP atau Kartu BPJS untuk memudahkan proses verifikasi.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Gambar Ilustrasi -->
                    <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?q=80&w=1000&auto=format&fit=crop"
                         alt="Ilustrasi Pendaftaran"
                         class="rounded-2xl shadow-lg w-full h-64 object-cover hidden lg:block">
                </div>

                <!-- KANAN: Form Pendaftaran -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-10">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">Data Pasien</h3>

                        <form action="{{ route('booking.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <!-- Section 1: Identitas Pasien -->
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- NIK -->
                                    <div>
                                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK (Nomor Induk Kependudukan)</label>
                                        <input type="number" id="nik" name="nik" required class="w-full px-4 py-2.5 rounded-lg border @error('nik') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-green-500 focus:border-green-500 transition outline-none" placeholder="Contoh: 1801..." value="{{ old('nik') }}">
                                        @error('nik')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <!-- Nama Lengkap -->
                                    <div>
                                        <label for="patient_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                        <input type="text" id="patient_name" name="patient_name" required class="w-full px-4 py-2.5 rounded-lg border @error('patient_name') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-green-500 focus:border-green-500 transition outline-none" placeholder="Sesuai KTP" value="{{ old('patient_name') }}">
                                        @error('patient_name')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Tanggal Lahir -->
                                    <div>
                                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                                        <input type="date" id="birth_date" name="birth_date" required class="w-full px-4 py-2.5 rounded-lg border @error('birth_date') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-green-500 focus:border-green-500 transition outline-none" value="{{ old('birth_date') }}">
                                    </div>
                                    @error('birth_date')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                    <!-- Jenis Kelamin -->
                                    <div>
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
                                </div>

                                <!-- Nomor HP / WA (Dengan Prefix +62) -->
                                <div>
                                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP / WhatsApp</label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-4 rounded-l-lg border border-r-0 border-gray-300 bg-gray-100 text-gray-500 text-sm font-medium">
                                            +62
                                        </span>
                                        <input type="number" id="phone_number" name="phone_number" required class="flex-1 min-w-0 block w-full px-4 py-2.5 rounded-r-lg border @error('phone_number') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-green-500 focus:border-green-500 transition outline-none" placeholder="81234567890" value="{{ old('phone_number') }}">
                                    </div>
                                    @error('phone_number')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Alamat -->
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                                    <textarea id="address" name="address" rows="2" required class="w-full px-4 py-2.5 rounded-lg border @error('address') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-green-500 focus:border-green-500 transition outline-none" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan...">{{ old('address') }}</textarea>
                                    @error('address')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nomor HP Darurat -->
                                <div>
                                    <label for="emergency_phone_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP / WhatsApp Darurat</label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-4 rounded-l-lg border border-r-0 border-gray-300 bg-gray-100 text-gray-500 text-sm font-medium">
                                            +62
                                        </span>
                                        <input type="number" id="emergency_phone_number" name="emergency_phone_number" required class="flex-1 min-w-0 block w-full px-4 py-2.5 rounded-r-lg border @error('emergency_phone_number') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-green-500 focus:border-green-500 transition outline-none" placeholder="81234567890" value="{{ old('emergency_phone_number') }}">
                                    </div>
                                    @error('emergency_phone_number')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <hr class="border-gray-100">

                            <!-- Section 2: Informasi Berobat -->
                            <div class="space-y-4">
                                <h4 class="text-md font-bold text-gray-900">Informasi Berobat</h4>

                                <!-- Jenis Pasien -->
                                <div>
                                    <label for="insurance_type" class="block text-sm font-medium text-gray-700 mb-1">Jenis Pasien</label>
                                    <div class="relative">
                                        <select id="insurance_type" name="insurance_type" onchange="toggleBPJS()" required class="appearance-none w-full px-4 py-2.5 rounded-lg border @error('insurance_type') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-green-500 focus:border-green-500 transition outline-none bg-white">
                                            <option value="" disabled selected>Pilih Jenis Pasien</option>
                                            <option value="Umum" {{ old('insurance_type') == 'Umum' ? 'selected' : '' }}>Umum</option>
                                            <option value="BPJS Kesehatan" {{ old('insurance_type') == 'BPJS Kesehatan' ? 'selected' : '' }}>BPJS Kesehatan</option>
                                            <option value="BPJS Ketenagakerjaan" {{ old('insurance_type') == 'BPJS Ketenagakerjaan' ? 'selected' : '' }}>BPJS Ketenagakerjaan</option>
                                            <option value="Asuransi Swasta" {{ old('insurance_type') == 'Asuransi Swasta' ? 'selected' : '' }}>Asuransi Swasta</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                    @error('insurance_type')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nomor BPJS (Conditional) -->
                                <div id="bpjs_section" class="hidden transition-all duration-300">
                                    <label for="bpjs_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor Kartu BPJS</label>
                                    <input type="number" id="bpjs_number" name="bpjs_number" class="w-full px-4 py-2.5 rounded-lg border @error('bpjs_number') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-green-500 focus:border-green-500 transition outline-none bg-green-50" placeholder="Masukkan 13 digit nomor BPJS" value="{{ old('bpjs_number') }}">
                                    <p class="text-xs text-green-600 mt-1">*Wajib diisi jika jenis pasien BPJS</p>
                                    @error('bpjs_number')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Keluhan / Gejala -->
                                <div>
                                    <label for="complaint_description" class="block text-sm font-medium text-gray-700 mb-1">Keluhan / Gejala</label>
                                    <textarea id="complaint_description" name="complaint_description" rows="3" required class="w-full px-4 py-2.5 rounded-lg border @error('complaint_description') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-green-500 focus:border-green-500 transition outline-none" placeholder="Jelaskan keluhan Anda secara singkat...">{{ old('complaint_description') }}</textarea>
                                    @error('complaint_description')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-2">
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-green-200 transition transform hover:-translate-y-0.5">
                                    Daftar Sekarang
                                </button>
                                <p class="text-xs text-gray-500 text-center mt-3">Dengan mendaftar, Anda menyetujui syarat & ketentuan rumah sakit.</p>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
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
            const jenisPasien = document.getElementById('insurance_type').value;
            const bpjsSection = document.getElementById('bpjs_section');

            if (jenisPasien === 'BPJS Kesehatan' || jenisPasien == 'BPJS Ketenagakerjaan') {
                // Tampilkan section
                bpjsSection.classList.remove('hidden');
                bpjsInput.required = true;
            } else {
                // Sembunyikan section
                bpjsSection.classList.add('hidden');
            }
        }
        toggleBPJS()

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
