<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Masuk - Pendaftaran Mahasiswa Baru')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-15px) rotate(2deg); }
            66% { transform: translateY(-8px) rotate(-1deg); }
        }
        @keyframes wave {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(1deg); }
            75% { transform: rotate(-1deg); }
        }
        .float-animation { animation: float 4s ease-in-out infinite; }
        .float-animation-delay { animation: float 4s ease-in-out infinite 1s; }
        .float-animation-delay-2 { animation: float 4s ease-in-out infinite 2s; }
        .wave-animation { animation: wave 3s ease-in-out infinite; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-cyan-50 via-blue-50 to-indigo-100 relative">
    
    <!-- Beautiful Background -->
    <div class="absolute inset-0 overflow-hidden">
        <!-- Colorful floating orbs -->
        <div class="absolute top-20 left-20 w-32 h-32 bg-gradient-to-br from-blue-400 to-cyan-300 rounded-full opacity-60 blur-xl float-animation"></div>
        <div class="absolute top-40 right-32 w-24 h-24 bg-gradient-to-br from-indigo-400 to-blue-300 rounded-full opacity-50 blur-lg float-animation-delay"></div>
        <div class="absolute bottom-32 left-1/4 w-40 h-40 bg-gradient-to-br from-teal-300 to-cyan-400 rounded-full opacity-40 blur-2xl float-animation-delay-2"></div>
        <div class="absolute bottom-20 right-20 w-28 h-28 bg-gradient-to-br from-blue-300 to-indigo-400 rounded-full opacity-55 blur-lg float-animation"></div>
        
        <!-- Geometric shapes -->
        <div class="absolute top-32 left-1/3 w-16 h-16 border-3 border-blue-400/30 rounded-2xl wave-animation"></div>
        <div class="absolute top-60 right-1/4 w-12 h-12 bg-gradient-to-br from-cyan-400/40 to-blue-400/40 rounded-xl float-animation-delay"></div>
        <div class="absolute bottom-40 left-1/2 w-8 h-8 bg-indigo-400/50 transform rotate-45 float-animation-delay-2"></div>
    </div>

    <!-- Subtle pattern overlay -->
    <div class="absolute inset-0 opacity-[0.02] bg-[url('data:image/svg+xml,%3csvg width="40" height="40" xmlns="http://www.w3.org/2000/svg"%3e%3cdefs%3e%3cpattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"%3e%3cpath d="m 40 0 l 0 40 l -40 0 l 0 -40 l 40 0 z" fill="none" stroke="%23000000" stroke-width="1"/%3e%3c/pattern%3e%3c/defs%3e%3crect width="100%25" height="100%25" fill="url(%23grid)"/%3e%3c/svg%3e')]"></div>

    <!-- Main Content -->
    <div class="relative z-10 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            
            <!-- Logo/Brand Section - HANYA INI YANG DIUBAH -->
            <div class="text-center">
                <div class="mx-auto h-28 w-28 bg-gradient-to-br from-blue-400 via-sky-400 to-blue-500 rounded-3xl flex items-center justify-center shadow-2xl shadow-blue-500/30 mb-8 relative transform hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/30 to-white/10 rounded-3xl"></div>
                    <img 
                        src="/images/logo.jpg" 
                        alt="Logo" 
                        class="h-20 w-20 relative z-10 rounded-2xl object-cover shadow-lg"
                    />
                </div>
                
                <h1 class="text-4xl font-bold text-gray-800 mb-2">
                    @yield('heading', 'Selamat Datang')
                </h1>
                <p class="text-gray-600 text-lg">
                    @yield('subheading', 'Masuk untuk melanjutkan pendaftaran')
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl shadow-blue-500/10 border border-white/50 p-8 relative overflow-hidden">
                <!-- Card accent -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-cyan-500 to-indigo-500"></div>
                
                <!-- Content -->
                <div class="relative z-10">
                    @yield('content')
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center">
                <p class="text-gray-500 text-sm">
                    @yield('footer', '© 2024 Universitas Anda. Semua hak cipta dilindungi.')
                </p>
            </div>
            
        </div>
    </div>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>