<!-- Fixed Mobile Header -->
<header class="bg-white/95 backdrop-blur-md shadow-lg border-b border-gray-200/50 sticky top-0 z-30">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <!-- Left: Mobile menu button -->
            <div class="flex items-center">
                <button 
                    @click="toggleSidebar()" 
                    class="lg:hidden group p-2.5 rounded-xl text-gray-600 hover:text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                >
                    <svg class="h-6 w-6 transition-transform duration-300 group-hover:scale-110" 
                         :class="sidebarOpen ? 'rotate-90' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <!-- Mobile Logo with gradient -->
                <div class="lg:hidden ml-3">
                    <div class="flex items-center space-x-2">
                        <div class="p-1.5 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg shadow-md">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h1 class="text-lg font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">PMB STIKPAR</h1>
                    </div>
                </div>
            </div>
            
            <!-- Right: User dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button 
                    @click="open = !open"
                    class="flex items-center space-x-3 p-2 text-sm bg-white/70 backdrop-blur-sm rounded-2xl hover:bg-white/90 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 border border-gray-200/50 shadow-sm hover:shadow-md group"
                    :class="{ 'bg-white/90 shadow-md ring-2 ring-blue-100': open }"
                >
                    <!-- Avatar with decorative ring -->
                    <div class="relative">
                        <img class="h-9 w-9 rounded-xl object-cover ring-2 ring-white shadow-md group-hover:ring-blue-200 transition-all duration-300" 
                             src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&color=7F9CF5&background=EBF4FF&bold=true" 
                             alt="{{ auth()->user()->name }}">
                        <!-- Online status indicator -->
                        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 border-2 border-white rounded-full shadow-sm">
                            <div class="w-full h-full bg-green-400 rounded-full animate-pulse"></div>
                        </div>
                    </div>
                    
                    <!-- User info -->
                    <div class="hidden sm:block text-left min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate max-w-32">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->isAdmin() ? 'Administrator' : 'Mahasiswa' }}</p>
                    </div>
                    
                    <!-- Dropdown arrow with animation -->
                    <svg class="h-4 w-4 text-gray-400 transition-transform duration-300" 
                         :class="{ 'rotate-180': open }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <!-- Elegant Dropdown Menu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                     x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     @click.away="open = false"
                     class="absolute right-0 mt-3 w-80 sm:w-80 w-72 bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-200/50 z-50 overflow-hidden">
                    
                    <!-- Decorative header with gradient -->
                    <div class="relative bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 px-6 py-5 border-b border-gray-100">
                        <!-- Background pattern -->
                        <div class="absolute inset-0 opacity-30">
                            <div class="absolute top-0 left-0 w-32 h-32 bg-blue-200 rounded-full -translate-x-16 -translate-y-16 blur-2xl"></div>
                            <div class="absolute bottom-0 right-0 w-24 h-24 bg-purple-200 rounded-full translate-x-12 translate-y-12 blur-2xl"></div>
                        </div>
                        
                        <!-- User profile info -->
                        <div class="relative flex items-center space-x-4">
                            <div class="relative">
                                <img class="h-16 w-16 rounded-2xl object-cover ring-4 ring-white shadow-lg" 
                                     src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&color=7F9CF5&background=EBF4FF&bold=true&size=128" 
                                     alt="{{ auth()->user()->name }}">
                                <!-- Decorative dots -->
                                <div class="absolute -top-1 -right-1 w-4 h-4 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-gray-900 truncate">{{ auth()->user()->name }}</h3>
                                <p class="text-sm text-gray-600 truncate">{{ auth()->user()->email }}</p>
                                <div class="flex items-center mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ auth()->user()->isAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }} shadow-sm">
                                        {{ auth()->user()->isAdmin() ? 'Administrator' : 'Mahasiswa' }}
                                    </span>
                                    <div class="ml-3 flex items-center text-xs text-green-600 font-medium">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-1 animate-pulse shadow-sm"></div>
                                        Online
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Decorative divider -->
                    <div class="px-6 py-1">
                        <div class="flex items-center">
                            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                            <div class="px-3">
                                <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                            </div>
                            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                        </div>
                    </div>
                    
                    <!-- Admin Settings Menu (Only for Admin) -->
                    @if(auth()->user()->isAdmin())
                    
                    
                    <!-- Decorative divider -->
                    <div class="px-6 py-1">
                        <div class="flex items-center">
                            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                            <div class="px-3">
                                <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                            </div>
                            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Logout section with elegant design -->
                    <div class="px-6 py-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full group flex items-center justify-center px-6 py-3 text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-all duration-300 hover:shadow-md transform hover:scale-[1.02]">
                                <div class="p-2 bg-red-100 rounded-lg mr-3 group-hover:bg-red-200 transition-colors shadow-sm">
                                    <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <div class="font-bold">Keluar dari Sistem</div>
                                    <div class="text-xs text-red-500 font-normal">Logout dengan aman</div>
                                </div>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Decorative footer -->
                    <div class="bg-gray-50/50 px-6 py-3 text-center">
                        <div class="flex items-center justify-center space-x-2 text-xs text-gray-500">
                            <div class="w-1 h-1 bg-gray-400 rounded-full"></div>
                            <span>PMB STIKPAR Dashboard</span>
                            <div class="w-1 h-1 bg-gray-400 rounded-full"></div>
                            <span>{{ date('Y') }}</span>
                            <div class="w-1 h-1 bg-gray-400 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Decorative gradient line -->
    <div class="h-0.5 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 opacity-60"></div>
</header>

<style>
/* Header specific styles */
header {
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}

/* Smooth transitions */
header button, header a {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Hover effects */
header button:hover {
    transform: translateY(-1px);
}

/* Dropdown shadow */
.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Glass effect */
.backdrop-blur-xl {
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
}

/* Mobile responsive dropdown */
@media (max-width: 640px) {
    header .w-80 {
        width: 18rem;
        max-width: calc(100vw - 2rem);
        right: 0;
        left: auto;
    }
}
</style>