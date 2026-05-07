<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RS Cepat Sembuh</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-poppins text-gray-700 bg-gray-50 antialiased min-h-screen flex items-center justify-center">

    <!-- CONTAINER UTAMA (Grid untuk Layar Terbagi) -->
    <div class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col lg:flex-row min-h-[600px] mx-4 md:mx-10">

        <!-- SISI KIRI: Visual & Branding (Hidden di Mobile, Visible di Desktop) -->
        <div class="hidden lg:flex lg:w-1/2 bg-green-900 relative items-center justify-center p-12">
            <!-- Background Image dengan Overlay -->
            <img src="https://images.unsplash.com/photo-1579684385127-1ef15d508118?q=80&w=1000&auto=format&fit=crop"
                 alt="Rumah Sakit"
                 class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-40">

            <div class="relative z-10 text-center text-white">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="bg-white text-green-600 p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold">RS Cepat Sembuh</h1>
                </div>
                <h2 class="text-2xl font-semibold mb-4">Selamat Datang Kembali!</h2>
                <p class="text-green-100 max-w-sm mx-auto">Sistem Informasi Manajemen Rumah Sakit Terpadu. Silakan masuk untuk mengelola data pasien dan antrian.</p>
            </div>
        </div>

        <!-- SISI KANAN: Form Login -->
        <div class="w-full lg:w-1/2 p-8 lg:p-16 flex flex-col justify-center">

            <!-- Mobile Header (Hanya muncul di Mobile) -->
            <div class="lg:hidden text-center mb-8">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <div class="bg-green-600 p-1 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <span class="font-bold text-xl text-gray-800">RS Cepat Sembuh</span>
                </div>
            </div>

            <h3 class="text-2xl font-bold text-gray-900 mb-2">Login Staff / Dokter</h3>
            <p class="text-sm text-gray-500 mb-8">Silakan masukkan akun email dan password Anda.</p>

            <!-- FORM -->
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative text-sm" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                <!-- Employee ID Input -->
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-1">Employee ID</label>
                    <input type="text" id="employee_id" name="employee_id" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition outline-none" placeholder="Masukkan Employee ID Anda">
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition outline-none" placeholder="Masukkan password">
                        <!-- Toggle Password Icon -->
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-green-200 transition transform hover:-translate-y-0.5">
                    Masuk
                </button>

                <!-- Footer Link -->
                <div class="text-center text-sm text-gray-500">
                    Belum punya akun? <span class="font-medium text-green-600 hover:text-green-500">Hubungi Admin IT</span>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Validasi & Toggle Password -->
    <script>
        // Fungsi untuk menampilkan/menyembunyikan password
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Ganti ikon ke mata tertutup (slash) - menggunakan path mata tertutup secara manual atau ganti SVG
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                passwordInput.type = 'password';
                // Ganti ikon ke mata terbuka
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>
</body>
</html>
