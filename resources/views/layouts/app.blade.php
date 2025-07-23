<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Energi Terbarukan</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />

    <!-- Aset Bawaan Laravel Breeze (Penting untuk Dropdown) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .card {
            background-color: white;
            border-radius: 1.5rem; /* Lebih bulat */
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        .search-input {
            background-color: #f3f4f6;
            border-radius: 9999px;
            border: 2px solid transparent;
            padding: 0.75rem 1.5rem;
            padding-left: 3rem; /* Ruang untuk ikon */
            transition: all 0.3s ease;
        }
        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            background-color: white;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white p-6 flex flex-col shadow-lg">
            <div class="flex items-center gap-3 mb-10">
                <!-- Ganti dengan path logo ITPLN Anda jika ada -->
                <img src="{{ asset('assets/img/LOGO_IT_PLN.png') }}" alt="Logo ITPLN" class="h-10">
            </div>

            <nav class="flex flex-col gap-2">
            @auth
                @if(auth()->user()->is_admin)
                    <!-- ================= -->
                    <!-- MENU UNTUK ADMIN -->
                    <!-- ================= -->
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-house w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.requests.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.requests.*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-table-list w-5 text-center"></i>
                        <span>Daftar Request</span>
                    </a>
                @else
                    <!-- ====================== -->
                    <!-- MENU UNTUK USER BIASA -->
                    <!-- ====================== -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-solar-panel w-5 text-center"></i>
                        <span>Simulasi Energi</span>
                    </a>

                    <!-- PERUBAHAN DI SINI -->
                    <a href="{{ route('requests.history') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('requests.history') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-list-check w-5 text-center"></i>
                        <span>Status Pemasangan</span>
                    </a>
                    <!-- AKHIR PERUBAHAN -->
                @endif
            @endauth
        </nav>

            <div class="mt-auto">
                <a href="https://itpln.ac.id" target="_blank" class="text-xs text-gray-400 hover:text-blue-500">
                    © {{ date('Y') }} - Didukung oleh ITPLN
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white p-4 flex justify-between items-center border-b">
                <!-- ======================================== -->
                <!-- MULAI FORM PENCARIAN BARU -->
                <!-- ======================================== -->
                <div class="relative w-2/3 md:w-1/2">
                    <form 
                        @if(Auth::check() && Auth::user()->is_admin)
                            action="{{ route('admin.requests.index') }}"
                        @else
                            action="{{ route('requests.history') }}"
                        @endif
                        method="GET" class="flex items-center gap-2">
                        
                        <!-- Input Teks -->
                        <div class="relative flex-grow">
                            <i class="fa-solid fa-search text-gray-400 absolute top-1/2 left-4 -translate-y-1/2"></i>
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Cari nama/kota..." 
                                class="w-full bg-gray-100 border-gray-200 rounded-lg py-2 pl-10 text-sm">
                        </div>

                        <!-- Input Tanggal BARU -->
                        <div class="relative">
                            <input 
                                type="date" 
                                name="search_date" 
                                value="{{ request('search_date') }}"
                                class="w-full bg-gray-100 border-gray-200 rounded-lg py-2 px-3 text-sm">
                        </div>
                        
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2 text-sm">
                            Cari
                        </button>
                    </form>
                </div>
                <!-- ====================== -->
                <!-- AKHIR FORM PENCARIAN -->
                <!-- ====================== -->

                <!-- ====================================================== -->
                <!-- MULAI BLOK DROPDOWN PROFIL (PENGGANTI IKON PROFIL LAMA) -->
                <!-- ====================================================== -->
                @auth
                <div class="relative hidden sm:flex sm:items-center sm:ml-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                
                                <!-- Menampilkan Nama Pengguna -->
                                <div class="font-semibold">{{ Auth::user()->name }}</div>

                                <!-- Ikon Panah Dropdown -->
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Link ke Halaman Edit Profil -->
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Tombol Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
                @endauth
                <!-- ================================== -->
                <!-- SELESAI BLOK DROPDOWN PROFIL -->
                <!-- ================================== -->
            </header>

            <!-- Page Content -->
            <div class="p-6 md:p-10 overflow-y-auto">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Script Section -->
    @stack('scripts')
</body>
</html>