<!-- Minimal Beautiful Footer -->
<footer class="relative bg-gradient-to-r from-gray-50 via-white to-gray-50 border-t border-gray-200/60">
    <!-- Subtle background pattern -->
    <div class="absolute inset-0 opacity-[0.03]">
        <div class="absolute top-0 left-1/4 w-32 h-32 bg-blue-500 rounded-full blur-3xl"></div>
        <div class="absolute top-0 right-1/4 w-24 h-24 bg-purple-500 rounded-full blur-3xl"></div>
    </div>
    
    <div class="relative px-6 lg:px-8">
        <div class="py-4">
            <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                
                <!-- Left: Beautiful Brand -->
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-3">
                        <!-- Enhanced logo -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl blur opacity-50 group-hover:opacity-70 transition-opacity"></div>
                            <div class="relative p-2 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 rounded-xl shadow-lg">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Brand info -->
                        <div>
                            <h3 class="text-base font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                                PMB STIKPAR
                            </h3>
                            <p class="text-xs text-gray-500 font-medium">Sistem Pendaftaran</p>
                        </div>
                    </div>
                </div>
                
                <!-- Right: Simple Copyright -->
                <div class="flex items-center space-x-4">
                    <!-- Copyright -->
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">© {{ date('Y') }} STIK Pariwisata Indonesia</span>
                    </div>
                    
                    <!-- Simple status -->
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs text-gray-500">Online</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Beautiful gradient line -->
    <div class="h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 opacity-60"></div>
</footer>

<style>
/* Minimal footer styles */
footer {
    margin-top: auto;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
}

/* Logo subtle glow */
.group:hover .absolute {
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

/* Mobile responsive */
@media (max-width: 640px) {
    footer .flex-col {
        text-align: center;
    }
    
    footer .justify-between {
        gap: 1rem;
    }
}

/* Print styles */
@media print {
    footer {
        display: none !important;
    }
}
</style>