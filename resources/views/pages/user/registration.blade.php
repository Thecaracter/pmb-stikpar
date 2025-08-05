@extends('layouts.app')

@section('title', 'Pendaftaran PMB')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 rounded-xl shadow-xl mb-8">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-white opacity-10 rounded-full"></div>
            <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-white opacity-5 rounded-full"></div>
            <div class="relative px-6 py-8 sm:px-8 sm:py-12">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h1 class="text-3xl sm:text-4xl xl:text-5xl font-bold text-white mb-3">
                            Pendaftaran Mahasiswa Baru
                        </h1>
                        <p class="text-blue-100 text-base sm:text-lg xl:text-xl max-w-4xl leading-relaxed">
                            Selamat datang di sistem pendaftaran PMB STIKPAR. Pilih gelombang dan jalur pendaftaran yang sesuai dengan kebutuhan Anda.
                        </p>
                    </div>
                    <div class="hidden lg:block">
                        <div class="w-20 h-20 xl:w-24 xl:h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 xl:w-12 xl:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($existingRegistration)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Status Pendaftaran Anda
                    </h2>
                </div>
                
                <div class="p-8">
                    <!-- Info Registrasi -->
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
                        <div class="space-y-6">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6">
                                <dl class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Nomor Registrasi</dt>
                                        <dd class="text-3xl font-bold text-blue-600">{{ $existingRegistration->registration_number }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Gelombang</dt>
                                        <dd class="text-xl font-semibold text-gray-900">{{ $existingRegistration->wave->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Jalur Pendaftaran</dt>
                                        <dd class="text-xl font-semibold text-gray-900">{{ $existingRegistration->path->name }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                        
                        <div class="flex flex-col justify-center">
                            <dt class="text-sm font-medium text-gray-500 mb-4">Status Saat Ini</dt>
                            <dd class="flex justify-center xl:justify-start">
                                <span class="inline-flex items-center px-6 py-3 rounded-full text-lg font-semibold bg-{{ $existingRegistration->status_color }}-100 text-{{ $existingRegistration->status_color }}-800 border border-{{ $existingRegistration->status_color }}-200">
                                    {{ $existingRegistration->status_label }}
                                </span>
                            </dd>
                        </div>
                    </div>

                    <!-- FIXED: Handle Rejected Payment -->
                    @if($hasRejectedAdminPayment)
                        <!-- Payment Rejected - Show Re-upload Form -->
                        <div class="border-t pt-6">
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                                <div class="flex items-start">
                                    <svg class="w-6 h-6 text-red-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-red-800 mb-2">Bukti Pembayaran Administrasi Ditolak</h4>
                                        <p class="text-sm text-red-700 mb-2">
                                            Bukti pembayaran administrasi Anda telah ditolak oleh admin. 
                                            Silakan upload ulang bukti pembayaran yang benar.
                                        </p>
                                        @if($rejectedPaymentReason)
                                            <div class="mt-2 p-3 bg-red-100 border border-red-200 rounded">
                                                <p class="text-sm text-red-800"><strong>Alasan penolakan:</strong></p>
                                                <p class="text-sm text-red-700 mt-1">{{ $rejectedPaymentReason }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload Ulang Bukti Pembayaran Administrasi</h3>
                            
                            <!-- Bank Account Info -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                <h4 class="font-medium text-yellow-800 mb-2">Informasi Pembayaran Administrasi</h4>
                                <p class="text-sm text-yellow-700 mb-2">
                                    Silakan transfer biaya administrasi sebesar <strong>Rp {{ number_format($existingRegistration->wave->administration_fee, 0, ',', '.') }}</strong> 
                                    ke salah satu rekening berikut:
                                </p>
                                
                                @if($bankAccounts->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($bankAccounts as $bank)
                                            <div class="bg-white border border-yellow-200 rounded p-3">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $bank->bank_name }}</p>
                                                        <p class="text-sm text-gray-600">{{ $bank->account_number }}</p>
                                                        <p class="text-sm text-gray-600">a.n. {{ $bank->account_holder }}</p>
                                                    </div>
                                                    <button onclick="copyToClipboard('{{ $bank->account_number }}')" 
                                                            class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded hover:bg-blue-200 transition-colors">
                                                        Copy
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-red-600">Belum ada rekening yang tersedia. Silakan hubungi admin.</p>
                                @endif
                            </div>

                            <!-- Re-upload Form -->
                            <form id="adminPaymentForm" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <label for="admin_payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload Ulang Bukti Pembayaran Administrasi <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="file" 
                                               id="admin_payment_proof" 
                                               name="payment_proof" 
                                               accept=".jpg,.jpeg,.png,.pdf"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                               required>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Format yang diizinkan: JPG, JPEG, PNG, PDF. Maksimal 5MB.
                                    </p>
                                </div>

                                <div class="flex justify-end space-x-3">
                                    <button type="submit" 
                                            class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span class="submit-text">Upload Ulang Bukti Pembayaran</span>
                                        <span class="submit-loading hidden">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Mengupload...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>

                    @else
                        <!-- Status Actions - Original Logic -->
                        @if($existingRegistration->status === 'pending')
                            <!-- Upload Bukti Administrasi -->
                            <div class="border-t pt-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload Bukti Pembayaran Administrasi</h3>
                                
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                    <h4 class="font-medium text-yellow-800 mb-2">Informasi Pembayaran Administrasi</h4>
                                    <p class="text-sm text-yellow-700 mb-2">
                                        Silakan transfer biaya administrasi sebesar <strong>Rp {{ number_format($existingRegistration->wave->administration_fee, 0, ',', '.') }}</strong> 
                                        ke salah satu rekening berikut:
                                    </p>
                                    
                                    @if($bankAccounts->count() > 0)
                                        <div class="space-y-2">
                                            @foreach($bankAccounts as $bank)
                                                <div class="bg-white border border-yellow-200 rounded p-3">
                                                    <div class="flex justify-between items-start">
                                                        <div>
                                                            <p class="font-medium text-gray-900">{{ $bank->bank_name }}</p>
                                                            <p class="text-sm text-gray-600">{{ $bank->account_number }}</p>
                                                            <p class="text-sm text-gray-600">a.n. {{ $bank->account_holder }}</p>
                                                        </div>
                                                        <button onclick="copyToClipboard('{{ $bank->account_number }}')" 
                                                                class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded hover:bg-blue-200 transition-colors">
                                                            Copy
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-red-600">Belum ada rekening yang tersedia. Silakan hubungi admin.</p>
                                    @endif
                                </div>

                                <form id="adminPaymentForm" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    
                                    <div>
                                        <label for="admin_payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                                            Upload Bukti Pembayaran Administrasi <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="file" 
                                                   id="admin_payment_proof" 
                                                   name="payment_proof" 
                                                   accept=".jpg,.jpeg,.png,.pdf"
                                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                                   required>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">
                                            Format yang diizinkan: JPG, JPEG, PNG, PDF. Maksimal 5MB.
                                        </p>
                                    </div>

                                    <div class="flex justify-end space-x-3">
                                        <button type="submit" 
                                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                            <span class="submit-text">Upload Bukti Administrasi</span>
                                            <span class="submit-loading hidden">
                                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 818-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Mengupload...
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                        @elseif($existingRegistration->status === 'waiting_payment')
                            <!-- Menunggu Verifikasi Admin -->
                            <div class="border-t pt-6">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h4 class="font-medium text-blue-800 mb-2">Bukti Pembayaran Sudah Diupload</h4>
                                    <p class="text-sm text-blue-700 mb-3">
                                        Bukti pembayaran administrasi Anda sedang dalam proses verifikasi. 
                                        Silakan tunggu konfirmasi dari admin.
                                    </p>
                                    @if($existingRegistration->adminPayment)
                                        <div class="mt-3 text-sm text-blue-600">
                                            <strong>File:</strong> {{ $existingRegistration->adminPayment->file_name }}<br>
                                            <strong>Tanggal Upload:</strong> {{ $existingRegistration->adminPayment->created_at->format('d/m/Y H:i') }}<br>
                                            <strong>Nominal:</strong> {{ $existingRegistration->adminPayment->formatted_amount }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                        @elseif($existingRegistration->status === 'waiting_documents')
                            <!-- Bisa Isi Form -->
                            <div class="border-t pt-6">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <h4 class="font-medium text-green-800 mb-2">Pembayaran Administrasi Disetujui!</h4>
                                    <p class="text-sm text-green-700 mb-3">
                                        Pembayaran administrasi Anda telah diverifikasi. Silakan lanjutkan dengan mengisi formulir pendaftaran dan upload dokumen.
                                    </p>
                                    <a href="{{ route('registration.form') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                        Isi Formulir & Upload Dokumen
                                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Timeline -->
                    @if($existingRegistration->timeline->count() > 1)
                        <div class="border-t pt-6 mt-8">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Timeline Pendaftaran</h4>
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    @foreach($existingRegistration->timeline as $index => $step)
                                        <li>
                                            <div class="relative pb-8">
                                                @if(!$loop->last)
                                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                @endif
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full {{ $step['completed'] ? 'bg-green-500' : 'bg-gray-400' }} flex items-center justify-center ring-8 ring-white">
                                                            @if($step['completed'])
                                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            @else
                                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5">
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900">{{ $step['label'] }}</p>
                                                            <p class="text-sm text-gray-500">{{ $step['date']->format('d/m/Y H:i') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Form Pendaftaran Baru -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Pilih Gelombang dan Jalur Pendaftaran
                    </h2>
                </div>
                
                <form id="registrationForm" class="p-8 space-y-8">
                    @csrf
                    
                    <!-- Pilih Gelombang -->
                    <div class="space-y-6">
                        <label class="flex items-center text-lg font-semibold text-gray-700">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 16L8 19l8 2-4-13z"></path>
                            </svg>
                            Pilih Gelombang Pendaftaran <span class="text-red-500 ml-1">*</span>
                        </label>
                        
                        @if($activeWaves->count() > 0)
                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                                @foreach($activeWaves as $wave)
                                    <div class="relative">
                                        <input type="radio" 
                                               id="wave_{{ $wave->id }}" 
                                               name="wave_id" 
                                               value="{{ $wave->id }}"
                                               data-available-paths="{{ json_encode($wave->available_paths ?? []) }}"
                                               class="sr-only peer wave-selector" 
                                               required>
                                        <label for="wave_{{ $wave->id }}" 
                                               class="block p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:ring-2 peer-checked:ring-blue-100 transition-all duration-200 hover:shadow-md">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <h3 class="font-bold text-gray-900 text-xl mb-2">{{ $wave->name }}</h3>
                                                    <div class="flex flex-col lg:flex-row lg:items-center gap-4 text-sm text-gray-600">
                                                        <div class="flex items-center">
                                                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                            <span class="font-medium">
                                                                {{ \Carbon\Carbon::parse($wave->start_date)->format('d M Y') }} - 
                                                                {{ \Carbon\Carbon::parse($wave->end_date)->format('d M Y') }}
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                            </svg>
                                                            <span class="font-semibold text-green-600">Rp {{ number_format($wave->administration_fee, 0, ',', '.') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0 ml-4">
                                                    <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-blue-600 peer-checked:bg-blue-600 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 rounded-xl">
                                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada gelombang aktif</h3>
                                <p class="mt-2 text-base text-gray-500">Saat ini tidak ada gelombang pendaftaran yang sedang berlangsung.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Pilih Jalur -->
                    <div id="pathSelectionContainer" class="hidden space-y-6">
                        <label class="flex items-center text-lg font-semibold text-gray-700">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Pilih Jalur Pendaftaran <span class="text-red-500 ml-1">*</span>
                        </label>
                        
                        <div id="pathOptions" class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                            @foreach($activePaths as $path)
                                <div class="relative path-option" data-path-code="{{ strtolower($path->code) }}" style="display: none;">
                                    <input type="radio" 
                                           id="path_{{ $path->id }}" 
                                           name="path_id" 
                                           value="{{ $path->id }}"
                                           class="sr-only peer path-selector" 
                                           required>
                                    <label for="path_{{ $path->id }}" 
                                           class="block p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:ring-2 peer-checked:ring-blue-100 transition-all duration-200 hover:shadow-md">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h3 class="font-bold text-gray-900 text-xl mb-2">{{ $path->name }}</h3>
                                                <p class="text-sm text-gray-500 font-medium mb-3">{{ $path->code }}</p>
                                                @if($path->description)
                                                    <p class="text-base text-gray-600 mb-4">{{ $path->description }}</p>
                                                @endif
                                                
                                                @if(strtoupper($path->code) === 'KIP')
                                                    <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                                                        <div class="flex items-center">
                                                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            <span class="text-base font-semibold text-blue-800">
                                                                Kuota KIP: 
                                                                @if(isset($path->kip_quota))
                                                                    <span class="font-bold text-xl">{{ $path->kip_quota }}</span> tersisa
                                                                @else
                                                                    <span class="text-gray-500">Memuat...</span>
                                                                @endif
                                                            </span>
                                                        </div>
                                                        @if(isset($path->kip_quota) && $path->kip_quota == 0)
                                                            <div class="mt-2 flex items-center text-sm text-red-600 font-medium">
                                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                Kuota KIP sudah habis
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-shrink-0 ml-4">
                                                <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-blue-600 peer-checked:bg-blue-600 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div id="submitButtonContainer" class="hidden pt-8">
                        <div class="flex justify-center">
                            <button type="submit" 
                                    class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-12 py-4 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 font-semibold text-lg shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="submit-text flex items-center">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Buat Pendaftaran
                                </span>
                                <span class="submit-loading hidden">
                                    <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 818-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Membuat Pendaftaran...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const waveSelectors = document.querySelectorAll('.wave-selector');
        const pathContainer = document.getElementById('pathSelectionContainer');
        const submitContainer = document.getElementById('submitButtonContainer');
        const pathOptions = document.querySelectorAll('.path-option');
        
        function updatePathOptions(availablePaths) {
            let hasAvailablePaths = false;
            
            pathOptions.forEach(option => {
                option.style.display = 'none';
                const input = option.querySelector('input[type="radio"]');
                if (input) {
                    input.checked = false;
                }
            });
            
            if (availablePaths && typeof availablePaths === 'object') {
                Object.keys(availablePaths).forEach(pathCode => {
                    const isAvailable = availablePaths[pathCode];
                    if (isAvailable) {
                        const pathOption = document.querySelector(`[data-path-code="${pathCode.toLowerCase()}"]`);
                        if (pathOption) {
                            pathOption.style.display = 'block';
                            hasAvailablePaths = true;
                        }
                    }
                });
            }
            
            updateSubmitButton();
        }
        
        function updateSubmitButton() {
            const waveSelected = document.querySelector('.wave-selector:checked');
            const pathSelected = document.querySelector('.path-selector:checked');
            
            if (waveSelected && pathSelected) {
                submitContainer.classList.remove('hidden');
            } else {
                submitContainer.classList.add('hidden');
            }
        }
        
        waveSelectors.forEach(selector => {
            selector.addEventListener('change', function() {
                if (this.checked) {
                    const availablePaths = JSON.parse(this.dataset.availablePaths || '{}');
                    pathContainer.classList.remove('hidden');
                    updatePathOptions(availablePaths);
                    
                    setTimeout(() => {
                        pathContainer.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'start' 
                        });
                    }, 100);
                }
            });
        });
        
        document.querySelectorAll('.path-selector').forEach(selector => {
            selector.addEventListener('change', function() {
                updateSubmitButton();
                
                if (this.checked) {
                    setTimeout(() => {
                        submitContainer.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'start' 
                        });
                    }, 100);
                }
            });
        });
        
        pathContainer.classList.add('hidden');
        submitContainer.classList.add('hidden');
    });

    // Form handlers
    document.getElementById('registrationForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const waveSelected = document.querySelector('.wave-selector:checked');
        const pathSelected = document.querySelector('.path-selector:checked');
        
        if (!waveSelected) {
            alert('Silakan pilih gelombang pendaftaran terlebih dahulu');
            return;
        }
        
        if (!pathSelected) {
            alert('Silakan pilih jalur pendaftaran terlebih dahulu');
            return;
        }
        
        const submitButton = this.querySelector('button[type="submit"]');
        const submitText = submitButton.querySelector('.submit-text');
        const submitLoading = submitButton.querySelector('.submit-loading');
        
        submitButton.disabled = true;
        submitText.classList.add('hidden');
        submitLoading.classList.remove('hidden');
        
        const formData = new FormData(this);
        
        fetch('{{ route("registration.create") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => window.location.reload(), 2000);
            } else {
                showNotification(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan sistem. Silakan coba lagi.', 'error');
        })
        .finally(() => {
            submitButton.disabled = false;
            submitText.classList.remove('hidden');
            submitLoading.classList.add('hidden');
        });
    });

    // Admin payment form
    document.getElementById('adminPaymentForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitButton = this.querySelector('button[type="submit"]');
        const submitText = submitButton.querySelector('.submit-text');
        const submitLoading = submitButton.querySelector('.submit-loading');
        
        submitButton.disabled = true;
        submitText.classList.add('hidden');
        submitLoading.classList.remove('hidden');
        
        const formData = new FormData(this);
        
        fetch('{{ route("registration.upload-admin-payment") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => window.location.reload(), 2000);
            } else {
                showNotification(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan sistem', 'error');
        })
        .finally(() => {
            submitButton.disabled = false;
            submitText.classList.remove('hidden');
            submitLoading.classList.add('hidden');
        });
    });

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Copied!';
            button.classList.add('bg-green-100', 'text-green-800');
            button.classList.remove('bg-blue-100', 'text-blue-800');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-100', 'text-green-800');
                button.classList.add('bg-blue-100', 'text-blue-800');
            }, 2000);
        });
    }

    function showNotification(message, type) {
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        const icon = type === 'success' ? 
            '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>' :
            '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
        
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50`;
        notification.innerHTML = `
            <div class="flex items-center">
                ${icon}
                ${message}
            </div>
        `;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
</script>
@endpush
@endsection