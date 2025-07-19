@extends('layouts.app')

@section('title', 'Cek Hasil Seleksi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 rounded-xl shadow-xl mb-8">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-white opacity-10 rounded-full"></div>
            <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-white opacity-5 rounded-full"></div>
            <div class="relative px-6 py-8 sm:px-8 sm:py-12">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h1 class="text-3xl sm:text-4xl xl:text-5xl font-bold text-white mb-3">
                            Cek Hasil Seleksi PMB
                        </h1>
                        <p class="text-blue-100 text-base sm:text-lg xl:text-xl max-w-4xl leading-relaxed">
                            Lihat status dan hasil seleksi pendaftaran mahasiswa baru Anda
                        </p>
                    </div>
                    <div class="hidden lg:block">
                        <div class="w-20 h-20 xl:w-24 xl:h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 xl:w-12 xl:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Info Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Data Pendaftaran Anda
                </h2>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Nomor Registrasi</dt>
                            <dd class="text-2xl font-bold text-blue-600">{{ $registration->registration_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Gelombang</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $registration->wave->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Jalur Pendaftaran</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $registration->path->name }}</dd>
                        </div>
                    </div>
                    <div class="flex flex-col justify-center">
                        <dt class="text-sm font-medium text-gray-500 mb-4">Status Saat Ini</dt>
                        <dd class="flex justify-center lg:justify-start">
                            <span class="inline-flex items-center px-6 py-3 rounded-full text-lg font-semibold bg-{{ $registration->status_color }}-100 text-{{ $registration->status_color }}-800 border border-{{ $registration->status_color }}-200">
                                {{ $registration->status_label }}
                            </span>
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Content -->
        @if($registration->status === 'waiting_documents')
            <!-- Belum Isi Form -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 px-8 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Isi Formulir Dulu Ya!
                    </h3>
                </div>
                
                <div class="p-8">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <h4 class="font-semibold text-yellow-800 mb-3 text-lg">Langkah Selanjutnya</h4>
                        <p class="text-yellow-700 mb-4">
                            Pembayaran administrasi Anda sudah diverifikasi! Sekarang silakan lengkapi formulir pendaftaran dan upload dokumen yang diperlukan.
                        </p>
                        <a href="{{ route('registration.form') }}" 
                           class="inline-flex items-center px-6 py-3 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Isi Formulir & Upload Dokumen
                        </a>
                    </div>
                </div>
            </div>

        @elseif($registration->status === 'waiting_decision')
            <!-- Menunggu Keputusan -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-8 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Sabar Ya, Sedang Direview
                    </h3>
                </div>
                
                <div class="p-8">
                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6">
                        <h4 class="font-semibold text-indigo-800 mb-3 text-lg">Formulir Sedang Direview</h4>
                        <p class="text-indigo-700 mb-4">
                            Formulir dan dokumen Anda sedang dalam proses review oleh tim seleksi. 
                            Hasil seleksi akan diumumkan sesuai jadwal yang telah ditentukan.
                        </p>
                        <div class="flex items-center text-sm text-indigo-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Proses review membutuhkan waktu, mohon bersabar menunggu
                        </div>
                    </div>
                </div>
            </div>

        @elseif($registration->status === 'passed')
            <!-- LULUS SELEKSI -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-8 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        🎉 Selamat! Anda LULUS Seleksi!
                    </h3>
                </div>
                
                <div class="p-8">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                        <h4 class="font-semibold text-green-800 mb-3 text-lg">Congratulations! 🎊</h4>
                        <p class="text-green-700 mb-4">
                            Selamat! Anda dinyatakan <strong>LULUS</strong> dalam seleksi PMB STIKPAR. 
                            Untuk menyelesaikan proses pendaftaran, silakan upload bukti pembayaran daftar ulang.
                        </p>
                    </div>

                    <!-- Payment Information -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                        <h4 class="font-semibold text-blue-800 mb-3 text-lg">Pembayaran Daftar Ulang</h4>
                        <p class="text-3xl font-bold text-blue-900 mb-3">
                            Rp {{ number_format($registration->wave->registration_fee, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-blue-700 mb-4">
                            Silakan transfer ke salah satu rekening berikut:
                        </p>
                        
                        @if($bankAccounts->count() > 0)
                            <div class="space-y-3">
                                @foreach($bankAccounts as $bank)
                                    <div class="bg-white border border-blue-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $bank->bank_name }}</p>
                                                <p class="text-gray-700 font-mono">{{ $bank->account_number }}</p>
                                                <p class="text-sm text-gray-600">a.n. {{ $bank->account_holder }}</p>
                                            </div>
                                            <button onclick="copyToClipboard('{{ $bank->account_number }}')" 
                                                    class="bg-blue-100 text-blue-800 px-3 py-1 rounded hover:bg-blue-200 transition-colors text-sm">
                                                Copy
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Upload Form -->
                    <form id="finalPaymentForm" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-3">
                                File Bukti Pembayaran Daftar Ulang <span class="text-red-500">*</span>
                            </label>
                            <input type="file" 
                                   id="payment_proof" 
                                   name="payment_proof" 
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-300 rounded-lg"
                                   required>
                            <p class="mt-2 text-sm text-gray-500">
                                Format: JPG, JPEG, PNG, PDF. Maksimal 5MB.
                            </p>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-3">
                                Catatan (Opsional)
                            </label>
                            <textarea id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      maxlength="500"
                                      class="block w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-green-500 focus:border-green-500"
                                      placeholder="Tambahkan catatan jika diperlukan"></textarea>
                            <p class="mt-1 text-sm text-gray-500">
                                <span id="noteCount">0</span>/500 karakter
                            </p>
                        </div>

                        <div class="flex justify-between items-center pt-4">
                            <a href="{{ route('registration.index') }}" 
                               class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                ← Kembali
                            </a>
                            
                            <button type="submit" 
                                    class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="submit-text flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    Upload Bukti Pembayaran
                                </span>
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
            </div>

        @elseif($registration->status === 'waiting_final_payment')
            <!-- Menunggu Verifikasi Daftar Ulang -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-orange-50 to-yellow-50 px-8 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Bukti Daftar Ulang Sedang Diverifikasi
                    </h3>
                </div>
                
                <div class="p-8">
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                        <h4 class="font-semibold text-orange-800 mb-3 text-lg">Pembayaran Sedang Diproses</h4>
                        <p class="text-orange-700 mb-4">
                            Bukti pembayaran daftar ulang Anda sudah berhasil diupload dan sedang dalam proses verifikasi admin. 
                            Silakan tunggu konfirmasi lebih lanjut.
                        </p>
                        @if($registration->registrationPayment)
                            <div class="bg-white border border-orange-200 rounded p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">File:</span>
                                        <span class="text-gray-600">{{ $registration->registrationPayment->file_name }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Tanggal Upload:</span>
                                        <span class="text-gray-600">{{ $registration->registrationPayment->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Nominal:</span>
                                        <span class="text-gray-600">{{ $registration->registrationPayment->formatted_amount }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Status:</span>
                                        <span class="text-orange-600 font-semibold">Menunggu Verifikasi</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        @elseif($registration->status === 'completed')
            <!-- SELESAI - DITERIMA -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-8 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        🎉 Selamat Anda Bergabung Menjadi Mahasiswa STIKPAR TORAJA!
                    </h3>
                </div>
                
                <div class="p-8">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-8 text-center">
                        <div class="mb-6">
                            <svg class="w-20 h-20 text-green-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                            <h4 class="text-2xl font-bold text-green-800 mb-3">PENDAFTARAN SELESAI!</h4>
                            <p class="text-lg text-green-700 mb-6">
                                Selamat! Pembayaran daftar ulang Anda telah diverifikasi. 
                                <strong>Anda resmi menjadi Mahasiswa Baru STIKPAR TORAJA!</strong>
                            </p>
                        </div>
                        
                        <div class="bg-white border border-green-200 rounded-lg p-6 text-left">
                            <h5 class="font-semibold text-gray-900 mb-4 text-center">🎓 Langkah Selanjutnya:</h5>
                            <ul class="space-y-3 text-gray-700">
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Tunggu informasi orientasi mahasiswa baru melalui email/WhatsApp</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Siapkan dokumen untuk registrasi ulang dan matriculation</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Ikuti kegiatan pengenalan kampus dan program studi</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Bergabung dengan grup mahasiswa baru untuk informasi terbaru</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="mt-6 text-sm text-green-600">
                            <p>Selamat datang di keluarga besar STIKPAR TORAJA! 🎊</p>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($registration->status === 'failed')
            <!-- TIDAK LULUS -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-red-50 to-pink-50 px-8 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Hasil Seleksi
                    </h3>
                </div>
                
                <div class="p-8">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-8 text-center">
                        <div class="mb-6">
                            <svg class="w-16 h-16 text-red-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <h4 class="text-xl font-bold text-red-800 mb-3">Mohon Maaf</h4>
                            <p class="text-red-700 mb-6">
                                Anda belum berhasil dalam seleksi PMB kali ini. 
                                <strong>Jangan patah semangat!</strong> Masih ada kesempatan lain untuk mencoba lagi.
                            </p>
                        </div>
                        
                        @if($registration->failure_reason)
                            <div class="bg-white border border-red-200 rounded-lg p-4 mb-6 text-left">
                                <h5 class="font-semibold text-gray-900 mb-2">Keterangan:</h5>
                                <p class="text-gray-700">{{ $registration->failure_reason }}</p>
                            </div>
                        @endif
                        
                        <div class="bg-white border border-red-200 rounded-lg p-6 text-left">
                            <h5 class="font-semibold text-gray-900 mb-4 text-center">💪 Tetap Semangat!</h5>
                            <ul class="space-y-3 text-gray-700">
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Coba lagi di gelombang pendaftaran berikutnya</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Pelajari kembali materi dan persyaratan yang dibutuhkan</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Hubungi admin untuk konsultasi dan saran perbaikan</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Pantau terus pengumuman untuk kesempatan berikutnya</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="mt-6 text-sm text-gray-600">
                            <p><em>"Kegagalan adalah kesempatan untuk memulai lagi dengan lebih cerdas"</em></p>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($registration->status === 'rejected')
            <!-- PENDAFTARAN DITOLAK -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-gray-50 to-red-50 px-8 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                        </svg>
                        Pendaftaran Ditolak
                    </h3>
                </div>
                
                <div class="p-8">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-800 mb-3 text-lg">Informasi Penolakan</h4>
                        <p class="text-gray-700 mb-4">
                            Pendaftaran Anda ditolak oleh admin. Silakan hubungi bagian administrasi untuk informasi lebih lanjut.
                        </p>
                        @if($registration->failure_reason)
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <h5 class="font-semibold text-gray-900 mb-2">Alasan Penolakan:</h5>
                                <p class="text-gray-700">{{ $registration->failure_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('registration.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Character counter for notes
    document.getElementById('notes')?.addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('noteCount').textContent = count;
    });

    // Final payment form submission
    document.getElementById('finalPaymentForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitButton = this.querySelector('button[type="submit"]');
        const submitText = submitButton.querySelector('.submit-text');
        const submitLoading = submitButton.querySelector('.submit-loading');
        
        submitButton.disabled = true;
        submitText.classList.add('hidden');
        submitLoading.classList.remove('hidden');
        
        const formData = new FormData(this);
        
        fetch('{{ route("selection-result.upload") }}', {
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