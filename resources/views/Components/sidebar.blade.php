<!-- Fixed Sidebar with Proper Scrolling -->
<div class="h-full flex flex-col bg-white">
    
    <!-- Sidebar Header - Fixed at top -->
    <div class="flex-shrink-0 flex items-center justify-between h-20 px-6 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 text-white shadow-lg relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-32 h-32 bg-white rounded-full -translate-x-16 -translate-y-16"></div>
            <div class="absolute bottom-0 right-0 w-24 h-24 bg-white rounded-full translate-x-12 translate-y-12"></div>
        </div>

<!-- Enhanced Header Logo Section -->
<div class="flex items-center space-x-3 relative z-10">
    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm border border-white/30 shadow-lg hover:scale-105 transition-transform duration-300">
        <img 
            src="/images/logo.jpg" 
            alt="Logo STIKPAR" 
            class="h-12 w-12 rounded-lg object-contain shadow-md"
        />
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
                    <!-- Verifikasi Pembayaran -->
                    <a href="{{ route('admin.payments.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.payments.*') ? 'bg-yellow-50 text-yellow-700 border-r-4 border-yellow-600' : 'text-gray-700 hover:bg-yellow-50 hover:text-yellow-700' }}">
                        <div class="p-2 bg-yellow-50 rounded-lg mr-3 group-hover:bg-yellow-100 transition-colors">
                            <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <span>Verifikasi Pembayaran</span>
                        <div class="ml-auto flex items-center space-x-2">
                            @php
                                $pendingPayments = App\Models\PaymentProof::where('verification_status', 'pending')->count();
                            @endphp
                            @if($pendingPayments > 0)
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-0.5 rounded-full">{{ $pendingPayments }}</span>
                            @endif
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <!-- Data Pendaftar - BARU -->
                    <a href="{{ route('admin.registrations.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.registrations.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                        <div class="p-2 bg-blue-50 rounded-lg mr-3 group-hover:bg-blue-100 transition-colors">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <span>Data Pendaftar</span>
                        <div class="ml-auto flex items-center space-x-2">
                            @php
                                $totalRegistrations = App\Models\Registration::count();
                                $waitingDecision = App\Models\Registration::where('status', 'waiting_decision')->count();
                            @endphp
                            @if($totalRegistrations > 0)
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded-full">{{ $totalRegistrations }}</span>
                            @endif
                            @if($waitingDecision > 0)
                                <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2 py-0.5 rounded-full animate-pulse">{{ $waitingDecision }}</span>
                            @endif
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
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
                    <!-- Data Pendaftaran -->
                    <a href="{{ route('registration.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('registration.index') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                        <div class="p-2 bg-blue-50 rounded-lg mr-3 group-hover:bg-blue-100 transition-colors">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <span>Data Pendaftaran</span>
                        <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </a>
                    
                    <!-- Formulir Pendaftaran - Enhanced with dynamic status -->
                    <a href="{{ route('registration.form') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('registration.form*') ? 'bg-green-50 text-green-700 border-r-4 border-green-600' : 'text-gray-700 hover:bg-green-50 hover:text-green-700' }}">
                        <div class="p-2 bg-green-50 rounded-lg mr-3 group-hover:bg-green-100 transition-colors">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <span>Formulir Pendaftaran</span>
                        @php
                            // Get user's registration status
                            $userRegistration = App\Models\Registration::where('user_id', auth()->id())->first();
                            $formStatus = 'pending';
                            
                            if ($userRegistration) {
                                switch ($userRegistration->status) {
                                    case 'waiting_documents':
                                    case 'waiting_payment':  // TAMBAHAN: Allow form access for waiting_payment
                                        $formStatus = $userRegistration->form && $userRegistration->form->is_completed ? 'draft' : 'pending';
                                        break;
                                    case 'waiting_decision':
                                        $formStatus = 'submitted';
                                        break;
                                    case 'passed':
                                    case 'failed':
                                        $formStatus = 'completed';
                                        break;
                                    default:
                                        $formStatus = 'locked';
                                        break;
                                }
                            }
                        @endphp
                        <div class="ml-auto">
                            @if($formStatus === 'completed')
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded-full">Selesai</span>
                            @elseif($formStatus === 'submitted')
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2 py-0.5 rounded-full animate-pulse">Direview</span>
                            @elseif($formStatus === 'draft')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-0.5 rounded-full">Draft</span>
                            @elseif($formStatus === 'locked')
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-0.5 rounded-full">Locked</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-0.5 rounded-full">Pending</span>
                            @endif
                        </div>
                    </a>

                    <!-- Check Kelulusan - BARU -->
                    @if($userRegistration && in_array($userRegistration->status, ['waiting_decision', 'passed', 'waiting_final_payment', 'completed', 'failed', 'rejected']))
                        <a href="{{ route('selection-result.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('selection-result.*') ? 'bg-purple-50 text-purple-700 border-r-4 border-purple-600' : 'text-gray-700 hover:bg-purple-50 hover:text-purple-700' }}">
                            <div class="p-2 bg-purple-50 rounded-lg mr-3 group-hover:bg-purple-100 transition-colors">
                                <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                            <span>Check Kelulusan</span>
                            <div class="ml-auto flex items-center space-x-2">
                                @if($userRegistration->status === 'passed')
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded-full animate-pulse">Lulus!</span>
                                @elseif($userRegistration->status === 'completed')
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-medium px-2 py-0.5 rounded-full">Selesai</span>
                                @elseif($userRegistration->status === 'failed')
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-0.5 rounded-full">Gagal</span>
                                @elseif($userRegistration->status === 'waiting_decision')
                                    <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2 py-0.5 rounded-full animate-pulse">Pending</span>
                                @elseif($userRegistration->status === 'waiting_final_payment')
                                    <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2 py-0.5 rounded-full">Verif</span>
                                @endif
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Status Information for User -->
            @if($userRegistration)
            <div class="pt-6">
                <div class="flex items-center px-4 mb-3">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                    <span class="px-3 text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-50 rounded-full py-1">Status Anda</span>
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                </div>
                
                <div class="px-4 py-3 bg-gradient-to-r {{ 
                    $userRegistration->status === 'completed' ? 'from-green-50 to-emerald-50 border-green-200' : 
                    ($userRegistration->status === 'passed' ? 'from-purple-50 to-pink-50 border-purple-200' : 
                    ($userRegistration->status === 'waiting_decision' ? 'from-indigo-50 to-blue-50 border-indigo-200' : 
                    ($userRegistration->status === 'waiting_documents' ? 'from-green-50 to-emerald-50 border-green-200' : 
                    ($userRegistration->status === 'waiting_payment' ? 'from-orange-50 to-yellow-50 border-orange-200' :
                    ($userRegistration->status === 'failed' ? 'from-red-50 to-pink-50 border-red-200' : 'from-gray-50 to-gray-100 border-gray-200'))))) 
                }} border rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            @if($userRegistration->status === 'completed')
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            @elseif($userRegistration->status === 'passed')
                                <div class="w-3 h-3 bg-purple-500 rounded-full animate-bounce"></div>
                            @elseif($userRegistration->status === 'waiting_decision')
                                <div class="w-3 h-3 bg-indigo-500 rounded-full animate-pulse"></div>
                            @elseif($userRegistration->status === 'waiting_documents')
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            @elseif($userRegistration->status === 'waiting_payment')
                                <div class="w-3 h-3 bg-orange-500 rounded-full animate-pulse"></div>
                            @elseif($userRegistration->status === 'failed')
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            @else
                                <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold {{ 
                                $userRegistration->status === 'completed' ? 'text-green-800' : 
                                ($userRegistration->status === 'passed' ? 'text-purple-800' : 
                                ($userRegistration->status === 'waiting_decision' ? 'text-indigo-800' : 
                                ($userRegistration->status === 'waiting_documents' ? 'text-green-800' : 
                                ($userRegistration->status === 'waiting_payment' ? 'text-orange-800' :
                                ($userRegistration->status === 'failed' ? 'text-red-800' : 'text-gray-800'))))) 
                            }}">
                                {{ $userRegistration->status_label }}
                            </p>
                            <p class="text-xs {{ 
                                $userRegistration->status === 'completed' ? 'text-green-600' : 
                                ($userRegistration->status === 'passed' ? 'text-purple-600' : 
                                ($userRegistration->status === 'waiting_decision' ? 'text-indigo-600' : 
                                ($userRegistration->status === 'waiting_documents' ? 'text-green-600' : 
                                ($userRegistration->status === 'waiting_payment' ? 'text-orange-600' :
                                ($userRegistration->status === 'failed' ? 'text-red-600' : 'text-gray-600'))))) 
                            }} truncate">
                                No. {{ $userRegistration->registration_number }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
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