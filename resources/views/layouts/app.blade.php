<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitem Pendataan SK</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome --}}
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Google Font (Inter) --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <style>
    body {
        font-family: 'Inter', sans-serif;
    }
    </style>
</head>

<body class="bg-blue-50 text-gray-800 antialiased">
    <!-- Navbar -->
    <!-- Navbar -->
    <header
        class="w-full bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 text-white shadow-lg fixed top-0 left-0 z-50 h-16 flex items-center">
        <div class="flex justify-between items-center w-full px-6">

            <div class="flex items-center gap-4">
                <img src="{{ asset('img/kgtk.png') }}" class="w-28">
                <div>
                    <h1 class="font-bold text-lg leading-none">SIMPENO-SK</h1>
                    <p class="text-white text-sm leading-none">Provinsi Gorontalo</p>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <!-- Sidebar (dipisah di file sendiri) -->
    <div class="fixed left-0 top-16 h-[calc(100vh-4rem)] w-64">
        @include('component.sidebar')
    </div>

    <main class="pt-20 ml-64 px-6 pb-10">
        @yield('content')
    </main>
</body>

</html>


<!-- <nav class="flex items-center  gap-6">
    <div class="flex justify-center gap-6">
        <a href="{{ route('dashboard') }}"
            class="font-medium {{ request()->routeIs('dashboard') ? 'text-yellow-300 font-semibold' : 'text-white hover:text-gray-200' }}">
            <i class="fa-solid fa-house mr-1"></i> Dashboard
        </a>
        <a href="{{ route('sk.create') }}"
            class="font-medium {{ request()->routeIs('sk.create') ? 'text-yellow-300 font-semibold' : 'text-white hover:text-gray-200' }}">
            <i class="fa-solid fa-plus mr-1"></i> Tambah SK
        </a>
        <a href="{{ route('sk.archive') }}"
            class="font-medium {{ request()->routeIs('sk.archive') ? 'text-yellow-300 font-semibold' : 'text-white hover:text-gray-200' }}">
            <i class="fa-solid fa-box-archive mr-1"></i> Arsip SK
        </a>
    </div>
</nav> -->