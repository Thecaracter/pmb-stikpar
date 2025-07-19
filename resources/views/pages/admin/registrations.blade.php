@extends('layouts.app')

@section('title', 'Data Pendaftar')

@section('content')
<div class="space-y-4 md:space-y-6" x-data="registrationManager()">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-4 md:p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-2 md:space-y-0">
            <div>
                <h1 class="text-xl md:text-2xl font-bold">Data Pendaftar</h1>
                <p class="text-blue-100 text-sm md:text-base mt-1">Kelola semua data pendaftar mahasiswa baru</p>
            </div>
            <div class="text-left md:text-right">
                <p class="text-xs md:text-sm text-blue-100">Total Pendaftar</p>
                <p class="text-2xl md:text-3xl font-bold">{{ $registrations->total() }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3 md:gap-4">
        <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 text-center border border-gray-200 hover:shadow-md transition-shadow duration-200">
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <p class="text-xs text-gray-500 uppercase font-medium mb-1">Total</p>
                <p class="text-lg md:text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 text-center border border-gray-200 hover:shadow-md transition-shadow duration-200">
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mb-2">
                    <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                </div>
                <p class="text-xs text-gray-500 uppercase font-medium mb-1">Pending</p>
                <p class="text-lg md:text-2xl font-bold text-gray-600">{{ $stats['pending'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 text-center border border-yellow-200 hover:shadow-md transition-shadow duration-200">
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <p class="text-xs text-yellow-600 uppercase font-medium mb-1">Bayar</p>
                <p class="text-lg md:text-2xl font-bold text-yellow-600">{{ $stats['waiting_payment'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 text-center border border-blue-200 hover:shadow-md transition-shadow duration-200">
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <p class="text-xs text-blue-600 uppercase font-medium mb-1">Dokumen</p>
                <p class="text-lg md:text-2xl font-bold text-blue-600">{{ $stats['waiting_documents'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 text-center border border-orange-200 hover:shadow-md transition-shadow duration-200">
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-xs text-orange-600 uppercase font-medium mb-1">Keputusan</p>
                <p class="text-lg md:text-2xl font-bold text-orange-600">{{ $stats['waiting_decision'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 text-center border border-green-200 hover:shadow-md transition-shadow duration-200">
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-xs text-green-600 uppercase font-medium mb-1">Lulus</p>
                <p class="text-lg md:text-2xl font-bold text-green-600">{{ $stats['passed'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 text-center border border-red-200 hover:shadow-md transition-shadow duration-200">
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <p class="text-xs text-red-600 uppercase font-medium mb-1">Gagal</p>
                <p class="text-lg md:text-2xl font-bold text-red-600">{{ $stats['failed'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <form method="GET" class="space-y-4">
            <!-- Search bar - full width on mobile -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Pendaftar</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nama, email, atau nomor registrasi..." 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm md:text-base">
            </div>
            
            <!-- Filters in grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm md:text-base">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="waiting_payment" {{ request('status') == 'waiting_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="waiting_documents" {{ request('status') == 'waiting_documents' ? 'selected' : '' }}>Menunggu Dokumen</option>
                        <option value="waiting_decision" {{ request('status') == 'waiting_decision' ? 'selected' : '' }}>Menunggu Keputusan</option>
                        <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>Lulus</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gelombang</label>
                    <select name="wave_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm md:text-base">
                        <option value="">Semua Gelombang</option>
                        @foreach($waves as $wave)
                            <option value="{{ $wave->id }}" {{ request('wave_id') == $wave->id ? 'selected' : '' }}>
                                {{ $wave->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm md:text-base">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
            <div class="flex flex-wrap items-center gap-2 md:gap-4">
                <span class="text-sm text-gray-600 font-medium">Aksi untuk yang dipilih:</span>
                <div class="flex flex-wrap gap-2">
                    <button @click="handleBulkAction('accept')" class="bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 text-xs md:text-sm font-medium transition-colors">
                        <svg class="w-3 h-3 md:w-4 md:h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Terima
                    </button>
                    <button @click="handleBulkAction('reject')" class="bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700 text-xs md:text-sm font-medium transition-colors">
                        <svg class="w-3 h-3 md:w-4 md:h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Tolak
                    </button>
                    <button @click="handleBulkAction('delete')" class="bg-gray-600 text-white px-3 py-2 rounded-lg hover:bg-gray-700 text-xs md:text-sm font-medium transition-colors">
                        <svg class="w-3 h-3 md:w-4 md:h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus
                    </button>
                </div>
            </div>
            <div class="text-xs md:text-sm text-gray-500">
                {{ $registrations->firstItem() ?? 0 }} - {{ $registrations->lastItem() ?? 0 }} dari {{ $registrations->total() }} data
            </div>
        </div>
    </div>

    <!-- Data Table/Cards -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Reg</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gelombang</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($registrations as $registration)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" x-model="selectedIds" value="{{ $registration->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-mono font-medium text-gray-900">{{ $registration->registration_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">{{ substr($registration->user->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $registration->user->name }}</div>
                                    @if($registration->form)
                                        <div class="text-sm text-gray-500">{{ $registration->form->full_name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $registration->user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $registration->wave->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {!! getStatusBadge($registration->status) !!}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button @click="viewDetail({{ $registration->id }})" 
                                        class="text-blue-600 hover:text-blue-900 hover:bg-blue-50 p-2 rounded transition-colors" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <button @click="changeStatus({{ $registration->id }}, '{{ $registration->status }}')" 
                                        class="text-green-600 hover:text-green-900 hover:bg-green-50 p-2 rounded transition-colors" title="Ubah Status">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button @click="deleteRegistration({{ $registration->id }})" 
                                        class="text-red-600 hover:text-red-900 hover:bg-red-50 p-2 rounded transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <h3 class="text-sm font-medium text-gray-900 mb-1">Tidak ada data pendaftar</h3>
                                <p class="text-sm text-gray-500">Tidak ada pendaftar yang ditemukan dengan kriteria pencarian saat ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="lg:hidden">
            @forelse($registrations as $registration)
            <div class="border-b border-gray-200 p-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" x-model="selectedIds" value="{{ $registration->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mt-1">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center">
                                <span class="text-sm font-medium text-white">{{ substr($registration->user->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $registration->user->name }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ $registration->user->email }}</div>
                            <div class="text-xs font-mono text-gray-400 mt-1">{{ $registration->registration_number }}</div>
                        </div>
                    </div>
                    {!! getStatusBadge($registration->status) !!}
                </div>
                
                <div class="grid grid-cols-2 gap-2 text-xs text-gray-500 mb-3">
                    <div>
                        <span class="font-medium">Gelombang:</span> {{ $registration->wave->name ?? '-' }}
                    </div>
                    <div>
                        <span class="font-medium">Jalur:</span> {{ $registration->path->name ?? '-' }}
                    </div>
                    @if($registration->form)
                    <div class="col-span-2">
                        <span class="font-medium">Nama Lengkap:</span> {{ $registration->form->full_name }}
                    </div>
                    @endif
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex space-x-2">
                        <button @click="viewDetail({{ $registration->id }})" 
                                class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                            Lihat
                        </button>
                        <button @click="changeStatus({{ $registration->id }}, '{{ $registration->status }}')" 
                                class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                            Ubah
                        </button>
                        <button @click="deleteRegistration({{ $registration->id }})" 
                                class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="text-sm font-medium text-gray-900 mb-1">Tidak ada data pendaftar</h3>
                <p class="text-sm text-gray-500">Tidak ada pendaftar yang ditemukan dengan kriteria pencarian saat ini.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($registrations->hasPages())
            <div class="px-4 md:px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>

    <!-- Modal untuk Detail Pendaftar -->
    <div x-show="showDetailModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" 
         x-cloak
         @click.away="showDetailModal = false">
        
        <div x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             @click.stop
             class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden">
            
            <!-- Header - Fixed -->
            <div class="flex items-center justify-between p-4 md:p-6 border-b border-gray-200 bg-white rounded-t-2xl flex-shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg md:text-xl font-bold text-gray-900">Detail Pendaftar</h3>
                        <p class="text-sm text-gray-500">Informasi lengkap data pendaftaran</p>
                    </div>
                </div>
                <button @click="showDetailModal = false" 
                        class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Content - Scrollable -->
            <div class="flex-1 overflow-y-auto overscroll-contain modal-scroll">
                <div class="p-4 md:p-6" x-html="detailContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Ubah Status -->
    <div x-show="showStatusModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50" 
         x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="bg-white rounded-xl shadow-xl w-full max-w-md">
                <div class="flex items-center p-6 border-b">
                    <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">Ubah Status Pendaftaran</h3>
                </div>
                <form @submit.prevent="submitStatusChange()" class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status:</label>
                        <select x-model="statusForm.status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="pending">Pending</option>
                            <option value="waiting_payment">Menunggu Pembayaran</option>
                            <option value="waiting_documents">Menunggu Dokumen</option>
                            <option value="waiting_decision">Menunggu Keputusan</option>
                            <option value="passed">Lulus</option>
                            <option value="failed">Gagal</option>
                        </select>
                    </div>
                    <div x-show="statusForm.status === 'failed'" class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan:</label>
                        <textarea x-model="statusForm.failureReason" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                  rows="3" placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="showStatusModal = false" 
                                class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk Bulk Action -->
    <div x-show="showBulkModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50" 
         x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="bg-white rounded-xl shadow-xl w-full max-w-md">
                <div class="flex items-center p-6 border-b">
                    <svg class="h-6 w-6 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Aksi</h3>
                </div>
                <form @submit.prevent="submitBulkAction()" class="p-6">
                    <p x-text="bulkForm.message" class="mb-4 text-gray-700"></p>
                    <div x-show="bulkForm.action === 'reject'" class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan:</label>
                        <textarea x-model="bulkForm.failureReason" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                  rows="3" placeholder="Jelaskan alasan penolakan untuk semua pendaftar yang dipilih..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="showBulkModal = false" 
                                class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit" 
                                :class="bulkForm.buttonClass"
                                class="px-6 py-2 text-white rounded-lg font-medium transition-colors">
                            <span x-text="bulkForm.buttonText"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@php
/**
 * Status Badge Helper Function
 * Returns HTML for status badges
 */
function getStatusBadge($status) {
    $badges = [
        'pending' => '<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
            <div class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>
            Pending
        </span>',
        
        'waiting_payment' => '<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
            <div class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></div>
            Menunggu Pembayaran
        </span>',
        
        'waiting_documents' => '<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
            <div class="w-2 h-2 bg-blue-400 rounded-full mr-2"></div>
            Menunggu Dokumen
        </span>',
        
        'waiting_decision' => '<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
            <div class="w-2 h-2 bg-orange-400 rounded-full mr-2 animate-pulse"></div>
            Menunggu Keputusan
        </span>',
        
        'passed' => '<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
            Lulus
        </span>',
        
        'failed' => '<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
            Gagal
        </span>'
    ];

    return $badges[$status] ?? '<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">'.ucfirst($status).'</span>';
}
@endphp

<script>
function registrationManager() {
    return {
        // State
        selectAll: false,
        selectedIds: [],
        showDetailModal: false,
        showStatusModal: false,
        showBulkModal: false,
        detailContent: '',
        
        // Forms
        statusForm: {
            id: null,
            status: 'pending',
            failureReason: ''
        },
        
        bulkForm: {
            action: '',
            message: '',
            buttonClass: '',
            buttonText: '',
            failureReason: ''
        },

        // Helper Functions - MOVED TO TOP
        getStatusBadgeHTML(status) {
            const badges = {
                'pending': '<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800"><div class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>Pending</span>',
                'waiting_payment': '<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800"><div class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></div>Menunggu Pembayaran</span>',
                'waiting_documents': '<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800"><div class="w-2 h-2 bg-blue-400 rounded-full mr-2"></div>Menunggu Dokumen</span>',
                'waiting_decision': '<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800"><div class="w-2 h-2 bg-orange-400 rounded-full mr-2 animate-pulse"></div>Menunggu Keputusan</span>',
                'passed': '<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>Lulus</span>',
                'failed': '<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>Gagal</span>'
            };
            return badges[status] || `<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">${status}</span>`;
        },

        getVerificationBadge(status) {
            const badges = {
                'pending': '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>',
                'approved': '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Disetujui</span>',
                'rejected': '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Ditolak</span>',
            };
            return badges[status] || badges['pending'];
        },

        getFileIcon(mimeType) {
            if (mimeType.includes('pdf')) {
                return `
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                `;
            } else if (mimeType.includes('image')) {
                return `
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                `;
            } else {
                return `
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                `;
            }
        },

        // Methods
        toggleSelectAll() {
            if (this.selectAll) {
                // Select all checkboxes
                const checkboxes = document.querySelectorAll('input[x-model="selectedIds"]');
                this.selectedIds = Array.from(checkboxes).map(cb => cb.value);
            } else {
                // Deselect all
                this.selectedIds = [];
            }
        },

        async viewDetail(id) {
            this.showDetailModal = true;
            this.detailContent = `
                <div class="flex justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                </div>
            `;
            
            try {
                const response = await fetch(`/admin/registrations/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });
                
                if (!response.ok) throw new Error('Network response was not ok');
                
                const result = await response.json();
                
                if (result.success && result.data) {
                    const data = result.data;
                    const statusBadgeHTML = this.getStatusBadgeHTML(data.status);
                    
                    // Process documents dengan context yang benar
                    let documentsHTML = '';
                    if (data.documents && data.documents.length > 0) {
                        documentsHTML = data.documents.map(doc => {
                            const fileIcon = this.getFileIcon(doc.mime_type);
                            const verificationBadge = this.getVerificationBadge(doc.verification_status);
                            return `
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:border-gray-300 hover:shadow-sm transition-all">
                                    <div class="flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                                        <div class="flex items-center space-x-3 flex-1">
                                            <div class="flex-shrink-0">
                                                ${fileIcon}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h5 class="text-sm font-semibold text-gray-900 truncate">${doc.document_name}</h5>
                                                <p class="text-xs text-gray-500 truncate">${doc.file_name}</p>
                                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                                    <span class="text-xs text-gray-400 bg-gray-200 px-2 py-1 rounded">${doc.file_size}</span>
                                                    <span class="text-xs text-gray-400">${doc.uploaded_at}</span>
                                                    ${verificationBadge}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-center sm:justify-end space-x-2 flex-shrink-0">
                                            <button onclick="window.open('${doc.download_url}', '_blank')" 
                                                    class="flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors" 
                                                    title="Lihat Dokumen">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            <a href="${doc.download_url}" download="${doc.file_name}"
                                               class="flex items-center justify-center w-8 h-8 text-green-600 hover:text-green-800 bg-green-50 hover:bg-green-100 rounded-lg transition-colors" 
                                               title="Download">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    ${doc.verification_notes ? `
                                        <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                            <div class="flex items-start">
                                                <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                                </svg>
                                                <div>
                                                    <h6 class="text-xs font-medium text-blue-800 uppercase tracking-wide">Catatan Admin</h6>
                                                    <p class="text-sm text-blue-700 mt-1">${doc.verification_notes}</p>
                                                </div>
                                            </div>
                                        </div>
                                    ` : ''}
                                </div>
                            `;
                        }).join('');
                    } else {
                        documentsHTML = `
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h5 class="text-sm font-medium text-gray-900 mb-1">Belum ada dokumen</h5>
                                <p class="text-xs text-gray-500">Pendaftar belum mengupload dokumen apapun</p>
                            </div>
                        `;
                    }
                    this.detailContent = `
                        <div class="space-y-4 md:space-y-6">
                            <!-- Header Info Card -->
                            <div class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 rounded-xl p-4 md:p-6 border border-blue-200">
                                <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                                    <div class="flex-shrink-0 mx-auto sm:mx-0">
                                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                                            <span class="text-xl md:text-2xl font-bold text-white">${data.user.name.charAt(0).toUpperCase()}</span>
                                        </div>
                                    </div>
                                    <div class="text-center sm:text-left flex-1">
                                        <h3 class="text-lg md:text-xl font-bold text-gray-900">${data.user.name}</h3>
                                        <p class="text-sm md:text-base text-gray-600 font-mono">${data.registration_number}</p>
                                        <div class="mt-2 flex justify-center sm:justify-start">${statusBadgeHTML}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Grid - Responsive -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
                                <!-- Informasi Dasar -->
                                <div class="bg-white rounded-xl p-4 md:p-5 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center mb-4">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="font-semibold text-gray-900">Informasi Dasar</h4>
                                    </div>
                                    <div class="space-y-3">
                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                            <span class="text-sm text-gray-600 font-medium">Email:</span>
                                            <span class="text-sm font-medium text-gray-900 break-all">${data.user.email}</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                            <span class="text-sm text-gray-600 font-medium">Gelombang:</span>
                                            <span class="text-sm font-medium text-gray-900">${data.wave.name}</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                            <span class="text-sm text-gray-600 font-medium">Jalur:</span>
                                            <span class="text-sm font-medium text-gray-900">${data.path.name}</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                            <span class="text-sm text-gray-600 font-medium">Biaya Admin:</span>
                                            <span class="text-sm font-medium text-gray-900">${data.admin_fee_paid ? 'Rp ' + new Intl.NumberFormat('id-ID').format(data.admin_fee_paid) : '-'}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Data Formulir -->
                                <div class="bg-white rounded-xl p-4 md:p-5 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center mb-4">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="font-semibold text-gray-900">Data Formulir</h4>
                                    </div>
                                    ${data.form ? `
                                        <div class="space-y-3">
                                            <div class="flex flex-col sm:flex-row sm:justify-between">
                                                <span class="text-sm text-gray-600 font-medium">Nama Lengkap:</span>
                                                <span class="text-sm font-medium text-gray-900">${data.form.full_name}</span>
                                            </div>
                                            <div class="flex flex-col sm:flex-row sm:justify-between">
                                                <span class="text-sm text-gray-600 font-medium">No. HP:</span>
                                                <span class="text-sm font-medium text-gray-900">${data.form.phone_number || '-'}</span>
                                            </div>
                                            <div class="flex flex-col sm:flex-row sm:justify-between">
                                                <span class="text-sm text-gray-600 font-medium">Tempat Lahir:</span>
                                                <span class="text-sm font-medium text-gray-900">${data.form.birth_place || '-'}</span>
                                            </div>
                                            <div class="flex flex-col sm:flex-row sm:justify-between">
                                                <span class="text-sm text-gray-600 font-medium">Jenis Kelamin:</span>
                                                <span class="text-sm font-medium text-gray-900">${data.form.gender === 'M' ? 'Laki-laki' : data.form.gender === 'F' ? 'Perempuan' : '-'}</span>
                                            </div>
                                        </div>
                                    ` : `
                                        <div class="text-center py-8">
                                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <p class="text-sm text-gray-500 italic">Formulir belum diisi</p>
                                        </div>
                                    `}
                                </div>
                            </div>

                            <!-- Dokumen Upload Section -->
                            <div class="bg-white rounded-xl p-4 md:p-6 border border-gray-200 shadow-sm">
                                <div class="flex items-center justify-between mb-4 md:mb-6">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="font-semibold text-gray-900">Dokumen Upload</h4>
                                    </div>
                                    <div class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-1 rounded-full">
                                        ${data.documents ? data.documents.length : 0} file
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    ${documentsHTML}
                                </div>
                            </div>

                            <!-- Timeline Section -->
                            <div class="bg-white rounded-xl p-4 md:p-6 border border-gray-200 shadow-sm">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-gray-900">Timeline Pendaftaran</h4>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-gray-100">
                                        <span class="text-sm text-gray-600 font-medium">Tanggal Daftar:</span>
                                        <span class="text-sm font-medium text-gray-900">${data.created_at}</span>
                                    </div>
                                    ${data.payment_date ? `
                                        <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-gray-100">
                                            <span class="text-sm text-gray-600 font-medium">Tanggal Bayar:</span>
                                            <span class="text-sm font-medium text-gray-900">${new Date(data.payment_date).toLocaleDateString('id-ID')}</span>
                                        </div>
                                    ` : ''}
                                    ${data.document_submitted_at ? `
                                        <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-gray-100">
                                            <span class="text-sm text-gray-600 font-medium">Submit Dokumen:</span>
                                            <span class="text-sm font-medium text-gray-900">${new Date(data.document_submitted_at).toLocaleDateString('id-ID')}</span>
                                        </div>
                                    ` : ''}
                                    ${data.passed_at ? `
                                        <div class="flex flex-col sm:flex-row sm:justify-between py-2">
                                            <span class="text-sm text-gray-600 font-medium">Tanggal Lulus:</span>
                                            <span class="text-sm font-semibold text-green-600">${new Date(data.passed_at).toLocaleDateString('id-ID')}</span>
                                        </div>
                                    ` : ''}
                                    ${data.failure_reason ? `
                                        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                            <div class="flex items-start">
                                                <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                                <div>
                                                    <h5 class="text-sm font-medium text-red-800">Alasan Penolakan:</h5>
                                                    <p class="text-sm text-red-700 mt-1">${data.failure_reason}</p>
                                                </div>
                                            </div>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    throw new Error(result.message || 'Data tidak valid');
                }
            } catch (error) {
                console.error('Error:', error);
                this.detailContent = `
                    <div class="text-center py-8">
                        <div class="mb-4">
                            <svg class="h-12 w-12 text-red-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="text-red-600 font-medium mb-2">Gagal memuat data</div>
                        <p class="text-sm text-gray-500 mb-4">${error.message}</p>
                        <button @click="viewDetail(${id})" class="text-blue-600 hover:text-blue-800 text-sm font-medium px-4 py-2 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            Coba lagi
                        </button>
                    </div>
                `;
            }
        },

        getVerificationBadge(status) {
            const badges = {
                'pending': '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>',
                'approved': '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Disetujui</span>',
                'rejected': '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Ditolak</span>',
            };
            return badges[status] || badges['pending'];
        },

        getFileIcon(mimeType) {
            if (mimeType.includes('pdf')) {
                return `
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                `;
            } else if (mimeType.includes('image')) {
                return `
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                `;
            } else {
                return `
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                `;
            }
        },

        changeStatus(id, currentStatus) {
            this.statusForm.id = id;
            this.statusForm.status = currentStatus;
            this.statusForm.failureReason = '';
            this.showStatusModal = true;
        },

        async submitStatusChange() {
            if (this.statusForm.status === 'failed' && !this.statusForm.failureReason.trim()) {
                alert('Alasan penolakan harus diisi untuk status gagal!');
                return;
            }

            try {
                const formData = new FormData();
                formData.append('status', this.statusForm.status);
                if (this.statusForm.status === 'failed' && this.statusForm.failureReason) {
                    formData.append('failure_reason', this.statusForm.failureReason);
                }
                formData.append('_method', 'PUT');
                formData.append('_token', '{{ csrf_token() }}');

                const response = await fetch(`/admin/registrations/${this.statusForm.id}/status`, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                
                if (data.success) {
                    alert('Status berhasil diubah!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan!');
                console.error(error);
            }

            this.showStatusModal = false;
        },

        handleBulkAction(action) {
            if (this.selectedIds.length === 0) {
                alert('Pilih minimal satu pendaftar!');
                return;
            }

            const count = this.selectedIds.length;
            this.bulkForm.action = action;

            switch (action) {
                case 'accept':
                    this.bulkForm.message = `Apakah Anda yakin ingin menerima ${count} pendaftar yang dipilih?`;
                    this.bulkForm.buttonClass = 'bg-green-600 hover:bg-green-700';
                    this.bulkForm.buttonText = 'Terima';
                    break;
                case 'reject':
                    this.bulkForm.message = `Apakah Anda yakin ingin menolak ${count} pendaftar yang dipilih?`;
                    this.bulkForm.buttonClass = 'bg-red-600 hover:bg-red-700';
                    this.bulkForm.buttonText = 'Tolak';
                    break;
                case 'delete':
                    this.bulkForm.message = `Apakah Anda yakin ingin menghapus ${count} pendaftar yang dipilih? Data akan dihapus permanen dan tidak dapat dikembalikan!`;
                    this.bulkForm.buttonClass = 'bg-gray-600 hover:bg-gray-700';
                    this.bulkForm.buttonText = 'Hapus';
                    break;
            }

            this.bulkForm.failureReason = '';
            this.showBulkModal = true;
        },

        async submitBulkAction() {
            if (this.bulkForm.action === 'reject' && !this.bulkForm.failureReason.trim()) {
                alert('Alasan penolakan harus diisi untuk aksi tolak!');
                return;
            }

            try {
                const formData = new FormData();
                formData.append('action', this.bulkForm.action);
                formData.append('registrations', JSON.stringify(this.selectedIds));
                if (this.bulkForm.action === 'reject' && this.bulkForm.failureReason) {
                    formData.append('failure_reason', this.bulkForm.failureReason);
                }
                formData.append('_token', '{{ csrf_token() }}');

                const response = await fetch('/admin/registrations/bulk-action', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan!');
                console.error(error);
            }

            this.showBulkModal = false;
        },

        async deleteRegistration(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus pendaftar ini? Data akan dihapus permanen!')) {
                return;
            }

            try {
                const formData = new FormData();
                formData.append('_method', 'DELETE');
                formData.append('_token', '{{ csrf_token() }}');

                const response = await fetch(`/admin/registrations/${id}`, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                
                if (data.success) {
                    alert('Data berhasil dihapus!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan!');
                console.error(error);
            }
        }
    }
}
</script>

<style>
/* Custom responsive utilities */
@media (max-width: 640px) {
    .statistics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 641px) and (max-width: 768px) {
    .statistics-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

/* Modal Scroll Enhancement */
.modal-scroll {
    scroll-behavior: smooth;
    scrollbar-width: thin;
    scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.modal-scroll::-webkit-scrollbar {
    width: 6px;
}

.modal-scroll::-webkit-scrollbar-track {
    background: transparent;
    border-radius: 3px;
}

.modal-scroll::-webkit-scrollbar-thumb {
    background: rgba(156, 163, 175, 0.4);
    border-radius: 3px;
}

.modal-scroll::-webkit-scrollbar-thumb:hover {
    background: rgba(156, 163, 175, 0.6);
}

/* Prevent body scroll when modal is open */
.modal-open {
    overflow: hidden;
}

/* Responsive text scaling */
@media (max-width: 640px) {
    .modal-scroll {
        font-size: 14px;
    }
}

/* Enhanced hover effects */
.hover-lift:hover {
    transform: translateY(-1px);
}

/* Loading animation */
@keyframes pulse-slow {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse-slow {
    animation: pulse-slow 2s ease-in-out infinite;
}

/* Better focus styles for Alpine.js */
[x-cloak] {
    display: none !important;
}

/* Custom breakpoint for modal responsiveness */
@media (max-width: 768px) {
    .modal-content {
        margin: 1rem;
        max-height: calc(100vh - 2rem);
    }
}
</style>
@endsection