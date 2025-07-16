<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - PMB STIKPAR</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Custom variables */
        :root {
            --sidebar-width: 288px;
            --header-height: 64px;
            --footer-height: 60px;
        }
        
        /* Ensure full height and prevent main body scroll */
        html, body {
            height: 100%;
            overflow: hidden; /* Prevent main scroll */
        }
        
        /* Layout container */
        .layout-container {
            display: flex;
            height: 100vh; /* Full viewport height */
        }
        
        /* Sidebar styles */
        .sidebar-container {
            width: var(--sidebar-width);
            flex-shrink: 0;
            background: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            z-index: 40;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        /* Main content container */
        .main-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            min-width: 0;
            overflow: hidden;
        }
        
        /* Header - fixed height */
        .header-container {
            height: var(--header-height);
            flex-shrink: 0;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            z-index: 30;
        }
        
        /* Content area - scrollable, calculated height */
        .content-scroll-area {
            height: calc(100vh - var(--header-height) - var(--footer-height));
            overflow-y: auto;
            overflow-x: hidden;
            background: #f9fafb;
            flex-shrink: 0;
        }
        
        /* Content wrapper */
        .content-wrapper {
            padding: 1.5rem;
            min-height: 100%;
        }
        
        @media (min-width: 640px) {
            .content-wrapper {
                padding: 2rem;
            }
        }
        
        /* Footer - fixed at bottom */
        .footer-container {
            height: var(--footer-height);
            flex-shrink: 0;
            background: white;
            border-top: 1px solid #e5e7eb;
            z-index: 20;
        }
        
        /* Mobile styles */
        @media (max-width: 1023px) {
            .layout-container {
                display: block;
                height: 100vh;
            }
            
            .sidebar-container {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 50;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar-container.open {
                transform: translateX(0);
            }
            
            .main-container {
                width: 100%;
                height: 100vh;
            }
            
            /* Mobile overlay */
            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 40;
                backdrop-filter: blur(4px);
            }
            
            /* Prevent body scroll when sidebar is open */
            .sidebar-open {
                overflow: hidden;
            }
        }
        
        /* Custom scrollbar for content */
        .content-scroll-area::-webkit-scrollbar {
            width: 8px;
        }
        
        .content-scroll-area::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        .content-scroll-area::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
            border: 2px solid #f1f5f9;
        }
        
        .content-scroll-area::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Custom scrollbar for sidebar */
        .sidebar-container::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-container::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        
        .sidebar-container::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        /* Smooth scrolling */
        .content-scroll-area {
            scroll-behavior: smooth;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased h-full" 
      x-data="{ 
          sidebarOpen: false,
          toggleSidebar() {
              this.sidebarOpen = !this.sidebarOpen;
              // Prevent body scroll on mobile when sidebar is open
              if (window.innerWidth < 1024) {
                  document.body.classList.toggle('sidebar-open', this.sidebarOpen);
              }
          },
          closeSidebar() {
              this.sidebarOpen = false;
              document.body.classList.remove('sidebar-open');
          },
          init() {
              // Handle window resize
              window.addEventListener('resize', () => {
                  if (window.innerWidth >= 1024) {
                      this.sidebarOpen = false;
                      document.body.classList.remove('sidebar-open');
                  }
              });
          }
      }">
    
    <!-- Layout Container -->
    <div class="layout-container">
        
        <!-- Sidebar -->
        <div class="sidebar-container lg:block"
             :class="sidebarOpen ? 'open' : ''">
            @include('components.sidebar')
        </div>
        
        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="closeSidebar()"
             class="mobile-overlay lg:hidden"></div>
        
        <!-- Main Content Container -->
        <div class="main-container">
            <!-- Header - Fixed at Top -->
            <div class="header-container">
                @include('components.header')
            </div>
            
            <!-- Scrollable Content Area -->
            <div class="content-scroll-area">
                <div class="content-wrapper">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Page Content -->
                    @yield('content')
                </div>
            </div>
            
            <!-- Footer - Fixed at Bottom -->
            <div class="footer-container">
                @include('components.footer')
            </div>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>