@extends('layouts.app')

@section('title', auth()->user()->isAdmin() ? 'Dashboard Admin' : 'Dashboard Mahasiswa')

@section('content')
@if(auth()->user()->isAdmin())
    <!-- Admin Dashboard -->
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg p-6 text-white">
            <h1 class="text-2xl font-bold">Dashboard Admin PMB</h1>
            <p class="text-purple-100 mt-2">Kelola seluruh proses penerimaan mahasiswa baru</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Pendaftar -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Pendaftar</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_registrations']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Pembayaran Tertunda -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pembayaran Tertunda</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_payments'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Dokumen Pending -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Menunggu Keputusan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['waiting_decision'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Lulus Seleksi -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Lulus Seleksi</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['passed_students'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Konfigurasi Sistem -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Konfigurasi Sistem</h3>
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">Atur biaya, jadwal, dan kuota pendaftaran</p>
                <a href="{{ route('admin.settings.index') }}" class="block w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition-colors text-center">
                    Kelola Konfigurasi
                </a>
            </div>

            <!-- Verifikasi Pembayaran -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Verifikasi Pembayaran</h3>
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">{{ $stats['pending_payments'] }} pembayaran menunggu verifikasi</p>
                <a href="{{ route('admin.payments.index') }}" class="block w-full bg-yellow-600 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 transition-colors text-center">
                    Kelola Pembayaran
                </a>
            </div>

            <!-- Data Pendaftar -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Data Pendaftar</h3>
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">Lihat dan kelola semua data pendaftar</p>
                <a href="{{ route('admin.registrations.index') }}" class="block w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-center">
                    Kelola Data
                </a>
            </div>
        </div>

        <!-- Recent Activities & Pending Tasks -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Activities -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h3>
                <div class="space-y-4">
                    @forelse($recentRegistrations as $registration)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">Pendaftar baru</p>
                            <p class="text-sm text-gray-500">{{ $registration->user->name }} mendaftar sebagai mahasiswa baru</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $registration->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">Belum ada aktivitas terbaru</p>
                    @endforelse
                </div>
            </div>

            <!-- Pending Tasks -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tugas Pending</h3>
                <div class="space-y-4">
                    @if($pendingTasks['document_review'] > 0)
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Menunggu Keputusan</p>
                                <p class="text-xs text-gray-500">{{ $pendingTasks['document_review'] }} pendaftar menunggu keputusan</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.registrations.index') }}?status=waiting_decision" class="text-red-600 hover:text-red-700 text-sm font-medium">
                            Lihat
                        </a>
                    </div>
                    @endif

                    @if($pendingTasks['payment_verification'] > 0)
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Konfirmasi Pembayaran</p>
                                <p class="text-xs text-gray-500">{{ $pendingTasks['payment_verification'] }} pembayaran perlu dikonfirmasi</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.payments.index') }}" class="text-yellow-600 hover:text-yellow-700 text-sm font-medium">
                            Lihat
                        </a>
                    </div>
                    @endif

                    @if($pendingTasks['form_review'] > 0)
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Review Formulir</p>
                                <p class="text-xs text-gray-500">{{ $pendingTasks['form_review'] }} formulir perlu di-review</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.registrations.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            Lihat
                        </a>
                    </div>
                    @endif

                    @if(array_sum($pendingTasks) == 0)
                    <div class="flex items-center justify-center p-8 text-gray-500">
                        <div class="text-center">
                            <svg class="h-12 w-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm">Semua tugas sudah selesai! 🎉</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@else
    <!-- User Dashboard -->
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-4 sm:p-6 text-white">
            <h1 class="text-xl sm:text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p class="text-blue-100 mt-2 text-sm sm:text-base">Kelola proses pendaftaran mahasiswa baru Anda di sini</p>
        </div>

        <!-- Progress Steps - Responsive Design -->
        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Status Pendaftaran</h2>
            
            <!-- Desktop/Tablet Version (hidden on mobile) -->
            <div class="hidden lg:block">
                <div class="flex items-center justify-between">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 {{ $progressSteps['account'] ? 'bg-green-500' : 'bg-gray-300' }} rounded-full flex items-center justify-center text-white font-bold text-sm">
                            @if($progressSteps['account'])
                                ✓
                            @else
                                1
                            @endif
                        </div>
                        <span class="text-xs text-gray-600 mt-2 text-center">Registrasi<br>Akun</span>
                    </div>
                    
                    <!-- Connector -->
                    <div class="flex-1 h-0.5 {{ $progressSteps['admin_fee'] ? 'bg-green-500' : 'bg-gray-300' }} mx-2"></div>
                    
                    <!-- Step 2 -->
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 {{ $progressSteps['admin_fee'] ? 'bg-green-500' : ($progressSteps['upload_proof'] ? 'bg-blue-500' : 'bg-gray-300') }} rounded-full flex items-center justify-center text-white font-bold text-sm">
                            @if($progressSteps['admin_fee'])
                                ✓
                            @else
                                2
                            @endif
                        </div>
                        <span class="text-xs text-gray-600 mt-2 text-center">Biaya<br>Administrasi</span>
                    </div>
                    
                    <!-- Connector -->
                    <div class="flex-1 h-0.5 {{ $progressSteps['fill_form'] ? 'bg-green-500' : 'bg-gray-300' }} mx-2"></div>
                    
                    <!-- Step 3 -->
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 {{ $progressSteps['fill_form'] ? 'bg-green-500' : 'bg-gray-300' }} rounded-full flex items-center justify-center text-white font-bold text-sm">
                            @if($progressSteps['fill_form'])
                                ✓
                            @else
                                3
                            @endif
                        </div>
                        <span class="text-xs text-gray-600 mt-2 text-center">Isi<br>Formulir</span>
                    </div>
                    
                    <!-- Connector -->
                    <div class="flex-1 h-0.5 {{ $progressSteps['upload_docs'] ? 'bg-green-500' : 'bg-gray-300' }} mx-2"></div>
                    
                    <!-- Step 4 -->
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 {{ $progressSteps['upload_docs'] ? 'bg-green-500' : 'bg-gray-300' }} rounded-full flex items-center justify-center text-white font-bold text-sm">
                            @if($progressSteps['upload_docs'])
                                ✓
                            @else
                                4
                            @endif
                        </div>
                        <span class="text-xs text-gray-600 mt-2 text-center">Upload<br>Dokumen</span>
                    </div>
                    
                    <!-- Connector -->
                    <div class="flex-1 h-0.5 {{ $progressSteps['waiting'] ? 'bg-green-500' : 'bg-gray-300' }} mx-2"></div>
                    
                    <!-- Step 5 -->
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 {{ $progressSteps['waiting'] ? 'bg-green-500' : 'bg-gray-300' }} rounded-full flex items-center justify-center text-white font-bold text-sm">
                            @if($progressSteps['waiting'])
                                ✓
                            @else
                                5
                            @endif
                        </div>
                        <span class="text-xs text-gray-600 mt-2 text-center">Menunggu<br>Kelulusan</span>
                    </div>
                </div>
            </div>

            <!-- Mobile/Tablet Version (clean vertical layout) -->
            <div class="lg:hidden">
                <div class="space-y-6">
                    <!-- Step 1: Account Registration -->
                    <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl border border-green-200">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">
                                ✓
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-900">Registrasi Akun</h3>
                            <p class="text-sm text-green-600">Selesai • Akun berhasil dibuat</p>
                        </div>
                    </div>

                    <!-- Step 2: Admin Fee -->
                    <div class="flex items-center space-x-4 p-4 rounded-xl border {{ $progressSteps['admin_fee'] ? 'bg-gradient-to-r from-green-50 to-green-100 border-green-200' : ($progressSteps['upload_proof'] ? 'bg-gradient-to-r from-blue-50 to-blue-100 border-blue-200' : 'bg-gray-50 border-gray-200') }}">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 {{ $progressSteps['admin_fee'] ? 'bg-green-500' : ($progressSteps['upload_proof'] ? 'bg-blue-500' : 'bg-gray-300') }} rounded-full flex items-center justify-center text-white font-bold {{ !$progressSteps['admin_fee'] && $progressSteps['upload_proof'] ? 'animate-pulse' : '' }}">
                                @if($progressSteps['admin_fee'])
                                    ✓
                                @else
                                    2
                                @endif
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-900">Biaya Administrasi</h3>
                            <p class="text-sm {{ $progressSteps['admin_fee'] ? 'text-green-600' : ($progressSteps['upload_proof'] ? 'text-blue-600' : 'text-gray-400') }}">
                                @if($progressSteps['admin_fee'])
                                    Selesai • Pembayaran diverifikasi
                                @elseif($progressSteps['upload_proof'])
                                    Sedang Diproses • Menunggu verifikasi admin
                                @else
                                    Menunggu • Upload bukti pembayaran
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Step 3: Fill Form -->
                    @if($progressSteps['admin_fee'])
                    <div class="flex items-center space-x-4 p-4 rounded-xl border {{ $progressSteps['fill_form'] ? 'bg-gradient-to-r from-green-50 to-green-100 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 {{ $progressSteps['fill_form'] ? 'bg-green-500' : 'bg-gray-300' }} rounded-full flex items-center justify-center text-white font-bold">
                                @if($progressSteps['fill_form'])
                                    ✓
                                @else
                                    3
                                @endif
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-900">Isi Formulir</h3>
                            <p class="text-sm {{ $progressSteps['fill_form'] ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $progressSteps['fill_form'] ? 'Selesai • Formulir berhasil disimpan' : 'Menunggu • Lengkapi data pendaftaran' }}
                            </p>
                        </div>
                    </div>
                    @endif

                    <!-- Step 4: Upload Documents -->
                    @if($progressSteps['fill_form'])
                    <div class="flex items-center space-x-4 p-4 rounded-xl border {{ $progressSteps['upload_docs'] ? 'bg-gradient-to-r from-green-50 to-green-100 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 {{ $progressSteps['upload_docs'] ? 'bg-green-500' : 'bg-gray-300' }} rounded-full flex items-center justify-center text-white font-bold">
                                @if($progressSteps['upload_docs'])
                                    ✓
                                @else
                                    4
                                @endif
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-900">Upload Dokumen</h3>
                            <p class="text-sm {{ $progressSteps['upload_docs'] ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $progressSteps['upload_docs'] ? 'Selesai • Dokumen berhasil diupload' : 'Menunggu • Upload dokumen pendukung' }}
                            </p>
                        </div>
                    </div>
                    @endif

                    <!-- Step 5: Waiting for Decision -->
                    @if($progressSteps['upload_docs'])
                    <div class="flex items-center space-x-4 p-4 rounded-xl border {{ $progressSteps['waiting'] ? 'bg-gradient-to-r from-green-50 to-green-100 border-green-200' : 'bg-gradient-to-r from-orange-50 to-orange-100 border-orange-200' }}">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 {{ $progressSteps['waiting'] ? 'bg-green-500' : 'bg-orange-500' }} rounded-full flex items-center justify-center text-white font-bold {{ !$progressSteps['waiting'] ? 'animate-pulse' : '' }}">
                                @if($progressSteps['waiting'])
                                    ✓
                                @else
                                    5
                                @endif
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-900">Menunggu Kelulusan</h3>
                            <p class="text-sm {{ $progressSteps['waiting'] ? 'text-green-600' : 'text-orange-600' }}">
                                @if($progressSteps['waiting'])
                                    Selesai • Hasil seleksi sudah keluar
                                @else
                                    Sedang Diproses • Admin sedang review dokumen
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif

                    <!-- Progress Summary -->
                    <div class="mt-8 p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <div class="text-center">
                            @php
                                // Count only the main steps (exclude upload_proof which is just a substep)
                                $completedSteps = 0;
                                $totalSteps = 5;
                                
                                if ($progressSteps['account']) $completedSteps++;
                                if ($progressSteps['admin_fee']) $completedSteps++;
                                if ($progressSteps['fill_form']) $completedSteps++;
                                if ($progressSteps['upload_docs']) $completedSteps++;
                                if ($progressSteps['waiting']) $completedSteps++;
                            @endphp
                            <div class="text-2xl font-bold text-blue-600 mb-1">
                                {{ $completedSteps }}/{{ $totalSteps }}
                            </div>
                            <p class="text-sm text-blue-700">Tahapan Selesai</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Status Card -->
        @if($registration)
        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Pendaftaran Anda</h2>
            <div class="bg-gradient-to-r {{ $registration->status === 'passed' ? 'from-green-50 to-green-100 border-green-200' : ($registration->status === 'failed' ? 'from-red-50 to-red-100 border-red-200' : 'from-blue-50 to-blue-100 border-blue-200') }} border rounded-lg p-4">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        @if($registration->status === 'passed')
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        @elseif($registration->status === 'failed')
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                        @else
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center animate-pulse">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-lg font-semibold {{ $registration->status === 'passed' ? 'text-green-800' : ($registration->status === 'failed' ? 'text-red-800' : 'text-blue-800') }}">
                            {{ $registration->status_label ?? ucfirst(str_replace('_', ' ', $registration->status)) }}
                        </p>
                        <p class="text-sm {{ $registration->status === 'passed' ? 'text-green-600' : ($registration->status === 'failed' ? 'text-red-600' : 'text-blue-600') }}">
                            No. Pendaftaran: {{ $registration->registration_number }}
                        </p>
                        @if($registration->failure_reason)
                        <p class="text-sm text-red-600 mt-1">Alasan: {{ $registration->failure_reason }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
            <!-- Payment Card -->
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Pembayaran</h3>
                        <p class="text-sm text-gray-600">Bayar biaya administrasi</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('registration.index') }}" class="block w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-center">
                        Bayar Sekarang
                    </a>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Formulir</h3>
                        <p class="text-sm text-gray-600">Isi formulir pendaftaran</p>
                    </div>
                </div>
                <div class="mt-4">
                    @if($registration && $registration->adminPayment && $registration->adminPayment->verification_status === 'approved')
                        <a href="{{ route('registration.form') }}" class="block w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors text-center">
                            Isi Formulir
                        </a>
                    @else
                        <button class="w-full bg-gray-400 text-white py-2 px-4 rounded-lg cursor-not-allowed" disabled>
                            Bayar Biaya Admin Dulu
                        </button>
                    @endif
                </div>
            </div>

            <!-- Documents Card -->
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Dokumen</h3>
                        <p class="text-sm text-gray-600">Upload dokumen pendukung</p>
                    </div>
                </div>
                <div class="mt-4">
                    @if($registration && $registration->form && $registration->form->is_completed)
                        <a href="{{ route('registration.form') }}" class="block w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition-colors text-center">
                            Upload Dokumen
                        </a>
                    @else
                        <button class="w-full bg-gray-400 text-white py-2 px-4 rounded-lg cursor-not-allowed" disabled>
                            Isi Formulir Dulu
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Important Information -->
        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Penting</h2>
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Batas Waktu Pendaftaran</h4>
                        <p class="text-sm text-gray-600">Pastikan melengkapi semua tahapan sebelum batas waktu yang telah ditentukan.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Bantuan dan Dukungan</h4>
                        <p class="text-sm text-gray-600">Jika mengalami kendala, hubungi tim support kami untuk mendapatkan bantuan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection