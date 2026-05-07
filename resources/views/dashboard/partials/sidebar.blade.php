<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col">

    <!-- Sidebar Header / Brand -->
    <div class="flex items-center justify-center h-20 border-b border-gray-100 px-6">
        <div class="flex items-center gap-2">
            <div class="bg-green-600 p-2 rounded-lg text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
            <span class="font-bold text-xl text-gray-800 tracking-tight">RS Cepat Sembuh</span>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 no-scrollbar">
        <!-- Group Label -->
        <div class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Utama</div>

        <!-- Menu Item: Dashboard (Active) -->
        <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dashboard.index') ? 'bg-green-50 text-green-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} rounded-xl font-medium transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            Dashboard
        </a>

        <!-- Menu Item: Antrian -->
        <a href="{{ route('dashboard.treatments.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dashboard.treatments.*') ? 'bg-green-50 text-green-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} rounded-xl font-medium transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
            </svg>

            Data Pengobatan
        </a>

        @can('role', 'Admin')
        <!-- Group Label -->
        <div class="px-3 mt-6 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Master Data</div>

        <!-- Menu Item: Dokter -->
        <a href="{{ route('dashboard.doctors.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dashboard.doctors.*') ? 'bg-green-50 text-green-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} rounded-xl font-medium transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Dokter & Spesialis
        </a>

        <!-- Menu Item: Laporan -->
        <a href="{{ route('dashboard.rooms.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dashboard.rooms.*') ? 'bg-green-50 text-green-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} rounded-xl font-medium transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
            </svg>
            Data Ruangan
        </a>
        @endcan
    </nav>

    <!-- Sidebar Footer: User -->
    <div class="border-t border-gray-100 p-4">
        <a href="#" class="flex items-center gap-3 w-full">
            <img class="h-9 w-9 rounded-full object-cover" src="{{ auth()->user()->photo ? '/storage/profile-photos/' . auth()->user()->photo : 'https://placehold.co/400' }}" alt="{{ auth()->user()->name }}">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->role }}</p>
            </div>
        </a>
    </div>
</aside>
