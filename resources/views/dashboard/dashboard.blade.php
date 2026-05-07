<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - RS Cepat Sembuh</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Style Tambahan untuk Scrollbar agar rapi -->
    <style>
        /* Sembunyikan scrollbar default tapi tetap bisa scroll */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        /* (IE, Edge, Firefox) */
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>
<body class="font-poppins text-gray-700 bg-gray-50 h-screen flex overflow-hidden">

    <!-- 1. SIDEBAR BACKDROP (Untuk Mobile/Tablet saat sidebar terbuka) -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity duration-300 opacity-0" aria-hidden="true"></div>

    <!-- 2. SIDEBAR -->
    <!-- Default: -translate-x-full (tutup di mobile), lg:translate-x-0 (buka di desktop jika ingin permanent, tapi kita buat toggleable sesuai request) -->
    <!-- Logic: Class 'translate-x-0' untuk buka, '-translate-x-full' untuk tutup -->
    @include('dashboard.partials.sidebar')

    <!-- 3. MAIN CONTENT WRAPPER -->
    <div id="main-content" class="flex-1 flex flex-col min-w-0 overflow-hidden bg-gray-50 transition-all duration-300 lg:ml-0">

        <!-- Header / Navbar -->
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10 relative">
            <!-- Kiri: Toggle Button & Breadcrumbs -->
            <div class="flex items-center gap-4">
                <button id="menu-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none p-2 rounded-lg hover:bg-gray-100 transition">
                    <!-- Icon Hamburger -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Kanan: Avatar & Dropdown -->
            <div class="flex items-center gap-4">

                <!-- Profile Dropdown -->
                <div class="relative ml-3">
                    <div>
                        <button type="button" id="user-menu-button" class="flex items-center max-w-xs rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only">Open user menu</span>
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ auth()->user()->photo ? '/storage/profile-photos/' . auth()->user()->photo : 'https://placehold.co/400' }}" alt="{{ auth()->user()->name }}">
                        </button>
                    </div>

                    <!-- Dropdown menu -->
                    <div id="user-dropdown" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-gray-100 ring-opacity-5 focus:outline-none transform transition ease-out duration-100 opacity-0 scale-95" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Profil Saya</a>
                        <div class="border-t border-gray-100"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50" role="menuitem">Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- 4. SCROLLABLE CONTENT AREA -->
        <main class="flex-1 overflow-auto p-4 sm:p-8 flex flex-col" id="scroll-area">

            <div class="space-y-8 flex-1 w-full">

                <div class="border-b border-gray-200 pb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
                        <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
                    </div>
                </div>

                @yield('content')

            </div>

            <!-- Footer Bawah -->
            <footer class="mt-auto pt-10 pb-2 border-t border-gray-200 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} RS Cepat Sembuh Lampung. All rights reserved.
            </footer>
        </main>
    </div>

    <!-- JAVASCRIPT LOGIC (FIXED) -->
    <script>
        // --- Sidebar Logic ---
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const menuToggle = document.getElementById('menu-toggle');
        const mainContent = document.getElementById('main-content');

        // State awal: Buka di Desktop, Tutup di Mobile
        let isSidebarOpen = window.innerWidth >= 1024;

        // Fungsi Update Tampilan Sidebar
        function updateSidebar() {
            const width = window.innerWidth;

            if (isSidebarOpen) {
                // --- STATE: BUKA (OPEN) ---

                // 1. Manipulasi posisi (Translate)
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');

                // 2. Manipulasi Layout Desktop
                // Pastikan sidebar kembali ke posisi static (mengambil ruang) di desktop
                sidebar.classList.add('lg:static', 'lg:translate-x-0');

                if (width >= 1024) {
                    sidebar.classList.remove('fixed'); // Hapus fixed di desktop
                } else {
                    sidebar.classList.add('fixed'); // Di mobile harus absolute/fixed menimpa layar
                }

                // 3. Handle Backdrop
                if (width < 1024) {
                    // Tampilkan backdrop di mobile
                    backdrop.classList.remove('hidden');
                    setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
                } else {
                    // Sembunyikan backdrop di desktop
                    backdrop.classList.add('hidden');
                }

            } else {
                // --- STATE: TUTUP (CLOSED) ---

                // 1. Manipulasi posisi (Translate)
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');

                // 2. Sembunyikan Backdrop
                backdrop.classList.add('opacity-0');
                setTimeout(() => backdrop.classList.add('hidden'), 300);

                // 3. Manipulasi Layout
                // Selalu hapus static dan jadikan fixed saat ditutup agar tak makan ruang
                sidebar.classList.remove('lg:static', 'lg:translate-x-0');
                sidebar.classList.add('fixed');
            }
        }

        // Event Listener Tombol Hamburger
        menuToggle.addEventListener('click', (e) => {
            e.stopPropagation(); // Mencegah event click tembus ke mainContent
            isSidebarOpen = !isSidebarOpen;
            updateSidebar();
        });

        // Event Listener Backdrop (Klik tutup sidebar)
        backdrop.addEventListener('click', () => {
            isSidebarOpen = false;
            updateSidebar();
        });

        // Event Listener Klik Main Content (Tutup sidebar jika sedang terbuka di mode overlay/mobile)
        mainContent.addEventListener('click', () => {
            if (isSidebarOpen && window.innerWidth < 1024) {
                isSidebarOpen = false;
                updateSidebar();
            }
        });

        // Handle Resize Window (Reset state agar konsisten)
        let lastWidth = window.innerWidth;
        window.addEventListener('resize', () => {
            const currentWidth = window.innerWidth;

            // Jika berubah ke mode mobile, secara default sidebar di-hidden
            if (lastWidth >= 1024 && currentWidth < 1024) {
                isSidebarOpen = false;
            }

            // Jika berubah dari mobile ke desktop, buka sidebar secara default (opsional)
            if (lastWidth < 1024 && currentWidth >= 1024) {
                isSidebarOpen = true;
            }

            lastWidth = currentWidth;
            updateSidebar();
        });

        // Inisialisasi Awal: Cek lebar layar
        updateSidebar();


        // --- Profile Dropdown Logic ---
        const userMenuButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');
        let isDropdownOpen = false;

        userMenuButton.addEventListener('click', (event) => {
            event.stopPropagation();
            isDropdownOpen = !isDropdownOpen;
            toggleDropdown();
        });

        function toggleDropdown() {
            if (isDropdownOpen) {
                userDropdown.classList.remove('hidden');
                setTimeout(() => {
                    userDropdown.classList.remove('opacity-0', 'scale-95');
                    userDropdown.classList.add('opacity-100', 'scale-100');
                }, 10);
            } else {
                userDropdown.classList.remove('opacity-100', 'scale-100');
                userDropdown.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    userDropdown.classList.add('hidden');
                }, 100);
            }
        }

        // Tutup dropdown jika klik di luar
        document.addEventListener('click', (event) => {
            if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                isDropdownOpen = false;
                userDropdown.classList.add('hidden');
            }
        });

    </script>
</body>
</html>
