<!-- Fixed Sidebar with Proper Scrolling -->
<div class="h-full flex flex-col bg-white">
    
    <!-- Sidebar Header - Fixed at top -->
    <div class="flex-shrink-0 flex items-center justify-between h-20 px-6 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 text-white shadow-lg relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-32 h-32 bg-white rounded-full -translate-x-16 -translate-y-16"></div>
            <div class="absolute bottom-0 right-0 w-24 h-24 bg-white rounded-full translate-x-12 translate-y-12"></div>
        </div>
        
        <div class="flex items-center space-x-3 relative z-10">
            <div class="p-2.5 bg-white/20 rounded-xl backdrop-blur-sm border border-white/30 shadow-lg">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight">PMB STIKPAR</h1>
                <p class="text-blue-100 text-xs font-medium">Sistem Pendaftaran</p>
            </div>
        </div>
        
        <!-- Close button for mobile -->
        <button @click="sidebarOpen = false" class="lg:hidden p-2 rounded-lg text-white hover:bg-white/20 transition-colors relative z-10">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    
    <!-- Scrollable Navigation Area -->
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-2">
        
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-4 py-3.5 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 border-r-4 border-blue-600 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-blue-600' }}">
            <div class="p-2 {{ request()->routeIs('dashboard') ? 'bg-blue-200' : 'bg-gray-100 group-hover:bg-blue-100' }} rounded-lg mr-3 transition-colors">
                <svg class="h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-blue-700' : 'text-gray-600 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                </svg>
            </div>
            <span>Dashboard</span>
            @if(request()->routeIs('dashboard'))
                <div class="ml-auto">
                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                </div>
            @endif
        </a>
        
        @if(auth()->user()->isAdmin())
            <!-- Admin Menu -->
            <div class="pt-6">
                <div class="flex items-center px-4 mb-3">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                    <span class="px-3 text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-50 rounded-full py-1">Konfigurasi</span>
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                </div>
                
                <div class="space-y-1">
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.settings.*') ? 'bg-purple-50 text-purple-700 border-r-4 border-purple-600' : 'text-gray-700 hover:bg-purple-50 hover:text-purple-700' }}">
                        <div class="p-2 bg-purple-50 rounded-lg mr-3 group-hover:bg-purple-100 transition-colors">
                            <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span>Konfigurasi Sistem</span>
                        <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </a>
                   
                </div>
            </div>

            <!-- Management Section -->
            <div class="pt-6">
                <div class="flex items-center px-4 mb-3">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                    <span class="px-3 text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-50 rounded-full py-1">Manajemen</span>
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                </div>
                
                <div class="space-y-1">
                    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 group">
                        <div class="p-2 bg-blue-50 rounded-lg mr-3 group-hover:bg-blue-100 transition-colors">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <span>Data Pendaftar</span>
                        <div class="ml-auto flex items-center space-x-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded-full">1,234</span>
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                    
                    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-xl hover:bg-yellow-50 hover:text-yellow-700 transition-all duration-200 group">
                        <div class="p-2 bg-yellow-50 rounded-lg mr-3 group-hover:bg-yellow-100 transition-colors">
                            <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <span>Verifikasi Pembayaran</span>
                        <div class="ml-auto flex items-center space-x-2">
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-0.5 rounded-full">56</span>
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                    
                    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-xl hover:bg-red-50 hover:text-red-700 transition-all duration-200 group">
                        <div class="p-2 bg-red-50 rounded-lg mr-3 group-hover:bg-red-100 transition-colors">
                            <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <span>Review Dokumen</span>
                        <div class="ml-auto flex items-center space-x-2">
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-0.5 rounded-full">23</span>
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                    
                    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 transition-all duration-200 group">
                        <div class="p-2 bg-emerald-50 rounded-lg mr-3 group-hover:bg-emerald-100 transition-colors">
                            <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <span>Seleksi Mahasiswa</span>
                        <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Reports Section -->
            <div class="pt-6">
                <div class="flex items-center px-4 mb-3">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                    <span class="px-3 text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-50 rounded-full py-1">Laporan</span>
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                </div>
                
                <div class="space-y-1">
                    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-xl hover:bg-teal-50 hover:text-teal-700 transition-all duration-200 group">
                        <div class="p-2 bg-teal-50 rounded-lg mr-3 group-hover:bg-teal-100 transition-colors">
                            <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <span>Statistik Pendaftaran</span>
                        <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </a>
                </div>
            </div>
        @else
            <!-- User Menu -->
            <div class="pt-6">
                <div class="flex items-center px-4 mb-3">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                    <span class="px-3 text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-50 rounded-full py-1">Proses Pendaftaran</span>
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                </div>
                
                <div class="space-y-1">
                    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 group">
                        <div class="p-2 bg-blue-50 rounded-lg mr-3 group-hover:bg-blue-100 transition-colors">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <span>Profil Saya</span>
                        <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </a>
                    
                    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-xl hover:bg-green-50 hover:text-green-700 transition-all duration-200 group">
                        <div class="p-2 bg-green-50 rounded-lg mr-3 group-hover:bg-green-100 transition-colors">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <span>Formulir Pendaftaran</span>
                        <div class="ml-auto">
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-0.5 rounded-full">Pending</span>
                        </div>
                    </a>
                    
                    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-xl hover:bg-purple-50 hover:text-purple-700 transition-all duration-200 group">
                        <div class="p-2 bg-purple-50 rounded-lg mr-3 group-hover:bg-purple-100 transition-colors">
                            <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <span>Pembayaran</span>
                        <div class="ml-auto">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded-full">Aktif</span>
                        </div>
                    </a>
                </div>
            </div>
        @endif

        <!-- Help Section -->
        <div class="pt-6 mt-6 border-t border-gray-200">
            <div class="flex items-center px-4 mb-3">
                <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                <span class="px-3 text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-50 rounded-full py-1">Bantuan</span>
                <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
            </div>
            
            <div class="space-y-1">
                <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-xl hover:bg-amber-50 hover:text-amber-700 transition-all duration-200 group">
                    <div class="p-2 bg-amber-50 rounded-lg mr-3 group-hover:bg-amber-100 transition-colors">
                        <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span>FAQ</span>
                </a>
                
                <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 group">
                    <div class="p-2 bg-blue-50 rounded-lg mr-3 group-hover:bg-blue-100 transition-colors">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span>Hubungi Support</span>
                </a>
            </div>
        </div>
    </nav>
    
    <!-- User Info at Bottom - Fixed -->
    <div class="flex-shrink-0 p-4 border-t border-gray-200 bg-gray-50">
        <div class="flex items-center space-x-3">
            <img class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm" 
                 src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&color=7F9CF5&background=EBF4FF" 
                 alt="{{ auth()->user()->name }}">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
            </div>
            <div class="flex-shrink-0">
                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
            </div>
        </div>
    </div>
</div>