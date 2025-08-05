@extends('layouts.app')

@section('title', 'Konfigurasi Sistem')

@section('content')
<div class="space-y-4 sm:space-y-6" x-data="{ activeTab: 'general' }">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg p-4 sm:p-6 text-white">
        <h1 class="text-xl sm:text-2xl font-bold">Konfigurasi Sistem PMB</h1>
        <p class="text-purple-100 mt-2 text-sm sm:text-base">Kelola pengaturan sistem, gelombang pendaftaran, dan rekening bank</p>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-3 sm:p-4 flex items-center space-x-3">
            <svg class="h-5 w-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-green-800 font-medium text-sm sm:text-base">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 sm:p-4 flex items-center space-x-3">
            <svg class="h-5 w-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <span class="text-red-800 font-medium text-sm sm:text-base">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex flex-wrap sm:flex-nowrap space-x-2 sm:space-x-8 px-2 sm:px-0">
            <button @click="activeTab = 'general'" 
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'general', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'general' }"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm">
                <div class="flex items-center space-x-1 sm:space-x-2">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="hidden sm:inline">Pengaturan Umum</span>
                    <span class="sm:hidden">Umum</span>
                </div>
            </button>
            
            <button @click="activeTab = 'paths'" 
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'paths', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'paths' }"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm">
                <div class="flex items-center space-x-1 sm:space-x-2">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    <span class="hidden sm:inline">Jalur Pendaftaran</span>
                    <span class="sm:hidden">Jalur</span>
                </div>
            </button>
            
            <button @click="activeTab = 'waves'" 
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'waves', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'waves' }"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm">
                <div class="flex items-center space-x-1 sm:space-x-2">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="hidden sm:inline">Gelombang & Biaya</span>
                    <span class="sm:hidden">Gelombang</span>
                </div>
            </button>
            
            <button @click="activeTab = 'banks'" 
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'banks', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'banks' }"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm">
                <div class="flex items-center space-x-1 sm:space-x-2">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span class="hidden sm:inline">Rekening Bank</span>
                    <span class="sm:hidden">Bank</span>
                </div>
            </button>
            
            <button @click="activeTab = 'kip'" 
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'kip', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'kip' }"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm">
                <div class="flex items-center space-x-1 sm:space-x-2">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="hidden sm:inline">Kuota KIP</span>
                    <span class="sm:hidden">KIP</span>
                </div>
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="mt-4 sm:mt-6">
        <!-- General Settings Tab -->
        <div x-show="activeTab === 'general'" x-cloak class="space-y-4 sm:space-y-6">
            <form action="{{ route('admin.settings.configurations') }}" method="POST" class="space-y-4 sm:space-y-6">
                @csrf
                @method('PUT')
                
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 sm:mr-4">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Kontak & Informasi</h3>
                            <p class="text-gray-600 text-xs sm:text-sm">Pengaturan informasi kontak sistem</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Email Kontak</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input type="email" name="contact_email" 
                                       value="{{ old('contact_email', isset($configurations['contact_email']) ? $configurations['contact_email']->value : '') }}"
                                       class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       placeholder="pmb@stikpar.ac.id"
                                       required>
                            </div>
                            @error('contact_email')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Telepon Kontak</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="contact_phone" 
                                       value="{{ old('contact_phone', isset($configurations['contact_phone']) ? $configurations['contact_phone']->value : '') }}"
                                       class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       placeholder="0812-3456-7890"
                                       required>
                            </div>
                            @error('contact_phone')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 sm:mr-4">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Pengaturan Upload</h3>
                            <p class="text-gray-600 text-xs sm:text-sm">Konfigurasi ukuran dan tipe file yang diizinkan</p>
                        </div>
                    </div>
                    
                    <div class="space-y-6 sm:space-y-8">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Ukuran Maksimal Upload</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <select name="max_upload_size" 
                                        class="w-full sm:w-2/3 lg:w-1/2 pl-10 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                        required>
                                    @php
                                        $currentSize = old('max_upload_size', isset($configurations['max_upload_size']) ? $configurations['max_upload_size']->value : 2048);
                                        $sizes = [
                                            1024 => '1 MB',
                                            2048 => '2 MB', 
                                            3072 => '3 MB',
                                            4096 => '4 MB',
                                            5120 => '5 MB',
                                            10240 => '10 MB',
                                            15360 => '15 MB',
                                            20480 => '20 MB',
                                            25600 => '25 MB',
                                            51200 => '50 MB'
                                        ];
                                    @endphp
                                    @foreach($sizes as $value => $label)
                                        <option value="{{ $value }}" {{ $currentSize == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-600 flex items-center mt-2">
                                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                Ukuran maksimal file yang dapat diupload oleh user
                            </p>
                            @error('max_upload_size')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Tipe File yang Diizinkan</label>
                            @php
                                $currentTypes = old('allowed_file_types', isset($configurations['allowed_file_types']) ? $configurations['allowed_file_types']->value : 'pdf,jpg,jpeg,png');
                                $allowedTypes = explode(',', $currentTypes);
                                $allowedTypes = array_map('trim', $allowedTypes);
                                
                                $fileTypes = [
                                    'pdf' => 'PDF',
                                    'jpg' => 'JPG',
                                    'jpeg' => 'JPEG', 
                                    'png' => 'PNG',
                                    'gif' => 'GIF',
                                    'doc' => 'DOC',
                                    'docx' => 'DOCX',
                                    'xls' => 'XLS',
                                    'xlsx' => 'XLSX'
                                ];
                            @endphp
                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">
                                @foreach($fileTypes as $type => $label)
                                    <label class="relative flex items-center space-x-2 sm:space-x-3 p-3 sm:p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 cursor-pointer transition-all duration-200 group">
                                        <input type="checkbox" 
                                               name="allowed_file_types[]" 
                                               value="{{ $type }}"
                                               {{ in_array($type, $allowedTypes) ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded flex-shrink-0">
                                        <span class="text-xs sm:text-sm font-medium text-gray-700 group-hover:text-blue-700">{{ $label }}</span>
                                        <div class="absolute inset-0 rounded-lg border-2 border-blue-500 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                                    </label>
                                @endforeach
                            </div>
                            
                            <p class="text-xs sm:text-sm text-gray-600 flex items-center mt-3">
                                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                Pilih tipe file yang dapat diupload oleh user
                            </p>
                            @error('allowed_file_types')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="inline-flex items-center px-6 sm:px-8 py-2 sm:py-3 border border-transparent text-sm sm:text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="hidden sm:inline">Simpan Pengaturan Umum</span>
                        <span class="sm:hidden">Simpan</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Registration Paths Tab -->
        <div x-show="activeTab === 'paths'" x-cloak class="space-y-4 sm:space-y-6" x-data="{
            registrationPaths: @js($registrationPaths ? $registrationPaths->toArray() : []),
            showModal: false,
            newPath: { name: '', code: '', description: '', is_active: true }
        }">
            <form action="{{ route('admin.settings.registration-paths') }}" method="POST" class="space-y-4 sm:space-y-6">
                @csrf
                @method('PUT')
                
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 space-y-4 sm:space-y-0">
                        <div class="flex items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3 sm:mr-4">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold text-gray-900">Jalur Pendaftaran</h3>
                                <p class="text-gray-600 text-xs sm:text-sm">Kelola jalur pendaftaran mahasiswa</p>
                            </div>
                        </div>
                        <button type="button" @click="showModal = true" 
                                class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 border border-transparent text-sm sm:text-base font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="hidden sm:inline">Tambah Jalur</span>
                            <span class="sm:hidden">Tambah</span>
                        </button>
                    </div>
                    
                    <div class="space-y-4 sm:space-y-6">
                        <!-- Hidden inputs untuk data yang akan dikirim ke server -->
                        <template x-for="(path, index) in registrationPaths" :key="index">
                            <div style="display: none;">
                                <input type="hidden" :name="'registration_paths[' + index + '][name]'" :value="path.name">
                                <input type="hidden" :name="'registration_paths[' + index + '][code]'" :value="path.code">
                                <input type="hidden" :name="'registration_paths[' + index + '][description]'" :value="path.description">
                                <input type="hidden" :name="'registration_paths[' + index + '][is_active]'" :value="path.is_active ? 1 : 0">
                            </div>
                        </template>
                        
                        <template x-for="(path, index) in registrationPaths" :key="index">
                            <div class="border-2 border-gray-200 rounded-xl p-4 sm:p-6 bg-gradient-to-r from-indigo-50 to-blue-50 hover:from-indigo-100 hover:to-blue-100 transition-all duration-300">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 sm:mb-6 space-y-4 sm:space-y-0">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-sm sm:text-lg mr-3 sm:mr-4 shadow-md">
                                            <span x-text="path.code"></span>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-base sm:text-lg" x-text="path.name"></h4>
                                            <p class="text-xs sm:text-sm text-gray-600" x-text="path.description"></p>
                                        </div>
                                    </div>
                                    <button type="button" @click="
                                        if (confirm('Yakin ingin menghapus jalur pendaftaran ini?')) {
                                            if (path.id) {
                                                // If path has ID, delete from database
                                                fetch('{{ route('admin.settings.registration-paths.delete', '') }}/' + path.id, {
                                                    method: 'DELETE',
                                                    headers: {
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                        'X-Requested-With': 'XMLHttpRequest'
                                                    }
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        registrationPaths.splice(index, 1);
                                                        alert('Jalur pendaftaran berhasil dihapus!');
                                                    } else {
                                                        alert('Error: ' + data.message);
                                                    }
                                                })
                                                .catch(error => {
                                                    console.error('Error:', error);
                                                    alert('Terjadi kesalahan saat menghapus data.');
                                                });
                                            } else {
                                                // If no ID, just remove from array
                                                registrationPaths.splice(index, 1);
                                            }
                                        }
                                    " 
                                            class="p-2 text-red-600 hover:text-red-800 hover:bg-red-100 rounded-lg transition-all duration-200 flex-shrink-0">
                                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Nama Jalur</label>
                                        <input type="text" :name="'registration_paths[' + index + '][name]'" 
                                               x-model="path.name"
                                               class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                               placeholder="Contoh: Jalur Reguler"
                                               required>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Kode Jalur</label>
                                        <input type="text" :name="'registration_paths[' + index + '][code]'" 
                                               x-model="path.code"
                                               class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                               placeholder="REG"
                                               maxlength="10"
                                               required>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Deskripsi</label>
                                        <input type="text" :name="'registration_paths[' + index + '][description]'" 
                                               x-model="path.description"
                                               class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                               placeholder="Deskripsi jalur pendaftaran">
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-center">
                                    <label class="flex items-center space-x-3 p-3 sm:p-4 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-all duration-200">
                                        <input type="hidden" :name="'registration_paths[' + index + '][is_active]'" value="0">
                                        <input type="checkbox" :name="'registration_paths[' + index + '][is_active]'" 
                                               x-model="path.is_active"
                                               value="1"
                                               class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <span class="text-sm font-medium text-gray-700">Jalur Aktif</span>
                                    </label>
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="registrationPaths.length === 0" class="text-center py-8 sm:py-12 text-gray-500">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                </svg>
                            </div>
                            <p class="text-base sm:text-lg font-medium">Belum ada jalur pendaftaran yang ditambahkan.</p>
                            <p class="text-sm">Klik tombol "Tambah Jalur" untuk memulai</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-end pt-4 sm:pt-6 mt-6 sm:mt-8 border-t border-gray-200">
                        <button type="submit" 
                                class="inline-flex items-center px-6 sm:px-8 py-2 sm:py-3 border border-transparent text-sm sm:text-base font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="hidden sm:inline">Simpan Perubahan</span>
                            <span class="sm:hidden">Simpan</span>
                        </button>
                    </div>
                </div>
                
                <!-- Modal for Adding New Path -->
                <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.away="showModal = false">
                    <div class="bg-white rounded-xl max-w-md w-full p-4 sm:p-6 max-h-[90vh] overflow-y-auto" @click.stop>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Tambah Jalur Pendaftaran</h3>
                        
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Nama Jalur</label>
                                <input type="text" 
                                       x-model="newPath.name" 
                                       name="modal_path_name"
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" 
                                       placeholder="Contoh: Jalur Reguler">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Kode Jalur</label>
                                <input type="text" 
                                       x-model="newPath.code" 
                                       name="modal_path_code"
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" 
                                       placeholder="REG" 
                                       maxlength="10">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Deskripsi</label>
                                <textarea x-model="newPath.description" 
                                          name="modal_path_description"
                                          class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" 
                                          placeholder="Deskripsi jalur pendaftaran" 
                                          rows="3"></textarea>
                            </div>
                            
                            <div class="flex items-center">
                                <label class="flex items-center space-x-3 p-3 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" 
                                           x-model="newPath.is_active" 
                                           name="modal_path_active"
                                           class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <span class="text-sm font-medium text-gray-700">Jalur Aktif</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 mt-6 pt-4 border-t">
                            <button type="button" @click="showModal = false" class="px-4 sm:px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors order-2 sm:order-1">
                                Batal
                            </button>
                            <button type="button" 
                                    @click="
                                        if (newPath.name.trim() === '' || newPath.code.trim() === '') {
                                            alert('Nama jalur dan kode jalur wajib diisi!');
                                            return;
                                        }
                                        
                                        // Disable button to prevent double-click
                                        $el.disabled = true;
                                        $el.textContent = 'Menyimpan...';
                                        
                                        // Create form data
                                        const formData = new FormData();
                                        formData.append('_token', '{{ csrf_token() }}');
                                        formData.append('name', newPath.name);
                                        formData.append('code', newPath.code);
                                        formData.append('description', newPath.description || '');
                                        formData.append('is_active', newPath.is_active ? '1' : '0');
                                        
                                        // Send AJAX request
                                        fetch('{{ route('admin.settings.registration-paths.create') }}', {
                                            method: 'POST',
                                            body: formData,
                                            headers: {
                                                'X-Requested-With': 'XMLHttpRequest'
                                            }
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                // Add to registrationPaths array
                                                registrationPaths.push(data.data);
                                                // Reset form
                                                newPath = { name: '', code: '', description: '', is_active: true };
                                                // Close modal
                                                showModal = false;
                                                // Show success message
                                                alert('Jalur pendaftaran berhasil ditambahkan!');
                                            } else {
                                                alert('Error: ' + data.message);
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            alert('Terjadi kesalahan saat menyimpan data.');
                                        })
                                        .finally(() => {
                                            // Re-enable button
                                            $el.disabled = false;
                                            $el.textContent = 'Tambah Jalur';
                                        });
                                    " 
                                    class="px-4 sm:px-6 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 text-sm sm:text-base order-1 sm:order-2">
                                <span class="hidden sm:inline">Tambah Jalur</span>
                                <span class="sm:hidden">Tambah</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Waves & Fees Tab -->
        <div x-show="activeTab === 'waves'" x-cloak class="space-y-4 sm:space-y-6" x-data="{
            waves: @js($waves ? $waves->toArray() : []),
            showModal: false,
            newWave: { 
                name: '', 
                wave_number: 1, 
                start_date: '', 
                end_date: '', 
                administration_fee: 0, 
                registration_fee: 0, 
                is_active: true, 
                available_paths: @js($registrationPaths && $registrationPaths->count() > 0 ? $registrationPaths->mapWithKeys(function($path) { return [strtolower($path->code) => true]; }) : [])
            },
            init() {
                console.log('Waves data:', this.waves);
                this.waves.forEach(wave => { 
                    if (!wave.available_paths) { 
                        wave.available_paths = @js($registrationPaths && $registrationPaths->count() > 0 ? $registrationPaths->mapWithKeys(function($path) { return [strtolower($path->code) => true]; }) : []); 
                    } 
                });
            }
        }">
            <form action="{{ route('admin.settings.waves') }}" method="POST" class="space-y-4 sm:space-y-6">
                @csrf
                @method('PUT')
                
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 space-y-4 sm:space-y-0">
                        <div class="flex items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3 sm:mr-4">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold text-gray-900">Gelombang Pendaftaran</h3>
                                <p class="text-gray-600 text-xs sm:text-sm">Kelola jadwal dan biaya pendaftaran</p>
                            </div>
                        </div>
                        <button type="button" @click="showModal = true; newWave.wave_number = waves.length + 1; newWave.name = 'Gelombang ' + (waves.length + 1)" 
                                class="inline-flex items-center px-3 sm:px-4 py-2 border border-transparent text-xs sm:text-sm font-medium rounded-lg text-white bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 shadow-md hover:shadow-lg">
                            <svg class="h-3 w-3 sm:h-4 sm:w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="hidden sm:inline">Tambah Gelombang</span>
                            <span class="sm:hidden">Tambah</span>
                        </button>
                    </div>
                    
                    <div class="space-y-4 sm:space-y-6">
                        <template x-for="(wave, index) in waves" :key="index">
                            <div class="border-2 border-gray-200 rounded-xl p-4 sm:p-6 bg-gradient-to-r from-gray-50 to-gray-100 hover:from-blue-50 hover:to-purple-50 transition-all duration-300">
                                <!-- Wave content similar to previous implementation -->
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 sm:mb-6 space-y-4 sm:space-y-0">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center text-white font-bold text-sm sm:text-lg mr-3 sm:mr-4 shadow-md"
                                             :class="index === 0 ? 'bg-gradient-to-r from-blue-500 to-blue-600' : index === 1 ? 'bg-gradient-to-r from-green-500 to-green-600' : index === 2 ? 'bg-gradient-to-r from-purple-500 to-purple-600' : 'bg-gradient-to-r from-gray-500 to-gray-600'">
                                            <span x-text="wave.wave_number"></span>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-base sm:text-lg" x-text="wave.name"></h4>
                                            <p class="text-xs sm:text-sm text-gray-600">Gelombang ke-<span x-text="wave.wave_number"></span></p>
                                        </div>
                                    </div>
                                    <button type="button" @click="
                                        if (confirm('Yakin ingin menghapus gelombang pendaftaran ini?')) {
                                            if (wave.id) {
                                                // If wave has ID, delete from database
                                                fetch('{{ route('admin.settings.waves.delete', '') }}/' + wave.id, {
                                                    method: 'DELETE',
                                                    headers: {
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                        'X-Requested-With': 'XMLHttpRequest'
                                                    }
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        waves.splice(index, 1);
                                                        alert('Gelombang pendaftaran berhasil dihapus!');
                                                    } else {
                                                        alert('Error: ' + data.message);
                                                    }
                                                })
                                                .catch(error => {
                                                    console.error('Error:', error);
                                                    alert('Terjadi kesalahan saat menghapus data.');
                                                });
                                            } else {
                                                // If no ID, just remove from array
                                                waves.splice(index, 1);
                                            }
                                        }
                                    " 
                                            class="p-2 text-red-600 hover:text-red-800 hover:bg-red-100 rounded-lg transition-all duration-200 flex-shrink-0">
                                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Nama Gelombang</label>
                                        <input type="text" :name="'waves[' + index + '][name]'" 
                                               x-model="wave.name"
                                               class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                               placeholder="Contoh: Gelombang 1">
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Nomor Gelombang</label>
                                        <input type="number" :name="'waves[' + index + '][wave_number]'" 
                                               x-model="wave.wave_number"
                                               class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                               min="1">
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Tanggal Mulai</label>
                                        <input type="date" :name="'waves[' + index + '][start_date]'" 
                                               x-model="wave.start_date"
                                               class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Tanggal Berakhir</label>
                                        <input type="date" :name="'waves[' + index + '][end_date]'" 
                                               x-model="wave.end_date"
                                               class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Biaya Administrasi</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                                <span class="text-gray-500 text-sm sm:text-base font-medium">Rp</span>
                                            </div>
                                            <input type="number" :name="'waves[' + index + '][administration_fee]'" 
                                                   x-model="wave.administration_fee"
                                                   class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                   min="0"
                                                   placeholder="0">
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Biaya Daftar Ulang</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                                <span class="text-gray-500 text-sm sm:text-base font-medium">Rp</span>
                                            </div>
                                            <input type="number" :name="'waves[' + index + '][registration_fee]'" 
                                                   x-model="wave.registration_fee"
                                                   class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                   min="0"
                                                   placeholder="0">
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-end">
                                        <label class="flex items-center space-x-3 p-3 sm:p-4 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-all duration-200">
                                            <input type="hidden" :name="'waves[' + index + '][is_active]'" value="0">
                                            <input type="checkbox" :name="'waves[' + index + '][is_active]'" 
                                                   x-model="wave.is_active"
                                                   value="1"
                                                   class="w-4 h-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                            <span class="text-sm font-medium text-gray-700">Aktif</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Available Paths Section - Only show if there are registration paths -->
                                @if($registrationPaths && $registrationPaths->count() > 0)
                                <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-gray-200">
                                    <div class="flex items-center mb-3 sm:mb-4">
                                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center text-white mr-2 sm:mr-3">
                                            <svg class="h-3 w-3 sm:h-4 sm:w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <h5 class="font-semibold text-gray-800 text-sm sm:text-base">Jalur Pendaftaran yang Tersedia</h5>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
                                        @foreach($registrationPaths as $path)
                                        <label class="relative flex items-center space-x-2 sm:space-x-3 p-3 sm:p-4 bg-white border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 cursor-pointer transition-all duration-200 group">
                                            <input type="hidden" :name="'waves[' + index + '][available_paths][{{ strtolower($path->code) }}]'" value="0">
                                            <input type="checkbox" 
                                                   :name="'waves[' + index + '][available_paths][{{ strtolower($path->code) }}]'" 
                                                   :checked="wave.available_paths?.{{ strtolower($path->code) }} ?? true"
                                                   @change="if (!wave.available_paths) wave.available_paths = {}; wave.available_paths.{{ strtolower($path->code) }} = $event.target.checked"
                                                   value="1"
                                                   class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded flex-shrink-0">
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white mr-2 sm:mr-3">
                                                    <svg class="h-3 w-3 sm:h-4 sm:w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="text-xs sm:text-sm font-medium text-gray-700 group-hover:text-blue-700">{{ $path->name }}</span>
                                                    <p class="text-xs text-gray-500 hidden sm:block">{{ $path->description }}</p>
                                                </div>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                    
                                    <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                        <p class="text-xs sm:text-sm text-blue-700 flex items-center">
                                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                            </svg>
                                            Pilih jalur pendaftaran yang tersedia untuk gelombang ini
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </template>
                        
                        <div x-show="waves.length === 0" class="text-center py-8 sm:py-12 text-gray-500">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <p class="text-base sm:text-lg font-medium">Belum ada gelombang yang ditambahkan.</p>
                            <p class="text-sm">Klik tombol "Tambah Gelombang" untuk memulai</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="inline-flex items-center px-6 sm:px-8 py-2 sm:py-3 border border-transparent text-sm sm:text-base font-medium rounded-lg text-white bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="hidden sm:inline">Simpan Gelombang Pendaftaran</span>
                        <span class="sm:hidden">Simpan</span>
                    </button>
                </div>

                <!-- Modal Tambah Gelombang -->
                <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.away="showModal = false">
                    <div class="bg-white rounded-xl shadow-2xl p-4 sm:p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.stop>
                        <div class="flex justify-between items-center mb-4 sm:mb-6">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Tambah Gelombang Baru</h3>
                            <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Nama Gelombang</label>
                                    <input type="text" 
                                           x-model="newWave.name" 
                                           name="modal_wave_name"
                                           class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200" 
                                           placeholder="Contoh: Gelombang 1">
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Nomor Gelombang</label>
                                    <input type="number" 
                                           x-model="newWave.wave_number" 
                                           name="modal_wave_number"
                                           class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200" 
                                           min="1">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Tanggal Mulai</label>
                                    <input type="date" 
                                           x-model="newWave.start_date" 
                                           name="modal_start_date"
                                           class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Tanggal Berakhir</label>
                                    <input type="date" 
                                           x-model="newWave.end_date" 
                                           name="modal_end_date"
                                           class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Biaya Administrasi</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-sm sm:text-base font-medium">Rp</span>
                                        </div>
                                        <input type="number" 
                                               x-model="newWave.administration_fee" 
                                               name="modal_admin_fee"
                                               class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200" 
                                               min="0" 
                                               placeholder="0">
                                    </div>
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Biaya Daftar Ulang</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-sm sm:text-base font-medium">Rp</span>
                                        </div>
                                        <input type="number" 
                                               x-model="newWave.registration_fee" 
                                               name="modal_reg_fee"
                                               class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200" 
                                               min="0" 
                                               placeholder="0">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Available Paths - Only show if there are registration paths -->
                            @if($registrationPaths && $registrationPaths->count() > 0)
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700">Jalur Pendaftaran yang Tersedia</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($registrationPaths as $path)
                                    <label class="flex items-center space-x-3 p-3 bg-blue-50 border border-blue-200 rounded-lg cursor-pointer hover:bg-blue-100 transition-colors">
                                        <input type="checkbox" 
                                               x-model="newWave.available_paths.{{ strtolower($path->code) }}" 
                                               name="modal_path_{{ strtolower($path->code) }}"
                                               class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="text-sm font-medium text-gray-700">{{ $path->name }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <div class="flex items-center">
                                <label class="flex items-center space-x-3 p-3 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" 
                                           x-model="newWave.is_active" 
                                           name="modal_wave_active"
                                           class="w-4 h-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <span class="text-sm font-medium text-gray-700">Gelombang Aktif</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 mt-6 pt-4 border-t">
                            <button type="button" @click="showModal = false" class="px-4 sm:px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors order-2 sm:order-1">
                                Batal
                            </button>
                            <button type="button" 
                                @click="
                                    if (newWave.name.trim() === '' || newWave.wave_number === '' || newWave.start_date === '' || newWave.end_date === '') {
                                        alert('Nama gelombang, nomor gelombang, tanggal mulai, dan tanggal berakhir wajib diisi!');
                                        return;
                                    }
                                    
                                    // Disable button to prevent double-click
                                    $el.disabled = true;
                                    $el.textContent = 'Menyimpan...';
                                    
                                    // Create form data
                                    const formData = new FormData();
                                    formData.append('_token', '{{ csrf_token() }}');
                                    formData.append('name', newWave.name);
                                    formData.append('wave_number', newWave.wave_number);
                                    formData.append('start_date', newWave.start_date);
                                    formData.append('end_date', newWave.end_date);
                                    formData.append('administration_fee', newWave.administration_fee);
                                    formData.append('registration_fee', newWave.registration_fee);
                                    formData.append('is_active', newWave.is_active ? '1' : '0');
                                    
                                    // Available paths
                                    if (newWave.available_paths) {
                                        Object.keys(newWave.available_paths).forEach(key => {
                                            formData.append('available_paths[' + key + ']', newWave.available_paths[key] ? '1' : '0');
                                        });
                                    }
                                    
                                    // Send AJAX request
                                    fetch('{{ route('admin.settings.waves.create') }}', {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            // Add to waves array
                                            waves.push(data.data);
                                            // Reset form
                                            newWave = { name: '', wave_number: waves.length + 1, start_date: '', end_date: '', administration_fee: 0, registration_fee: 0, is_active: true, available_paths: @js($registrationPaths && $registrationPaths->count() > 0 ? $registrationPaths->mapWithKeys(function($path) { return [strtolower($path->code) => true]; }) : []) };
                                            // Close modal
                                            showModal = false;
                                            // Show success message
                                            alert('Gelombang pendaftaran berhasil ditambahkan!');
                                        } else {
                                            alert('Error: ' + data.message);
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Terjadi kesalahan saat menyimpan data.');
                                    })
                                    .finally(() => {
                                        // Re-enable button
                                        $el.disabled = false;
                                        $el.textContent = 'Tambah Gelombang';
                                    });
                                " 
                                class="px-4 sm:px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-200 text-sm sm:text-base order-1 sm:order-2">
                                <span class="hidden sm:inline">Tambah Gelombang</span>
                                <span class="sm:hidden">Tambah</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Bank Accounts Tab -->
        <div x-show="activeTab === 'banks'" x-cloak class="space-y-6" x-data="{
            banks: @js($bankAccounts ? $bankAccounts->toArray() : []),
            showModal: false,
            newBank: { bank_name: '', account_number: '', account_holder: '', is_active: true }
        }">
            <form action="{{ route('admin.settings.banks') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                      <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 sm:mr-4">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Rekening Bank</h3>
                            <p class="text-gray-600 text-sm">Kelola informasi rekening bank untuk pembayaran</p>
                        </div>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <button type="button" @click="showModal = true" 
                                class="w-full sm:w-auto inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 border border-transparent text-sm sm:text-base font-medium rounded-lg text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-md hover:shadow-lg">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="hidden sm:inline">Tambah Rekening</span>
                            <span class="sm:hidden">Tambah</span>
                        </button>
                    </div>
                </div>
                    
                    <div class="space-y-6">
                        <!-- Hidden inputs untuk data yang akan dikirim ke server -->
                        <template x-for="(bank, index) in banks" :key="index">
                            <div style="display: none;">
                                <input type="hidden" :name="'bank_accounts[' + index + '][bank_name]'" :value="bank.bank_name">
                                <input type="hidden" :name="'bank_accounts[' + index + '][account_number]'" :value="bank.account_number">
                                <input type="hidden" :name="'bank_accounts[' + index + '][account_holder]'" :value="bank.account_holder">
                                <input type="hidden" :name="'bank_accounts[' + index + '][is_active]'" :value="bank.is_active ? 1 : 0">
                            </div>
                        </template>
                        
                        <template x-for="(bank, index) in banks" :key="index">
                            <div class="border-2 border-gray-200 rounded-xl p-4 sm:p-6 bg-gradient-to-r from-green-50 to-blue-50 hover:from-green-100 hover:to-blue-100 transition-all duration-300">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 sm:mb-6">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 sm:w-12 sm:h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center text-white font-bold text-sm sm:text-lg mr-3 sm:mr-4 shadow-md">
                                            <span x-text="index + 1"></span>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-base sm:text-lg" x-text="'Rekening Bank #' + (index + 1)"></h4>
                                            <p class="text-sm text-gray-600">Informasi rekening pembayaran</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 sm:mt-0">
                                        <button type="button" @click="
                                            if (confirm('Yakin ingin menghapus rekening bank ini?')) {
                                                if (bank.id) {
                                                    // If bank has ID, delete from database
                                                    fetch('{{ route('admin.settings.banks.delete', '') }}/' + bank.id, {
                                                        method: 'DELETE',
                                                        headers: {
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                            'X-Requested-With': 'XMLHttpRequest'
                                                        }
                                                    })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        if (data.success) {
                                                            banks.splice(index, 1);
                                                            alert('Rekening bank berhasil dihapus!');
                                                        } else {
                                                            alert('Error: ' + data.message);
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error('Error:', error);
                                                        alert('Terjadi kesalahan saat menghapus data.');
                                                    });
                                                } else {
                                                    // If no ID, just remove from array
                                                    banks.splice(index, 1);
                                                }
                                            }
                                        " 
                                                class="p-1 sm:p-2 text-red-600 hover:text-red-800 hover:bg-red-100 rounded-lg transition-all duration-200">
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Nama Bank</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0h3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                            <input type="text" :name="'bank_accounts[' + index + '][bank_name]'" 
                                                   x-model="bank.bank_name"
                                                   class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                                   placeholder="Contoh: Bank BCA"
                                                   required>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Nomor Rekening</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                                </svg>
                                            </div>
                                            <input type="text" :name="'bank_accounts[' + index + '][account_number]'" 
                                                   x-model="bank.account_number"
                                                   class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                                   placeholder="1234567890"
                                                   required>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Nama Pemilik</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <input type="text" :name="'bank_accounts[' + index + '][account_holder]'" 
                                                   x-model="bank.account_holder"
                                                   class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                                   placeholder="Nama pemilik rekening"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-center">
                                    <label class="flex items-center space-x-3 p-3 sm:p-4 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-all duration-200">
                                        <input type="hidden" :name="'bank_accounts[' + index + '][is_active]'" value="0">
                                        <input type="checkbox" :name="'bank_accounts[' + index + '][is_active]'" 
                                               x-model="bank.is_active"
                                               value="1"
                                               class="w-4 h-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                        <span class="text-sm font-medium text-gray-700">Rekening Aktif</span>
                                    </label>
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="banks.length === 0" class="text-center py-8 sm:py-12 text-gray-500">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                            <p class="text-base sm:text-lg font-medium">Belum ada rekening bank yang ditambahkan.</p>
                            <p class="text-sm">Klik tombol "Tambah Rekening" untuk memulai</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 sm:px-8 sm:py-3 border border-transparent text-sm sm:text-base font-medium rounded-lg text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="hidden sm:inline">Simpan Rekening Bank</span>
                        <span class="sm:hidden">Simpan</span>
                    </button>
                </div>

                <!-- Modal Tambah Rekening Bank -->
                <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.away="showModal = false">
                    <div class="bg-white rounded-xl shadow-2xl p-4 sm:p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto" @click.stop>
                        <div class="flex justify-between items-center mb-4 sm:mb-6">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Tambah Rekening Bank</h3>
                            <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Nama Bank</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0h3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           x-model="newBank.bank_name" 
                                           name="modal_bank_name"
                                           class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" 
                                           placeholder="Contoh: Bank BCA">
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Nomor Rekening</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           x-model="newBank.account_number" 
                                           name="modal_account_number"
                                           class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" 
                                           placeholder="1234567890">
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Nama Pemilik</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           x-model="newBank.account_holder" 
                                           name="modal_account_holder"
                                           class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" 
                                           placeholder="Nama pemilik rekening">
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <label class="flex items-center space-x-3 p-3 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" 
                                           x-model="newBank.is_active" 
                                           name="modal_bank_active"
                                           class="w-4 h-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <span class="text-sm font-medium text-gray-700">Rekening Aktif</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 mt-6 pt-4 border-t">
                            <button type="button" @click="showModal = false" class="px-4 sm:px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors order-2 sm:order-1">
                                Batal
                            </button>
                            <button type="button" 
                                @click="
                                    if (newBank.bank_name.trim() === '' || newBank.account_number.trim() === '' || newBank.account_holder.trim() === '') {
                                        alert('Semua field wajib diisi!');
                                        return;
                                    }
                                    
                                    // Disable button to prevent double-click
                                    $el.disabled = true;
                                    $el.textContent = 'Menyimpan...';
                                    
                                    // Create form data
                                    const formData = new FormData();
                                    formData.append('_token', '{{ csrf_token() }}');
                                    formData.append('bank_name', newBank.bank_name);
                                    formData.append('account_number', newBank.account_number);
                                    formData.append('account_holder', newBank.account_holder);
                                    formData.append('is_active', newBank.is_active ? '1' : '0');
                                    
                                    // Send AJAX request
                                    fetch('{{ route('admin.settings.banks.create') }}', {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            // Add to banks array
                                            banks.push(data.data);
                                            // Reset form
                                            newBank = { bank_name: '', account_number: '', account_holder: '', is_active: true };
                                            // Close modal
                                            showModal = false;
                                            // Show success message
                                            alert('Rekening bank berhasil ditambahkan!');
                                        } else {
                                            alert('Error: ' + data.message);
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Terjadi kesalahan saat menyimpan data.');
                                    })
                                    .finally(() => {
                                        // Re-enable button
                                        $el.disabled = false;
                                        $el.textContent = 'Tambah Rekening';
                                    });
                                " 
                                class="px-4 sm:px-6 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 text-sm sm:text-base order-1 sm:order-2">
                                <span class="hidden sm:inline">Tambah Rekening</span>
                                <span class="sm:hidden">Tambah</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- KIP Quotas Tab -->
        <div x-show="activeTab === 'kip'" x-cloak class="space-y-6" x-data="{
            quotas: @js($kipQuotas ? $kipQuotas->toArray() : []),
            showModal: false,
            newQuota: { year: new Date().getFullYear(), total_quota: 0, remaining_quota: 0, is_active: true }
        }">
            <form action="{{ route('admin.settings.kip') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center text-white mr-3 sm:mr-4 shadow-md">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 lg:h-6 lg:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold text-gray-900">Kuota KIP</h3>
                                <p class="text-sm text-gray-600">Kelola kuota KIP per tahun</p>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <button type="button" @click="showModal = true; newQuota.year = new Date().getFullYear()" 
                                    class="w-full sm:w-auto inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 border border-transparent text-sm sm:text-base font-medium rounded-lg text-white bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 shadow-md hover:shadow-lg">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span class="hidden sm:inline">Tambah Kuota</span>
                                <span class="sm:hidden">Tambah</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        <template x-for="(quota, index) in quotas" :key="index">
                            <div class="border-2 border-gray-200 rounded-xl p-4 sm:p-6 bg-gradient-to-r from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 transition-all duration-300">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 sm:mb-6">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-sm sm:text-base lg:text-lg mr-3 sm:mr-4 shadow-md">
                                            <span x-text="quota.year"></span>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-base sm:text-lg" x-text="'Kuota KIP Tahun ' + quota.year"></h4>
                                            <p class="text-sm text-gray-600">Pengaturan kuota KIP tahun akademik</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 sm:mt-0">
                                        <button type="button" @click="
                                            if (confirm('Yakin ingin menghapus kuota KIP ini?')) {
                                                if (quota.id) {
                                                    // If quota has ID, delete from database
                                                    fetch('{{ route('admin.settings.kip.delete', '') }}/' + quota.id, {
                                                        method: 'DELETE',
                                                        headers: {
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                            'X-Requested-With': 'XMLHttpRequest'
                                                        }
                                                    })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        if (data.success) {
                                                            quotas.splice(index, 1);
                                                            alert('Kuota KIP berhasil dihapus!');
                                                        } else {
                                                            alert('Error: ' + data.message);
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error('Error:', error);
                                                        alert('Terjadi kesalahan saat menghapus data.');
                                                    });
                                                } else {
                                                    // If no ID, just remove from array
                                                    quotas.splice(index, 1);
                                                }
                                            }
                                        " 
                                                class="p-1 sm:p-2 text-red-600 hover:text-red-800 hover:bg-red-100 rounded-lg transition-all duration-200">
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Tahun</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <input type="number" :name="'kip_quotas[' + index + '][year]'" 
                                                   x-model="quota.year"
                                                   class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                   min="2020" max="2030"
                                                   placeholder="2024"
                                                   required>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Total Kuota</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                </svg>
                                            </div>
                                            <input type="number" :name="'kip_quotas[' + index + '][total_quota]'" 
                                                   x-model="quota.total_quota"
                                                   class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                   min="0"
                                                   placeholder="100"
                                                   required>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Sisa Kuota</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                            <input type="number" :name="'kip_quotas[' + index + '][remaining_quota]'" 
                                                   x-model="quota.remaining_quota"
                                                   class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                   min="0"
                                                   placeholder="90"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-center">
                                    <label class="flex items-center space-x-3 p-3 sm:p-4 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-all duration-200">
                                        <input type="hidden" :name="'kip_quotas[' + index + '][is_active]'" value="0">
                                        <input type="checkbox" :name="'kip_quotas[' + index + '][is_active]'" 
                                               x-model="quota.is_active"
                                               value="1"
                                               class="w-4 h-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                        <span class="text-sm font-medium text-gray-700">Kuota Aktif</span>
                                    </label>
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="quotas.length === 0" class="text-center py-8 sm:py-12 text-gray-500">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                            <p class="text-base sm:text-lg font-medium">Belum ada kuota KIP yang ditambahkan.</p>
                            <p class="text-sm">Klik tombol "Tambah Kuota" untuk memulai</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 sm:px-8 sm:py-3 border border-transparent text-sm sm:text-base font-medium rounded-lg text-white bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 shadow-md hover:shadow-lg">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="hidden sm:inline">Simpan Kuota KIP</span>
                        <span class="sm:hidden">Simpan</span>
                    </button>
                </div>

                <!-- Modal Tambah Kuota KIP -->
                <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.away="showModal = false">
                    <div class="bg-white rounded-xl shadow-2xl p-4 sm:p-6 w-full max-w-md max-h-[90vh] overflow-y-auto" @click.stop>
                        <div class="flex justify-between items-center mb-4 sm:mb-6">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Tambah Kuota KIP</h3>
                            <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Tahun</label>
                                <input type="number" 
                                       x-model="newQuota.year" 
                                       name="modal_quota_year"
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200" 
                                       min="2020" 
                                       max="2030">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Total Kuota</label>
                                <input type="number" 
                                       x-model="newQuota.total_quota" 
                                       name="modal_total_quota"
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200" 
                                       min="0" 
                                       placeholder="0">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Sisa Kuota</label>
                                <input type="number" 
                                       x-model="newQuota.remaining_quota" 
                                       name="modal_remaining_quota"
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200" 
                                       min="0" 
                                       placeholder="0">
                            </div>
                            
                            <div class="flex items-center">
                                <label class="flex items-center space-x-3 p-3 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" 
                                           x-model="newQuota.is_active" 
                                           name="modal_quota_active"
                                           class="w-4 h-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <span class="text-sm font-medium text-gray-700">Kuota Aktif</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 mt-6 pt-4 border-t">
                            <button type="button" @click="showModal = false" class="px-4 sm:px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors order-2 sm:order-1">
                                Batal
                            </button>
                            <button type="button" 
                                @click="
                                    if (newQuota.year === '' || newQuota.total_quota === '' || newQuota.remaining_quota === '') {
                                        alert('Semua field wajib diisi!');
                                        return;
                                    }
                                    
                                    // Disable button to prevent double-click
                                    $el.disabled = true;
                                    $el.textContent = 'Menyimpan...';
                                    
                                    // Create form data
                                    const formData = new FormData();
                                    formData.append('_token', '{{ csrf_token() }}');
                                    formData.append('year', newQuota.year);
                                    formData.append('total_quota', newQuota.total_quota);
                                    formData.append('remaining_quota', newQuota.remaining_quota);
                                    formData.append('is_active', newQuota.is_active ? '1' : '0');
                                    
                                    // Send AJAX request
                                    fetch('{{ route('admin.settings.kip.create') }}', {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            // Add to quotas array
                                            quotas.push(data.data);
                                            // Reset form
                                            newQuota = { year: new Date().getFullYear(), total_quota: 0, remaining_quota: 0, is_active: true };
                                            // Close modal
                                            showModal = false;
                                            // Show success message
                                            alert('Kuota KIP berhasil ditambahkan!');
                                        } else {
                                            alert('Error: ' + data.message);
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Terjadi kesalahan saat menyimpan data.');
                                    })
                                    .finally(() => {
                                        // Re-enable button
                                        $el.disabled = false;
                                        $el.textContent = 'Tambah Kuota';
                                    });
                                " 
                                class="px-4 sm:px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-200 text-sm sm:text-base order-1 sm:order-2">
                                <span class="hidden sm:inline">Tambah Kuota</span>
                                <span class="sm:hidden">Tambah</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush
@endsection