@extends('layouts.app')

@section('title', 'Formulir Pendaftaran')

@section('content')
<div x-data="registrationWizard()" class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-5xl mx-auto px-4">
        
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Formulir Pendaftaran Mahasiswa Baru</h1>
                    <div class="mt-2 flex items-center space-x-6 text-sm text-gray-600">
                        <span>Nomor Pendaftaran: <span class="font-semibold text-blue-600">{{ $registration->registration_number }}</span></span>
                        <span>Jalur Pendaftaran: <span class="font-semibold text-gray-900">{{ $registration->path->name }}</span></span>
                        <span>Gelombang: <span class="font-semibold text-gray-900">{{ $registration->wave->name }}</span></span>
                    </div>
                </div>
                <div class="text-right">
                    <span x-text="getStatusText()" 
                          :class="getStatusClass()"
                          class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border">
                    </span>
                </div>
            </div>
        </div>

        <!-- Status Check for waiting_decision -->
        @if($registration->status === 'waiting_decision')
        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-indigo-900">Pendaftaran Sudah Disubmit</h3>
                    <p class="text-indigo-700">Formulir dan dokumen Anda sudah disubmit pada {{ $registration->document_submitted_at?->format('d/m/Y H:i') }}. Tim seleksi sedang melakukan review berkas Anda.</p>
                    <p class="text-indigo-600 text-sm mt-2">Status: <strong>Menunggu Keputusan Seleksi</strong></p>
                </div>
            </div>
        </div>
        @endif

        <!-- Progress Steps -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <!-- Step 1: Data Pribadi -->
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full transition-all duration-300"
                         :class="currentStep >= 1 || registrationStatus === 'waiting_decision' ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600'">
                        <svg x-show="currentStep > 1 || registrationStatus === 'waiting_decision'" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span x-show="currentStep <= 1 && registrationStatus !== 'waiting_decision'" class="text-sm font-bold">1</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium" :class="currentStep >= 1 || registrationStatus === 'waiting_decision' ? 'text-gray-900' : 'text-gray-500'">Data Pribadi</p>
                        <p class="text-xs text-gray-500">Informasi dasar calon mahasiswa</p>
                    </div>
                </div>

                <!-- Connector 1 -->
                <div class="flex-1 mx-4 h-1 bg-gray-200 rounded overflow-hidden">
                    <div class="h-1 bg-blue-600 rounded transition-all duration-500" 
                         :style="`width: ${(currentStep > 1 || registrationStatus === 'waiting_decision') ? '100' : '0'}%`"></div>
                </div>

                <!-- Step 2: Upload Dokumen -->
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full transition-all duration-300"
                         :class="(currentStep >= 2 || registrationStatus === 'waiting_decision') ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600'">
                        <svg x-show="currentStep > 2 || registrationStatus === 'waiting_decision'" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span x-show="currentStep <= 2 && registrationStatus !== 'waiting_decision'" class="text-sm font-bold">2</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium" :class="(currentStep >= 2 || registrationStatus === 'waiting_decision') ? 'text-gray-900' : 'text-gray-500'">Upload Dokumen</p>
                        <p class="text-xs text-gray-500">Dokumen persyaratan</p>
                    </div>
                </div>

                <!-- Connector 2 -->
                <div class="flex-1 mx-4 h-1 bg-gray-200 rounded overflow-hidden">
                    <div class="h-1 bg-blue-600 rounded transition-all duration-500" 
                         :style="`width: ${(currentStep > 2 || registrationStatus === 'waiting_decision') ? '100' : '0'}%`"></div>
                </div>

                <!-- Step 3: Finalisasi -->
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full transition-all duration-300"
                         :class="(currentStep >= 3 || registrationStatus === 'waiting_decision') ? 'bg-green-600 text-white' : 'bg-gray-300 text-gray-600'">
                        <svg x-show="registrationStatus === 'waiting_decision'" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span x-show="currentStep <= 3 && registrationStatus !== 'waiting_decision'" class="text-sm font-bold">3</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium" :class="(currentStep >= 3 || registrationStatus === 'waiting_decision') ? 'text-gray-900' : 'text-gray-500'">
                            <span x-show="registrationStatus === 'waiting_decision'">Direview</span>
                            <span x-show="registrationStatus !== 'waiting_decision'">Finalisasi</span>
                        </p>
                        <p class="text-xs text-gray-500">
                            <span x-show="registrationStatus === 'waiting_decision'">Sedang direview</span>
                            <span x-show="registrationStatus !== 'waiting_decision'">Review & Submit</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Container -->
        <div x-show="alert.show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="mb-6">
            <div :class="alert.type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'"
                 class="border rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path x-show="alert.type === 'success'" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        <path x-show="alert.type === 'error'" fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="flex-1" x-text="alert.message"></div>
                    <button @click="alert.show = false" class="ml-4 text-sm font-medium underline hover:no-underline">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content - Only show form if not in waiting_decision -->
        <div x-show="registrationStatus !== 'waiting_decision'" class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            
            <!-- Step 1: Data Pribadi -->
            <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Data Pribadi Calon Mahasiswa
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Lengkapi seluruh informasi dengan data yang sesuai dengan dokumen resmi</p>
                </div>
                
                <form @submit.prevent="savePersonalData()" class="p-6">
                    
                    <!-- Section: Identitas Diri -->
                    <div class="mb-8">
                        <div class="border-l-4 border-blue-600 pl-4 mb-6">
                            <h3 class="text-md font-semibold text-gray-800">Identitas Diri</h3>
                            <p class="text-sm text-gray-600">Data identitas calon mahasiswa</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                                <input type="text" x-model="formData.nisn"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Masukkan NISN (opsional)">
                                <p class="text-xs text-gray-500 mt-1">Nomor Induk Siswa Nasional (opsional)</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" x-model="formData.full_name" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Nama lengkap sesuai ijazah">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select x-model="formData.gender" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Agama <span class="text-red-500">*</span>
                                </label>
                                <select x-model="formData.religion" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Pilih Agama</option>
                                    <option value="Kristen Protestan">Kristen Protestan</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tempat Lahir <span class="text-red-500">*</span>
                                </label>
                                <input type="text" x-model="formData.birth_place" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Nama kota tempat lahir">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Lahir <span class="text-red-500">*</span>
                                </label>
                                <input type="date" x-model="formData.birth_date" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Telepon <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" x-model="formData.phone_number" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Contoh: 08123456789">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" x-model="formData.email" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="nama@email.com">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat Lengkap <span class="text-red-500">*</span>
                                </label>
                                <textarea x-model="formData.address" rows="3" required
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                          placeholder="Alamat lengkap sesuai KTP/KK (Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos)"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                   Asal Paroki <span class="text-red-500">*</span>
                                </label>
                                <input type="text" x-model="formData.parish_name" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Paroki Katedral, Paroki Santo Yosef, dll.">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Data Akademik -->
                    <div class="mb-8">
                        <div class="border-l-4 border-green-600 pl-4 mb-6">
                            <h3 class="text-md font-semibold text-gray-800">Data Akademik</h3>
                            <p class="text-sm text-gray-600">Informasi pendidikan dan pilihan program studi</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Asal Sekolah <span class="text-red-500">*</span>
                                </label>
                                <input type="text" x-model="formData.school_origin" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Nama lengkap SMA/SMK/MA asal">
                            </div>

                            @if(strtoupper($registration->path->code) === 'PRE' || strtoupper($registration->path->code) === 'PRESTASI')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Rata-rata Rapor Kelas 11</label>
                                <input type="number" x-model="formData.grade_8_sem2"
                                       min="0" max="100" step="0.01" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Contoh: 85.50">
                                <p class="text-xs text-gray-500 mt-1">Rata-rata nilai rapor kelas 11</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Rata-rata Rapor Kelas 12</label>
                                <input type="number" x-model="formData.grade_9_sem1"
                                       min="0" max="100" step="0.01" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Contoh: 87.25">
                                <p class="text-xs text-gray-500 mt-1">Rata-rata nilai rapor kelas 12 (semester 1)</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Section: Data Orang Tua -->
                    <div class="mb-8">
                        <div class="border-l-4 border-purple-600 pl-4 mb-6">
                            <h3 class="text-md font-semibold text-gray-800">Data Orang Tua/Wali</h3>
                            <p class="text-sm text-gray-600">Informasi orang tua atau wali calon mahasiswa</p>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Data Ayah -->
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <h4 class="font-medium text-gray-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                    Data Ayah
                                </h4>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Lengkap Ayah <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" x-model="formData.parent_name" required
                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Nomor Telepon Ayah <span class="text-red-500">*</span>
                                        </label>
                                        <input type="tel" x-model="formData.parent_phone" required
                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Pekerjaan Ayah <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" x-model="formData.parent_job" required
                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                </div>
                            </div>

                            <!-- Data Ibu -->
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <h4 class="font-medium text-gray-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                    Data Ibu
                                </h4>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Lengkap Ibu <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" x-model="formData.mother_name" required
                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Pekerjaan Ibu <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" x-model="formData.mother_job" required
                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Penghasilan Keluarga per Bulan
                                        </label>
                                        <input type="number" x-model="formData.parent_income"
                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                               placeholder="Contoh: 5000000">
                                        <p class="text-xs text-gray-500 mt-1">Penghasilan gabungan orang tua (opsional)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(strtoupper($registration->path->code) === 'PRE' || strtoupper($registration->path->code) === 'PRESTASI')
                    <!-- Section: Data Prestasi -->
                    <div class="mb-8">
                        <div class="border-l-4 border-amber-600 pl-4 mb-6">
                            <h3 class="text-md font-semibold text-gray-800">Data Prestasi</h3>
                            <p class="text-sm text-gray-600">Informasi prestasi yang dimiliki (jalur prestasi)</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Prestasi</label>
                                <input type="text" x-model="formData.achievement_type"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Contoh: Olimpiade Matematika">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Prestasi</label>
                                <select x-model="formData.achievement_level" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Pilih Tingkat Prestasi</option>
                                    <option value="national">Nasional</option>
                                    <option value="provincial">Provinsi</option>
                                    <option value="district">Kabupaten/Kota</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Peringkat yang Diraih</label>
                                <input type="number" x-model="formData.achievement_rank"
                                       min="1" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Contoh: 1">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Penyelenggara</label>
                                <input type="text" x-model="formData.achievement_organizer"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Contoh: Kementerian Pendidikan dan Kebudayaan">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Prestasi</label>
                                <input type="date" x-model="formData.achievement_date"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                Field bertanda <span class="text-red-500 font-medium">*</span> wajib diisi
                            </div>
                            <button type="submit" :disabled="loading"
                                    class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-medium py-3 px-8 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span x-show="!loading" class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6a1 1 0 10-2 0v5.586l-1.293-1.293z"></path>
                                        <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v4a1 1 0 11-2 0V4H7v10h6v-2a1 1 0 112 0v2a2 2 0 01-2 2H7a2 2 0 01-2-2V4z"></path>
                                    </svg>
                                    Simpan & Lanjutkan
                                </span>
                                <span x-show="loading" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Menyimpan Data...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Step 2: Upload Dokumen -->
            <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        Upload Dokumen Persyaratan
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Upload semua dokumen yang diperlukan sesuai jalur pendaftaran</p>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <template x-for="(docInfo, docType) in requiredDocuments" :key="docType">
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <h4 class="font-medium text-gray-800" x-text="docInfo.name"></h4>
                                        <span x-show="docInfo.required" class="text-red-500 ml-1 text-sm">*</span>
                                    </div>
                                    <span :class="uploadedDocuments[docType] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
                                          class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                                        <svg x-show="uploadedDocuments[docType]" class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <svg x-show="!uploadedDocuments[docType]" class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span x-text="uploadedDocuments[docType] ? 'Terupload' : 'Belum Upload'"></span>
                                    </span>
                                </div>
                                
                                <p class="text-sm text-gray-600 mb-4" x-text="docInfo.description"></p>
                                
                                <div x-show="uploadedDocuments[docType]" class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="font-medium text-green-800 text-sm" x-text="uploadedDocuments[docType]?.file_name"></p>
                                            <p class="text-xs text-green-600">
                                                Status: <span class="font-medium">Terupload</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <form @submit.prevent="uploadDocument(docType, $event)">
                                    <div class="flex items-center space-x-3">
                                        <input type="file" accept=".pdf,.jpg,.jpeg,.png" required
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        <button type="submit" :disabled="loading"
                                                class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-medium py-2 px-4 rounded-lg transition-colors text-sm">
                                            <span x-show="!loading">Upload</span>
                                            <span x-show="loading" class="flex items-center">
                                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Uploading...
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </template>
                    </div>

                    <div class="border-t border-gray-200 pt-6 mt-8">
                        <div class="flex items-center justify-between">
                            <button @click="currentStep = 1" type="button"
                                    class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-8 rounded-lg transition-colors">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Kembali
                                </span>
                            </button>

                            <button @click="goToFinalization()" type="button"
                                    class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-8 rounded-lg transition-colors">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Lanjut ke Finalisasi
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Finalisasi -->
            <div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Review & Finalisasi Pendaftaran
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Pastikan semua data sudah benar sebelum submit pendaftaran final</p>
                </div>

                <div class="p-6">
                    <!-- Data Pribadi Review -->
                    <div class="mb-8">
                        <div class="border-l-4 border-blue-600 pl-4 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Data Pribadi</h3>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">NISN</label>
                                    <p class="text-gray-900 font-medium" x-text="formData.nisn || '-'"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                    <p class="text-gray-900 font-medium" x-text="formData.full_name"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                    <p class="text-gray-900 font-medium" x-text="formData.gender === 'male' ? 'Laki-laki' : 'Perempuan'"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Agama</label>
                                    <p class="text-gray-900 font-medium" x-text="formData.religion"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tempat, Tanggal Lahir</label>
                                    <p class="text-gray-900 font-medium" x-text="`${formData.birth_place}, ${formatDate(formData.birth_date)}`"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                    <p class="text-gray-900 font-medium" x-text="formData.phone_number"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <p class="text-gray-900 font-medium" x-text="formData.email"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Gereja/Denominasi</label>
                                    <p class="text-gray-900 font-medium" x-text="formData.parish_name"></p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                                    <p class="text-gray-900 font-medium" x-text="formData.address"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Akademik Review -->
                    <div class="mb-8">
                        <div class="border-l-4 border-green-600 pl-4 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Data Akademik</h3>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Asal Sekolah</label>
                                    <p class="text-gray-900 font-medium" x-text="formData.school_origin"></p>
                                </div>
                                @if(strtoupper($registration->path->code) === 'PRE' || strtoupper($registration->path->code) === 'PRESTASI')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nilai Rata-rata Kelas 11</label>
                                    <p class="text-gray-900 font-medium" x-text="formData.grade_8_sem2 || '-'"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nilai Rata-rata Kelas 12</label>
                                    <p class="text-gray-900 font-medium" x-text="formData.grade_9_sem1 || '-'"></p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Data Orang Tua Review -->
                    <div class="mb-8">
                        <div class="border-l-4 border-purple-600 pl-4 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Data Orang Tua/Wali</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Data Ayah -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="font-medium text-gray-800 mb-4">Data Ayah</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                        <p class="text-gray-900 font-medium" x-text="formData.parent_name"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                        <p class="text-gray-900 font-medium" x-text="formData.parent_phone"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Pekerjaan</label>
                                        <p class="text-gray-900 font-medium" x-text="formData.parent_job"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Ibu -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="font-medium text-gray-800 mb-4">Data Ibu</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                        <p class="text-gray-900 font-medium" x-text="formData.mother_name"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Pekerjaan</label>
                                        <p class="text-gray-900 font-medium" x-text="formData.mother_job"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Penghasilan Keluarga</label>
                                        <p class="text-gray-900 font-medium" x-text="formData.parent_income ? formatCurrency(formData.parent_income) : '-'"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(strtoupper($registration->path->code) === 'PRE' || strtoupper($registration->path->code) === 'PRESTASI')
                    <!-- Data Prestasi Review -->
                    <div class="mb-8" x-show="formData.achievement_type">
                        <div class="border-l-4 border-amber-600 pl-4 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Data Prestasi</h3>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jenis Prestasi</label>
                                    <p class="text-gray-900 font-medium" x-text="formData.achievement_type || '-'"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tingkat Prestasi</label>
                                    <p class="text-gray-900 font-medium" x-text="getAchievementLevel(formData.achievement_level)"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Peringkat</label>
                                    <p class="text-gray-900 font-medium" x-text="formData.achievement_rank || '-'"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Penyelenggara</label>
                                    <p class="text-gray-900 font-medium" x-text="formData.achievement_organizer || '-'"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Prestasi</label>
                                    <p class="text-gray-900 font-medium" x-text="formData.achievement_date ? formatDate(formData.achievement_date) : '-'"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Status Dokumen -->
                    <div class="mb-8">
                        <div class="border-l-4 border-indigo-600 pl-4 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Status Dokumen</h3>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <template x-for="(docInfo, docType) in requiredDocuments" :key="docType">
                                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                                        <span class="font-medium text-gray-700" x-text="docInfo.name"></span>
                                        <span :class="uploadedDocuments[docType] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                              class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                                            <svg x-show="uploadedDocuments[docType]" class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            <svg x-show="!uploadedDocuments[docType]" class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span x-text="uploadedDocuments[docType] ? 'Lengkap' : 'Belum Upload'"></span>
                                        </span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Confirmation -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Perhatian!</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Setelah Anda submit pendaftaran final, data tidak dapat diubah lagi. Pastikan semua informasi sudah benar dan dokumen sudah lengkap.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Final Actions -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-center justify-between">
                            <button @click="currentStep = 2" type="button"
                                    class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-8 rounded-lg transition-colors">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Kembali
                                </span>
                            </button>

                            <button @click="submitFinalRegistration()" :disabled="loading || !canSubmit()"
                                    class="bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white font-medium py-3 px-8 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                <span x-show="!loading" class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Submit Pendaftaran Final
                                </span>
                                <span x-show="loading" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Submitting...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Mode for waiting_decision status -->
        <div x-show="registrationStatus === 'waiting_decision'" class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" clip-rule="evenodd"></path>
                    </svg>
                    Pendaftaran Telah Disubmit - Sedang Direview
                </h2>
                <p class="text-sm text-gray-600 mt-1">Data dan dokumen Anda sedang dalam proses review oleh tim seleksi</p>
            </div>

            <div class="p-6">
                <!-- Show submitted data in read-only mode -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Personal Data Summary -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="font-semibold text-gray-800 mb-4">Data Pribadi</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nama Lengkap:</span>
                                <span class="font-medium">{{ $form->full_name ?? 'Belum diisi' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium">{{ $form->email ?? 'Belum diisi' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Asal Sekolah:</span>
                                <span class="font-medium">{{ $form->school_origin ?? 'Belum diisi' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status Form:</span>
                                <span class="text-green-600 font-medium">
                                    @if($form && $form->is_completed)
                                        ✓ Lengkap
                                    @else
                                        ⚠ Belum Lengkap
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Summary -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="font-semibold text-gray-800 mb-4">Status Dokumen</h3>
                        <div class="space-y-2 text-sm">
                            @foreach($requiredDocuments as $docType => $docInfo)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">{{ $docInfo['name'] }}:</span>
                                    @if(isset($uploadedDocuments[$docType]))
                                        <span class="text-green-600 font-medium">✓ Terupload</span>
                                    @else
                                        <span class="text-red-600 font-medium">✗ Belum Upload</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="mt-8 border-t pt-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Timeline Pendaftaran</h3>
                    <div class="space-y-4">
                        <div class="flex items-center text-sm">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-gray-600">Pendaftaran dibuat: {{ $registration->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($registration->adminPayment && $registration->adminPayment->verification_status === 'approved')
                        <div class="flex items-center text-sm">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-gray-600">Pembayaran diverifikasi: {{ $registration->adminPayment->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                        @if($registration->document_submitted_at)
                        <div class="flex items-center text-sm">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-gray-600">Dokumen disubmit: {{ $registration->document_submitted_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                        <div class="flex items-center text-sm">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3 animate-pulse"></div>
                            <span class="text-blue-600 font-medium">Sedang direview oleh tim seleksi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Panel -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Informasi Penting</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li x-show="registrationStatus === 'waiting_decision'">Berkas Anda sedang dalam proses review oleh tim seleksi</li>
                            <li x-show="registrationStatus === 'waiting_decision'">Hasil seleksi akan diumumkan sesuai jadwal yang telah ditentukan</li>
                            <li x-show="registrationStatus === 'waiting_decision'">Jika ada pertanyaan, silakan hubungi panitia</li>
                            <li x-show="registrationStatus !== 'waiting_decision'">Pastikan semua data yang diisi sesuai dengan dokumen resmi (KTP, Ijazah, dll)</li>
                            <li x-show="registrationStatus !== 'waiting_decision'">Data yang telah disimpan dapat diubah kembali sebelum tahap finalisasi</li>
                            <li x-show="registrationStatus !== 'waiting_decision'">Setelah menyimpan data pribadi, Anda dapat melanjutkan ke tahap upload dokumen</li>
                            <li x-show="registrationStatus !== 'waiting_decision'">Hubungi panitia jika mengalami kendala dalam pengisian formulir</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function registrationWizard() {
    return {
        // FIXED: Selalu mulai dari step 1, tidak ada auto-advance
        currentStep: 1,
        
        loading: false,
        alert: {
            show: false,
            type: 'success',
            message: ''
        },

        // Registration status untuk tracking
        registrationStatus: '{{ $registration->status }}',

        // Form data
        formData: {
            nisn: '{{ old("nisn", $form->nisn ?? "") }}',
            full_name: '{{ old("full_name", $form->full_name ?? "") }}',
            gender: '{{ old("gender", $form->gender ?? "") }}',
            religion: '{{ old("religion", $form->religion ?? "") }}',
            birth_place: '{{ old("birth_place", $form->birth_place ?? "") }}',
            birth_date: '{{ old("birth_date", $form->birth_date ? $form->birth_date->format("Y-m-d") : "") }}',
            phone_number: '{{ old("phone_number", $form->phone_number ?? "") }}',
            email: '{{ old("email", $form->email ?? "") }}',
            address: '{{ old("address", $form->address ?? "") }}',
            parish_name: '{{ old("parish_name", $form->parish_name ?? "") }}',
            school_origin: '{{ old("school_origin", $form->school_origin ?? "") }}',
            grade_8_sem2: '{{ old("grade_8_sem2", $form->grade_8_sem2 ?? "") }}',
            grade_9_sem1: '{{ old("grade_9_sem1", $form->grade_9_sem1 ?? "") }}',
            parent_name: '{{ old("parent_name", $form->parent_name ?? "") }}',
            parent_phone: '{{ old("parent_phone", $form->parent_phone ?? "") }}',
            parent_job: '{{ old("parent_job", $form->parent_job ?? "") }}',
            mother_name: '{{ old("mother_name", $form->mother_name ?? "") }}',
            mother_job: '{{ old("mother_job", $form->mother_job ?? "") }}',
            parent_income: '{{ old("parent_income", $form->parent_income ?? "") }}',
            achievement_type: '{{ old("achievement_type", $form->achievement_type ?? "") }}',
            achievement_level: '{{ old("achievement_level", $form->achievement_level ?? "") }}',
            achievement_rank: '{{ old("achievement_rank", $form->achievement_rank ?? "") }}',
            achievement_organizer: '{{ old("achievement_organizer", $form->achievement_organizer ?? "") }}',
            achievement_date: '{{ old("achievement_date", $form->achievement_date ? $form->achievement_date->format("Y-m-d") : "") }}'
        },

        // Required documents
        requiredDocuments: @json($requiredDocuments ?? []),
        
        // Uploaded documents
        uploadedDocuments: @json($uploadedDocuments ?? []),

        // FIXED: Method init() yang diperbaiki - tidak ada auto-advance
        init() {
            // Selalu mulai dari step 1, biarkan user navigasi manual
            // Ini memungkinkan user untuk mengedit data yang sudah ada
            this.currentStep = 1;
            
            console.log('Registration wizard initialized:', {
                currentStep: this.currentStep,
                registrationStatus: this.registrationStatus,
                hasFormData: !!this.formData.full_name,
                uploadedDocsCount: Object.keys(this.uploadedDocuments).length
            });
        },

        // Method untuk check apakah personal data complete
        isPersonalDataComplete() {
            const requiredFields = [
                'full_name', 'gender', 'religion', 'birth_place', 'birth_date',
                'phone_number', 'email', 'address', 'parish_name', 'school_origin',
                'parent_name', 'parent_phone', 'parent_job', 'mother_name', 'mother_job'
            ];

            return requiredFields.every(field => 
                this.formData[field] && this.formData[field].trim() !== ''
            );
        },

        // Status methods
        getStatusText() {
            const statusMap = {
                'waiting_documents': 'Mengisi Formulir & Upload Dokumen',
                'waiting_decision': 'Menunggu Keputusan Seleksi'
            };
            
            if (this.registrationStatus === 'waiting_decision') {
                return statusMap[this.registrationStatus];
            }
            
            switch (this.currentStep) {
                case 1: return 'Mengisi Data Pribadi';
                case 2: return 'Upload Dokumen';
                case 3: return 'Review & Finalisasi';
                default: return statusMap[this.registrationStatus] || 'Lengkapi Data & Dokumen';
            }
        },

        getStatusClass() {
            if (this.registrationStatus === 'waiting_decision') {
                return 'bg-indigo-100 text-indigo-800 border-indigo-200';
            }
            return 'bg-blue-100 text-blue-800 border-blue-200';
        },

        // Utility methods
        formatDate(dateStr) {
            if (!dateStr) return '-';
            const date = new Date(dateStr);
            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long', 
                year: 'numeric'
            });
        },

        formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        },

        getAchievementLevel(level) {
            const levels = {
                'national': 'Nasional',
                'provincial': 'Provinsi', 
                'district': 'Kabupaten/Kota'
            };
            return levels[level] || '-';
        },

        // Alert methods
        showAlert(type, message) {
            this.alert = {
                show: true,
                type: type,
                message: message
            };

            // Auto hide after 5 seconds
            setTimeout(() => {
                this.alert.show = false;
            }, 5000);
        },

        // Form submission methods
        async savePersonalData() {
            this.loading = true;
            
            try {
                const formData = new FormData();
                
                // Add CSRF token
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                
                // Add form data
                Object.keys(this.formData).forEach(key => {
                    if (this.formData[key]) {
                        formData.append(key, this.formData[key]);
                    }
                });

                const response = await fetch('{{ route("registration.form.save") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.showAlert('success', data.message);
                    this.currentStep = 2;
                } else {
                    this.showAlert('error', data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                this.showAlert('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
            } finally {
                this.loading = false;
            }
        },

        async uploadDocument(docType, event) {
            this.loading = true;
            
            try {
                const formData = new FormData();
                const fileInput = event.target.querySelector('input[type="file"]');
                
                if (!fileInput.files[0]) {
                    this.showAlert('error', 'Silakan pilih file terlebih dahulu');
                    return;
                }

                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                formData.append('document_type', docType);
                formData.append('document_file', fileInput.files[0]);

                const response = await fetch('{{ route("registration.form.upload-document") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.showAlert('success', data.message);
                    
                    // Update uploaded documents list
                    this.uploadedDocuments[docType] = {
                        file_name: fileInput.files[0].name,
                        verification_status: 'pending'
                    };
                    
                    // Clear file input
                    fileInput.value = '';
                } else {
                    this.showAlert('error', data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                this.showAlert('error', 'Terjadi kesalahan saat mengupload dokumen');
            } finally {
                this.loading = false;
            }
        },

        // Navigation methods
        goToFinalization() {
            // Check personal data completion first
            if (!this.isPersonalDataComplete()) {
                this.showAlert('error', 'Silakan lengkapi data pribadi terlebih dahulu di Step 1');
                this.currentStep = 1;
                return;
            }

            // Check required documents
            const requiredDocs = Object.keys(this.requiredDocuments).filter(key => 
                this.requiredDocuments[key].required
            );
            
            const missingDocs = requiredDocs.filter(docType => !this.uploadedDocuments[docType]);
            
            if (missingDocs.length > 0) {
                const missingNames = missingDocs.map(docType => this.requiredDocuments[docType].name);
                this.showAlert('error', `Dokumen wajib yang belum diupload: ${missingNames.join(', ')}`);
                return;
            }

            this.currentStep = 3;
        },

        // Check if can submit
        canSubmit() {
            // Check if personal data is complete
            const isPersonalDataComplete = this.isPersonalDataComplete();

            // Check if all required documents are uploaded
            const requiredDocs = Object.keys(this.requiredDocuments).filter(key => 
                this.requiredDocuments[key].required
            );
            
            const allDocsUploaded = requiredDocs.every(docType => this.uploadedDocuments[docType]);

            return isPersonalDataComplete && allDocsUploaded;
        },

        async submitFinalRegistration() {
            if (!this.canSubmit()) {
                this.showAlert('error', 'Pastikan semua data pribadi dan dokumen wajib sudah lengkap');
                return;
            }

            if (!confirm('Apakah Anda yakin ingin submit pendaftaran final? Setelah disubmit, data tidak dapat diubah lagi.')) {
                return;
            }

            this.loading = true;
            
            try {
                const response = await fetch('{{ route("registration.form.submit") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.showAlert('success', data.message);
                    
                    // Update status and redirect after 2 seconds
                    this.registrationStatus = 'waiting_decision';
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    this.showAlert('error', data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                this.showAlert('error', 'Terjadi kesalahan saat submit pendaftaran');
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

@endsection