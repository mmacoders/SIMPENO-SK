<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Aplikasi SK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6; /* Fallback color */
        }
        
        .bg-login {
            background-image: url('img/bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: fixed;
            inset: 0;
            z-index: -1;
        }

        .bg-overlay {
            position: fixed;
            inset: 0;
            background: rgba(17, 24, 39, 0.7); /* Darker overlay */
            backdrop-filter: blur(8px);
            z-index: -1;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen px-4">

    <!-- Background Layers -->
    <div class="bg-login"></div>
    <div class="bg-overlay"></div>

    <div class="w-full max-w-md">
        <!-- Logo / Brand (Optional) -->
        <div class="text-center mb-8">
             <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/10 backdrop-blur-md mb-4 shadow-lg border border-white/20">
                <i class="fa-solid fa-file-signature text-4xl text-white"></i>
            </div>
            <h1 class="text-3xl font-bold text-white tracking-wide">Sistem Arsip SK</h1>
            <p class="text-gray-300 mt-2 text-sm">Masuk untuk mengelola surat keputusan</p>
        </div>

        <!-- Login Card -->
        <div class="glass-card rounded-2xl p-8 md:p-10 w-full animate-fade-in-up">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Selamat Datang Kembali</h2>

            @if (session('error'))
            <div class="flex items-center p-4 mb-6 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50" role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <div>
                   {{ session('error') }}
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('login.process') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email atau Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fa-solid fa-envelope text-gray-400"></i>
                        </div>
                        <input type="text" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-3 transition-all placeholder-gray-400" placeholder="nama@email.com" required>
                    </div>
                </div>

                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-3 transition-all placeholder-gray-400" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="remember" aria-describedby="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="remember" class="text-gray-500">Ingat saya</label>
                        </div>
                    </div>
                    <!-- <a href="#" class="text-sm font-medium text-blue-600 hover:underline">Lupa password?</a> -->
                </div>

                <button type="submit" class="w-full text-white bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Masuk ke Sistem
                </button>
            </form>
        </div>
        
        <div class="text-center mt-6 text-gray-400 text-xs">
            &copy; {{ date('Y') }} Sistem Arsip SK. All rights reserved.
        </div>
    </div>

</body>
</html>