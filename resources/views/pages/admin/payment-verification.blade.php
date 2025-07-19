@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 rounded-lg lg:rounded-xl shadow-lg overflow-hidden">
        <div class="px-4 sm:px-6 lg:px-8 py-4 lg:py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-2xl lg:text-3xl font-bold text-white mb-1 lg:mb-2">Verifikasi Pembayaran</h1>
                    <p class="text-blue-100 text-sm lg:text-base">Kelola dan verifikasi bukti pembayaran mahasiswa</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-12 h-12 lg:w-16 lg:h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-6">
        <div class="bg-white rounded-lg lg:rounded-xl shadow-sm border border-gray-200 p-3 lg:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 lg:p-3 bg-blue-100 rounded-lg lg:rounded-xl mb-2 lg:mb-0 self-start">
                    <svg class="w-4 h-4 lg:w-6 lg:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4">
                    <p class="text-xs lg:text-sm font-medium text-gray-600">Total Pembayaran</p>
                    <p class="text-lg lg:text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg lg:rounded-xl shadow-sm border border-gray-200 p-3 lg:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 lg:p-3 bg-yellow-100 rounded-lg lg:rounded-xl mb-2 lg:mb-0 self-start">
                    <svg class="w-4 h-4 lg:w-6 lg:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4">
                    <p class="text-xs lg:text-sm font-medium text-gray-600">Menunggu Verifikasi</p>
                    <p class="text-lg lg:text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg lg:rounded-xl shadow-sm border border-gray-200 p-3 lg:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 lg:p-3 bg-green-100 rounded-lg lg:rounded-xl mb-2 lg:mb-0 self-start">
                    <svg class="w-4 h-4 lg:w-6 lg:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4">
                    <p class="text-xs lg:text-sm font-medium text-gray-600">Disetujui</p>
                    <p class="text-lg lg:text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg lg:rounded-xl shadow-sm border border-gray-200 p-3 lg:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 lg:p-3 bg-red-100 rounded-lg lg:rounded-xl mb-2 lg:mb-0 self-start">
                    <svg class="w-4 h-4 lg:w-6 lg:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="lg:ml-4">
                    <p class="text-xs lg:text-sm font-medium text-gray-600">Ditolak</p>
                    <p class="text-lg lg:text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation & Content Container -->
    <div class="bg-white rounded-lg lg:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Mobile Tab Navigation (Horizontal Scroll) -->
        <div class="border-b border-gray-200">
            <nav class="flex overflow-x-auto scrollbar-hide px-4 lg:px-6" aria-label="Tabs">
                <button onclick="switchTab('all')" id="tab-all" 
                        class="tab-button border-b-2 border-blue-500 py-3 lg:py-4 px-3 lg:px-1 text-sm font-medium text-blue-600 whitespace-nowrap flex-shrink-0">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 lg:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span class="hidden sm:inline">Semua</span>
                        <span class="sm:hidden">All</span>
                        <span class="ml-1 lg:ml-2 bg-gray-100 text-gray-900 py-0.5 px-1.5 lg:px-2.5 rounded-full text-xs font-medium">{{ $stats['total'] }}</span>
                    </div>
                </button>
                
                <button onclick="switchTab('pending')" id="tab-pending" 
                        class="tab-button border-b-2 border-transparent py-3 lg:py-4 px-3 lg:px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap flex-shrink-0 ml-4 lg:ml-8">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 lg:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="hidden sm:inline">Menunggu</span>
                        <span class="sm:hidden">Wait</span>
                        <span class="ml-1 lg:ml-2 bg-yellow-100 text-yellow-800 py-0.5 px-1.5 lg:px-2.5 rounded-full text-xs font-medium">{{ $stats['pending'] }}</span>
                    </div>
                </button>

                <button onclick="switchTab('approved')" id="tab-approved" 
                        class="tab-button border-b-2 border-transparent py-3 lg:py-4 px-3 lg:px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap flex-shrink-0 ml-4 lg:ml-8">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 lg:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="hidden sm:inline">Disetujui</span>
                        <span class="sm:hidden">OK</span>
                        <span class="ml-1 lg:ml-2 bg-green-100 text-green-800 py-0.5 px-1.5 lg:px-2.5 rounded-full text-xs font-medium">{{ $stats['approved'] }}</span>
                    </div>
                </button>

                <button onclick="switchTab('rejected')" id="tab-rejected" 
                        class="tab-button border-b-2 border-transparent py-3 lg:py-4 px-3 lg:px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap flex-shrink-0 ml-4 lg:ml-8">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 lg:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="hidden sm:inline">Ditolak</span>
                        <span class="sm:hidden">No</span>
                        <span class="ml-1 lg:ml-2 bg-red-100 text-red-800 py-0.5 px-1.5 lg:px-2.5 rounded-full text-xs font-medium">{{ $stats['rejected'] }}</span>
                    </div>
                </button>
            </nav>
        </div>

        <!-- Filters Section -->
        <div class="p-4 lg:p-6 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('admin.payments.index') }}" class="space-y-4">
                <input type="hidden" name="tab" id="current-tab" value="all">
                
                <!-- Mobile-first responsive grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="sm:col-span-2 lg:col-span-1">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Pembayaran</label>
                        <div class="relative">
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Nama, email, atau nomor..."
                                   class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Type Filter -->
                    <div>
                        <label for="payment_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Pembayaran</label>
                        <select id="payment_type" name="payment_type" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Tipe</option>
                            <option value="administration" {{ request('payment_type') === 'administration' ? 'selected' : '' }}>Administrasi</option>
                            <option value="registration" {{ request('payment_type') === 'registration' ? 'selected' : '' }}>Daftar Ulang</option>
                        </select>
                    </div>

                    <!-- Wave Filter -->
                    <div>
                        <label for="wave_id" class="block text-sm font-medium text-gray-700 mb-1">Gelombang</label>
                        <select id="wave_id" name="wave_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Gelombang</option>
                            @foreach($waves as $wave)
                                <option value="{{ $wave->id }}" {{ request('wave_id') == $wave->id ? 'selected' : '' }}>
                                    {{ $wave->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" 
                               id="date_from" 
                               name="date_from" 
                               value="{{ request('date_from') }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row sm:items-end gap-2">
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('admin.payments.index') }}" class="w-full sm:w-auto px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors text-center text-sm">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tab Content Header -->
        <div class="px-4 lg:px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900" id="tab-title">Semua Pembayaran</h2>
                    <p class="text-sm text-gray-500 mt-1" id="tab-description">Kelola semua pembayaran mahasiswa</p>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                    <div id="pending-actions" class="hidden">
                        <button onclick="bulkApprove()" 
                                class="w-full sm:w-auto px-3 lg:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm flex items-center justify-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Setujui Terpilih
                        </button>
                    </div>
                    <button onclick="refreshData()" class="w-full sm:w-auto px-3 lg:px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm flex items-center justify-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span class="hidden sm:inline">Refresh</span>
                        <span class="sm:hidden">Reload</span>
                    </button>
                    <a href="{{ route('admin.payments.export', request()->query()) }}" 
                       class="w-full sm:w-auto px-3 lg:px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm flex items-center justify-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="hidden sm:inline">Export CSV</span>
                        <span class="sm:hidden">Export</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Payment Table/Cards -->
        <div id="payment-content">
            @if($payments->count() > 0)
                <!-- Desktop Table View -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mahasiswa
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Registrasi
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pembayaran
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="payment-table-body">
                            @foreach($payments as $payment)
                                <tr class="hover:bg-gray-50 payment-row" data-status="{{ $payment->verification_status }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($payment->verification_status === 'pending')
                                            <input type="checkbox" name="selected_payments[]" value="{{ $payment->id }}" 
                                                   class="payment-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        @else
                                            <div class="w-4 h-4"></div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img class="h-10 w-10 rounded-full" 
                                                 src="https://ui-avatars.com/api/?name={{ urlencode($payment->registration->user->name) }}&color=7F9CF5&background=EBF4FF" 
                                                 alt="{{ $payment->registration->user->name }}">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $payment->registration->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $payment->registration->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $payment->registration->registration_number }}</div>
                                        <div class="text-sm text-gray-500">{{ $payment->registration->wave->name }} - {{ $payment->registration->path->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $payment->formatted_amount }}</div>
                                        <div class="text-sm text-gray-500">{{ $payment->payment_type_label }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($payment->verification_status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                Menunggu Verifikasi
                                            </span>
                                        @elseif($payment->verification_status === 'approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Disetujui
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payment->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ $payment->file_url }}" target="_blank" 
                                               class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Lihat
                                            </a>
                                            @if($payment->verification_status === 'pending')
                                                <button onclick="approvePayment({{ $payment->id }})" 
                                                        class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition-colors">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Setujui
                                                </button>
                                                <button onclick="rejectPayment({{ $payment->id }})" 
                                                        class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    Tolak
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="lg:hidden divide-y divide-gray-200">
                    @foreach($payments as $payment)
                        <div class="p-4 payment-row" data-status="{{ $payment->verification_status }}">
                            <div class="flex items-start space-x-3">
                                <!-- Checkbox/Avatar -->
                                <div class="flex-shrink-0 flex flex-col items-center space-y-2">
                                    @if($payment->verification_status === 'pending')
                                        <input type="checkbox" name="selected_payments[]" value="{{ $payment->id }}" 
                                               class="payment-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    @endif
                                    <img class="h-10 w-10 rounded-full" 
                                         src="https://ui-avatars.com/api/?name={{ urlencode($payment->registration->user->name) }}&color=7F9CF5&background=EBF4FF" 
                                         alt="{{ $payment->registration->user->name }}">
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <!-- Header -->
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium text-gray-900 truncate">{{ $payment->registration->user->name }}</h3>
                                            <p class="text-sm text-gray-500 truncate">{{ $payment->registration->user->email }}</p>
                                        </div>
                                        @if($payment->verification_status === 'pending')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                Menunggu
                                            </span>
                                        @elseif($payment->verification_status === 'approved')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Disetujui
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                                Ditolak
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Details -->
                                    <div class="mt-2 space-y-1">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">No. Registrasi:</span>
                                            <span class="font-medium text-gray-900">{{ $payment->registration->registration_number }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Gelombang:</span>
                                            <span class="text-gray-900">{{ $payment->registration->wave->name }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Jumlah:</span>
                                            <span class="font-medium text-gray-900">{{ $payment->formatted_amount }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Tipe:</span>
                                            <span class="text-gray-900">{{ $payment->payment_type_label }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Tanggal:</span>
                                            <span class="text-gray-900">{{ $payment->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <a href="{{ $payment->file_url }}" target="_blank" 
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors text-sm">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Lihat
                                        </a>
                                        @if($payment->verification_status === 'pending')
                                            <button onclick="approvePayment({{ $payment->id }})" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition-colors text-sm">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Setujui
                                            </button>
                                            <button onclick="rejectPayment({{ $payment->id }})" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors text-sm">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Tolak
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-4 lg:px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $payments->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data pembayaran</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada pembayaran yang perlu diverifikasi.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Hide scrollbar for horizontal tab scroll */
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    /* Responsive table improvements */
    @media (max-width: 1023px) {
        .payment-row {
            transition: background-color 0.2s ease;
        }
        .payment-row:hover {
            background-color: #f9fafb;
        }
    }

    /* Touch-friendly buttons on mobile */
    @media (max-width: 640px) {
        button, .button-like {
            min-height: 44px;
            min-width: 44px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let currentActiveTab = 'all';

    // Tab switching functionality
    function switchTab(tabName) {
        currentActiveTab = tabName;
        
        // Update tab appearance
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('border-blue-500', 'text-blue-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        
        document.getElementById(`tab-${tabName}`).classList.remove('border-transparent', 'text-gray-500');
        document.getElementById(`tab-${tabName}`).classList.add('border-blue-500', 'text-blue-600');
        
        // Update form hidden input
        document.getElementById('current-tab').value = tabName;
        
        // Update content
        updateTabContent(tabName);
        filterPaymentRows(tabName);
    }

    function updateTabContent(tabName) {
        const titles = {
            'all': 'Semua Pembayaran',
            'pending': 'Menunggu Verifikasi',
            'approved': 'Pembayaran Disetujui',
            'rejected': 'Pembayaran Ditolak'
        };
        
        const descriptions = {
            'all': 'Kelola semua pembayaran mahasiswa',
            'pending': 'Verifikasi pembayaran yang masih menunggu persetujuan',
            'approved': 'Pembayaran yang telah disetujui',
            'rejected': 'Pembayaran yang telah ditolak'
        };
        
        document.getElementById('tab-title').textContent = titles[tabName];
        document.getElementById('tab-description').textContent = descriptions[tabName];
        
        // Show/hide pending actions
        const pendingActions = document.getElementById('pending-actions');
        if (tabName === 'pending' || tabName === 'all') {
            pendingActions.classList.remove('hidden');
        } else {
            pendingActions.classList.add('hidden');
        }
    }

    function filterPaymentRows(status) {
        const rows = document.querySelectorAll('.payment-row');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            
            if (status === 'all' || rowStatus === status) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Select All Checkbox (Desktop only)
    const selectAllCheckbox = document.getElementById('select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.payment-checkbox');
            checkboxes.forEach(checkbox => {
                if (checkbox.closest('.payment-row').style.display !== 'none') {
                    checkbox.checked = this.checked;
                }
            });
        });
    }

    // Individual checkboxes
    document.querySelectorAll('.payment-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (selectAllCheckbox) {
                const visibleCheckboxes = Array.from(document.querySelectorAll('.payment-checkbox'))
                    .filter(cb => cb.closest('.payment-row').style.display !== 'none');
                const checkedVisibleCheckboxes = visibleCheckboxes.filter(cb => cb.checked);
                
                selectAllCheckbox.checked = visibleCheckboxes.length === checkedVisibleCheckboxes.length && visibleCheckboxes.length > 0;
                selectAllCheckbox.indeterminate = checkedVisibleCheckboxes.length > 0 && checkedVisibleCheckboxes.length < visibleCheckboxes.length;
            }
        });
    });

    // Approve Payment
    function approvePayment(paymentId) {
        if (!confirm('Yakin ingin menyetujui pembayaran ini?')) return;

        fetch(`/admin/payments/${paymentId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                notes: ''
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan sistem', 'error');
        });
    }

    // Reject Payment
    function rejectPayment(paymentId) {
        const reason = prompt('Masukkan alasan penolakan:');
        if (!reason) return;

        fetch(`/admin/payments/${paymentId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                reason: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan sistem', 'error');
        });
    }

    // Bulk Approve
    function bulkApprove() {
        const visibleCheckboxes = Array.from(document.querySelectorAll('.payment-checkbox'))
            .filter(cb => cb.closest('.payment-row').style.display !== 'none');
        const checkedCheckboxes = visibleCheckboxes.filter(cb => cb.checked);
        
        if (checkedCheckboxes.length === 0) {
            showNotification('Pilih minimal satu pembayaran', 'warning');
            return;
        }

        if (!confirm(`Yakin ingin menyetujui ${checkedCheckboxes.length} pembayaran?`)) return;

        const paymentIds = checkedCheckboxes.map(cb => cb.value);

        fetch('/admin/payments/bulk-approve', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                payment_ids: paymentIds,
                notes: ''
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan sistem', 'error');
        });
    }

    // Refresh Data
    function refreshData() {
        location.reload();
    }

    // Show Notification
    function showNotification(message, type = 'info') {
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500'
        };

        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-4 lg:px-6 py-3 rounded-lg shadow-lg z-50 transform transition-transform duration-300 translate-x-full max-w-sm`;
        notification.innerHTML = `
            <div class="flex items-center">
                <span class="text-sm lg:text-base">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);
    }

    // Initialize tab on page load
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const tabFromUrl = urlParams.get('tab') || 'all';
        switchTab(tabFromUrl);
    });
</script>
@endpush
@endsection