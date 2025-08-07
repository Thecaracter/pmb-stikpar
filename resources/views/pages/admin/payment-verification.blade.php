@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div x-data="paymentVerification()" x-init="init()" class="space-y-4 lg:space-y-6">
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

    <!-- Statistics Cards - UPDATE REAL-TIME -->
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
                    <p class="text-lg lg:text-2xl font-bold text-gray-900" x-text="stats.total">{{ $stats['total'] }}</p>
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
                    <p class="text-lg lg:text-2xl font-bold text-yellow-600" x-text="stats.pending">{{ $stats['pending'] }}</p>
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
                    <p class="text-lg lg:text-2xl font-bold text-green-600" x-text="stats.approved">{{ $stats['approved'] }}</p>
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
                    <p class="text-lg lg:text-2xl font-bold text-red-600" x-text="stats.rejected">{{ $stats['rejected'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="bg-white rounded-lg lg:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Tab Navigation - ALPINE.JS -->
        <div class="border-b border-gray-200">
            <nav class="flex overflow-x-auto scrollbar-hide px-4 lg:px-6" aria-label="Tabs">
                <button @click="setActiveTab('all')" 
                        :class="activeTab === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="border-b-2 py-3 lg:py-4 px-3 lg:px-1 text-sm font-medium whitespace-nowrap flex-shrink-0">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 lg:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span class="hidden sm:inline">Semua</span>
                        <span class="sm:hidden">All</span>
                        <span class="ml-1 lg:ml-2 bg-gray-100 text-gray-900 py-0.5 px-1.5 lg:px-2.5 rounded-full text-xs font-medium" x-text="stats.total">0</span>
                    </div>
                </button>
                
                <button @click="setActiveTab('pending')" 
                        :class="activeTab === 'pending' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="border-b-2 py-3 lg:py-4 px-3 lg:px-1 text-sm font-medium whitespace-nowrap flex-shrink-0 ml-4 lg:ml-8">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 lg:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="hidden sm:inline">Menunggu</span>
                        <span class="sm:hidden">Wait</span>
                        <span class="ml-1 lg:ml-2 bg-yellow-100 text-yellow-800 py-0.5 px-1.5 lg:px-2.5 rounded-full text-xs font-medium" x-text="stats.pending">0</span>
                    </div>
                </button>

                <button @click="setActiveTab('approved')" 
                        :class="activeTab === 'approved' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="border-b-2 py-3 lg:py-4 px-3 lg:px-1 text-sm font-medium whitespace-nowrap flex-shrink-0 ml-4 lg:ml-8">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 lg:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="hidden sm:inline">Disetujui</span>
                        <span class="sm:hidden">OK</span>
                        <span class="ml-1 lg:ml-2 bg-green-100 text-green-800 py-0.5 px-1.5 lg:px-2.5 rounded-full text-xs font-medium" x-text="stats.approved">0</span>
                    </div>
                </button>

                <button @click="setActiveTab('rejected')" 
                        :class="activeTab === 'rejected' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="border-b-2 py-3 lg:py-4 px-3 lg:px-1 text-sm font-medium whitespace-nowrap flex-shrink-0 ml-4 lg:ml-8">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 lg:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="hidden sm:inline">Ditolak</span>
                        <span class="sm:hidden">No</span>
                        <span class="ml-1 lg:ml-2 bg-red-100 text-red-800 py-0.5 px-1.5 lg:px-2.5 rounded-full text-xs font-medium" x-text="stats.rejected">0</span>
                    </div>
                </button>
            </nav>
        </div>

        <!-- Filters Section - ALPINE.JS REAL-TIME -->
        <div class="p-4 lg:p-6 bg-gray-50 border-b border-gray-200">
            <div class="space-y-4">
                <!-- Filter Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                    <!-- Search -->
                    <div class="sm:col-span-2 xl:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari Pembayaran</label>
                        <div class="relative">
                            <input type="text" 
                                   x-model="filters.search"
                                   @input="applyFilters()"
                                   placeholder="Nama, email, atau nomor..."
                                   class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Pembayaran</label>
                        <select x-model="filters.payment_type" @change="applyFilters()" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Tipe</option>
                            <option value="administration">Administrasi</option>
                            <option value="registration">Daftar Ulang</option>
                        </select>
                    </div>

                    <!-- Wave -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gelombang</label>
                        <select x-model="filters.wave_id" @change="applyFilters()" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Gelombang</option>
                            @foreach($waves as $wave)
                                <option value="{{ $wave->id }}">{{ $wave->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Path -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jalur</label>
                        <select x-model="filters.path_id" @change="applyFilters()" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Jalur</option>
                            @foreach($paths as $path)
                                <option value="{{ $path->id }}">{{ $path->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" 
                               x-model="filters.date_from"
                               @change="applyFilters()"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" 
                               x-model="filters.date_to"
                               @change="applyFilters()"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <button @click="resetFilters()" 
                                class="w-full sm:w-auto px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors text-sm">
                            Reset Filter
                        </button>
                        <button @click="refreshData()" 
                                class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Refresh
                        </button>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <button @click="bulkApprove()" 
                                x-show="selectedPayments.length > 0 && pendingCount > 0"
                                class="w-full sm:w-auto px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Setujui <span x-text="selectedPayments.length"></span> Terpilih
                        </button>
                        <button @click="exportData()" 
                                class="w-full sm:w-auto px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export CSV
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="flex items-center justify-center py-12">
            <div class="loader"></div>
            <span class="ml-3 text-gray-600">Memuat data...</span>
        </div>

        <!-- Payment Content -->
        <div x-show="!loading">
            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" @change="toggleSelectAll($event)" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registrasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembayaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="payment in paginatedPayments" :key="payment.id">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input x-show="payment.verification_status === 'pending'" 
                                           type="checkbox" 
                                           :value="payment.id"
                                           @change="togglePaymentSelection(payment.id, $event.target.checked)"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-full" 
                                             :src="getAvatarUrl(payment.user_name)" 
                                             :alt="payment.user_name">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900" x-text="payment.user_name"></div>
                                            <div class="text-sm text-gray-500" x-text="payment.user_email"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900" x-text="payment.registration_number"></div>
                                    <div class="text-sm text-gray-500" x-text="payment.wave_name + ' - ' + payment.path_name"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900" x-text="payment.formatted_amount"></div>
                                    <div class="text-sm text-gray-500" x-text="payment.payment_type_label"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span x-show="payment.verification_status === 'pending'" 
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        Menunggu Verifikasi
                                    </span>
                                    <span x-show="payment.verification_status === 'approved'" 
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Disetujui
                                    </span>
                                    <span x-show="payment.verification_status === 'rejected'" 
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Ditolak
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="payment.created_at"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a :href="payment.file_url" target="_blank" 
                                           class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Lihat
                                        </a>
                                        <button x-show="payment.verification_status === 'pending'" 
                                                @click="approvePayment(payment.id)"
                                                class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Setujui
                                        </button>
                                        <button x-show="payment.verification_status === 'pending'" 
                                                @click="rejectPayment(payment.id)"
                                                class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Tolak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="lg:hidden divide-y divide-gray-200">
                <template x-for="payment in paginatedPayments" :key="payment.id">
                    <div class="p-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 flex flex-col items-center space-y-2">
                                <input x-show="payment.verification_status === 'pending'" 
                                       type="checkbox" 
                                       :value="payment.id"
                                       @change="togglePaymentSelection(payment.id, $event.target.checked)"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <img class="h-10 w-10 rounded-full" 
                                     :src="getAvatarUrl(payment.user_name)" 
                                     :alt="payment.user_name">
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-medium text-gray-900 truncate" x-text="payment.user_name"></h3>
                                        <p class="text-sm text-gray-500 truncate" x-text="payment.user_email"></p>
                                    </div>
                                    <!-- Status Badge -->
                                    <span x-show="payment.verification_status === 'pending'" 
                                          class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Menunggu
                                    </span>
                                    <span x-show="payment.verification_status === 'approved'" 
                                          class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Disetujui
                                    </span>
                                    <span x-show="payment.verification_status === 'rejected'" 
                                          class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Ditolak
                                    </span>
                                </div>

                                <!-- Details -->
                                <div class="mt-2 space-y-1">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">No. Registrasi:</span>
                                        <span class="font-medium text-gray-900" x-text="payment.registration_number"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Gelombang:</span>
                                        <span class="text-gray-900" x-text="payment.wave_name"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Jalur:</span>
                                        <span class="text-gray-900" x-text="payment.path_name"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Jumlah:</span>
                                        <span class="font-medium text-gray-900" x-text="payment.formatted_amount"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Tipe:</span>
                                        <span class="text-gray-900" x-text="payment.payment_type_label"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Tanggal:</span>
                                        <span class="text-gray-900" x-text="payment.created_at"></span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <a :href="payment.file_url" target="_blank" 
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors text-sm">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Lihat
                                    </a>
                                    <button x-show="payment.verification_status === 'pending'" 
                                            @click="approvePayment(payment.id)"
                                            class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition-colors text-sm">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Setujui
                                    </button>
                                    <button x-show="payment.verification_status === 'pending'" 
                                            @click="rejectPayment(payment.id)"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors text-sm">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Tolak
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty State -->
            <div x-show="filteredPayments.length === 0" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data pembayaran</h3>
                <p class="mt-1 text-sm text-gray-500">
                    <span x-show="activeTab === 'pending'">Belum ada pembayaran yang perlu diverifikasi.</span>
                    <span x-show="activeTab === 'approved'">Belum ada pembayaran yang disetujui.</span>
                    <span x-show="activeTab === 'rejected'">Belum ada pembayaran yang ditolak.</span>
                    <span x-show="activeTab === 'all'">Belum ada data pembayaran yang sesuai dengan filter.</span>
                </p>
            </div>

            <!-- Pagination - ALPINE.JS -->
            <div x-show="totalPages > 1" class="px-4 lg:px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button @click="previousPage()" 
                                :disabled="currentPage === 1"
                                :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-300'"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white">
                            Previous
                        </button>
                        <button @click="nextPage()" 
                                :disabled="currentPage === totalPages"
                                :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-300'"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white">
                            Next
                        </button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Menampilkan <span class="font-medium" x-text="(currentPage - 1) * itemsPerPage + 1"></span> 
                                sampai <span class="font-medium" x-text="Math.min(currentPage * itemsPerPage, filteredPayments.length)"></span> 
                                dari <span class="font-medium" x-text="filteredPayments.length"></span> hasil
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <button @click="previousPage()" 
                                        :disabled="currentPage === 1"
                                        :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                
                                <template x-for="page in visiblePages" :key="page">
                                    <button @click="goToPage(page)" 
                                            :class="page === currentPage ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
                                            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                            x-text="page">
                                    </button>
                                </template>
                                
                                <button @click="nextPage()" 
                                        :disabled="currentPage === totalPages"
                                        :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .loader {
        border: 3px solid #f3f4f6;
        border-top: 3px solid #3b82f6;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    [x-cloak] { display: none !important; }
</style>
@endpush

@push('scripts')
<script>
function paymentVerification() {
    return {
        // Data
        allPayments: @json($transformedPayments),
        filteredPayments: [],
        
        // Filters
        filters: {
            search: '',
            payment_type: '',
            wave_id: '',
            path_id: '',
            date_from: '',
            date_to: ''
        },
        
        // Tabs & Pagination
        activeTab: 'all',
        currentPage: 1,
        itemsPerPage: 20,
        
        // Selection
        selectedPayments: [],
        
        // Stats
        stats: {
            total: {{ $stats['total'] }},
            pending: {{ $stats['pending'] }},
            approved: {{ $stats['approved'] }},
            rejected: {{ $stats['rejected'] }}
        },
        
        // UI State
        loading: false,
        
        // Initialize
        init() {
            this.applyFilters();
        },
        
        // Computed Properties
        get paginatedPayments() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.filteredPayments.slice(start, end);
        },
        
        get totalPages() {
            return Math.ceil(this.filteredPayments.length / this.itemsPerPage);
        },
        
        get visiblePages() {
            const pages = [];
            const maxVisible = 5;
            let start = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
            let end = Math.min(this.totalPages, start + maxVisible - 1);
            
            if (end - start + 1 < maxVisible) {
                start = Math.max(1, end - maxVisible + 1);
            }
            
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            return pages;
        },
        
        get pendingCount() {
            return this.filteredPayments.filter(p => p.verification_status === 'pending').length;
        },
        
        // Tab Management
        setActiveTab(tab) {
            this.activeTab = tab;
            this.currentPage = 1;
            this.selectedPayments = [];
            this.applyFilters();
        },
        
        // Filtering
        applyFilters() {
            this.loading = true;
            
            let filtered = [...this.allPayments];
            
            // Tab filter
            if (this.activeTab !== 'all') {
                filtered = filtered.filter(payment => 
                    payment.verification_status === this.activeTab
                );
            }
            
            // Search filter
            if (this.filters.search) {
                const search = this.filters.search.toLowerCase();
                filtered = filtered.filter(payment => 
                    payment.user_name.toLowerCase().includes(search) ||
                    payment.user_email.toLowerCase().includes(search) ||
                    payment.registration_number.toLowerCase().includes(search) ||
                    payment.file_name.toLowerCase().includes(search)
                );
            }
            
            // Payment type filter
            if (this.filters.payment_type) {
                filtered = filtered.filter(payment => 
                    payment.payment_type === this.filters.payment_type
                );
            }
            
            // Wave filter
            if (this.filters.wave_id) {
                filtered = filtered.filter(payment => 
                    payment.wave_id == this.filters.wave_id
                );
            }
            
            // Path filter
            if (this.filters.path_id) {
                filtered = filtered.filter(payment => 
                    payment.path_id == this.filters.path_id
                );
            }
            
            // Date filters
            if (this.filters.date_from) {
                const dateFrom = new Date(this.filters.date_from);
                filtered = filtered.filter(payment => {
                    const paymentDate = new Date(payment.created_at_full);
                    return paymentDate >= dateFrom;
                });
            }
            
            if (this.filters.date_to) {
                const dateTo = new Date(this.filters.date_to);
                dateTo.setHours(23, 59, 59, 999); // End of day
                filtered = filtered.filter(payment => {
                    const paymentDate = new Date(payment.created_at_full);
                    return paymentDate <= dateTo;
                });
            }
            
            this.filteredPayments = filtered;
            this.updateStats();
            this.loading = false;
            
            // Reset pagination
            if (this.currentPage > this.totalPages) {
                this.currentPage = 1;
            }
        },
        
        // Update stats berdasarkan filtered data
        updateStats() {
            this.stats = {
                total: this.filteredPayments.length,
                pending: this.filteredPayments.filter(p => p.verification_status === 'pending').length,
                approved: this.filteredPayments.filter(p => p.verification_status === 'approved').length,
                rejected: this.filteredPayments.filter(p => p.verification_status === 'rejected').length
            };
        },
        
        // Reset filters
        resetFilters() {
            this.filters = {
                search: '',
                payment_type: '',
                wave_id: '',
                path_id: '',
                date_from: '',
                date_to: ''
            };
            this.activeTab = 'all';
            this.currentPage = 1;
            this.selectedPayments = [];
            this.applyFilters();
        },
        
        // Refresh data dari server
        async refreshData() {
            this.loading = true;
            try {
                const response = await fetch('/admin/api/payments/list');
                const result = await response.json();
                
                if (result.success) {
                    this.allPayments = result.data;
                    this.applyFilters();
                    this.showNotification('Data berhasil diperbarui!', 'success');
                } else {
                    this.showNotification('Gagal memperbarui data', 'error');
                }
            } catch (error) {
                this.showNotification('Terjadi kesalahan', 'error');
                console.error(error);
            }
            this.loading = false;
        },
        
        // Pagination
        goToPage(page) {
            this.currentPage = page;
            this.selectedPayments = [];
        },
        
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.selectedPayments = [];
            }
        },
        
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.selectedPayments = [];
            }
        },
        
        // Selection
        toggleSelectAll(event) {
            if (event.target.checked) {
                this.selectedPayments = this.paginatedPayments
                    .filter(p => p.verification_status === 'pending')
                    .map(p => p.id);
            } else {
                this.selectedPayments = [];
            }
        },
        
        togglePaymentSelection(paymentId, checked) {
            if (checked) {
                if (!this.selectedPayments.includes(paymentId)) {
                    this.selectedPayments.push(paymentId);
                }
            } else {
                this.selectedPayments = this.selectedPayments.filter(id => id !== paymentId);
            }
        },
        
        // Payment Actions
        async approvePayment(paymentId) {
            if (!confirm('Yakin ingin menyetujui pembayaran ini?')) return;
            
            try {
                const response = await fetch(`/admin/api/payments/${paymentId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.showNotification(result.message, 'success');
                    await this.refreshData();
                } else {
                    this.showNotification(result.message, 'error');
                }
            } catch (error) {
                this.showNotification('Terjadi kesalahan', 'error');
                console.error(error);
            }
        },
        
        async rejectPayment(paymentId) {
            const reason = prompt('Masukkan alasan penolakan:');
            if (!reason) return;
            
            try {
                const response = await fetch(`/admin/api/payments/${paymentId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ reason })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.showNotification(result.message, 'success');
                    await this.refreshData();
                } else {
                    this.showNotification(result.message, 'error');
                }
            } catch (error) {
                this.showNotification('Terjadi kesalahan', 'error');
                console.error(error);
            }
        },
        
        async bulkApprove() {
            if (this.selectedPayments.length === 0) {
                this.showNotification('Pilih minimal satu pembayaran', 'warning');
                return;
            }
            
            if (!confirm(`Yakin ingin menyetujui ${this.selectedPayments.length} pembayaran?`)) return;
            
            try {
                const response = await fetch('/admin/api/payments/bulk-approve', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        payment_ids: this.selectedPayments
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.showNotification(result.message, 'success');
                    this.selectedPayments = [];
                    await this.refreshData();
                } else {
                    this.showNotification(result.message, 'error');
                }
            } catch (error) {
                this.showNotification('Terjadi kesalahan', 'error');
                console.error(error);
            }
        },
        
        // Export data
        // Export data - FIXED
async exportData() {
    try {
        const paymentIds = this.filteredPayments.map(p => p.id);
        
        // OPSI 1: Kirim sebagai JSON di body (recommended)
        const response = await fetch('/admin/api/payments/export', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                payment_ids: paymentIds  // ← Array langsung, bukan string JSON
            })
        });

        // Jika response adalah file stream
        if (response.ok && response.headers.get('Content-Type').includes('text/csv')) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `payments_${new Date().toISOString().slice(0, 10)}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            
            this.showNotification('File berhasil didownload!', 'success');
        } else {
            // Jika response adalah JSON error
            const result = await response.json();
            this.showNotification(result.message || 'Gagal export data', 'error');
        }
    } catch (error) {
        console.error('Export error:', error);
        this.showNotification('Gagal export data', 'error');
    }
},
        
        // Helper methods
        getAvatarUrl(name) {
            return `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&color=7F9CF5&background=EBF4FF`;
        },
        
        showNotification(message, type = 'info') {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };

            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full max-w-sm`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => notification.classList.remove('translate-x-full'), 100);
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }
    }
}
</script>
@endpush
@endsection