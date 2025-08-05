@extends('layouts.auth')

@section('title', 'Daftar - Pendaftaran Mahasiswa Baru')
@section('heading', 'Daftar Akun Baru')
@section('subheading', 'Buat akun untuk memulai pendaftaran mahasiswa baru')

@section('content')
<div x-data="{ 
    showPassword: false,
    showPasswordConfirmation: false,
    isLoading: false,
    formData: {
        name: '',
        email: '',
        phone: '',
        password: '',
        password_confirmation: '',
        terms: false
    },
    focusedField: null,
    passwordStrength: 0,
    
    checkPasswordStrength() {
        const password = this.formData.password;
        let strength = 0;
        
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/\d/.test(password)) strength++;
        if (/[!@#$%^&*(),.?':{}|<>]/.test(password)) strength++;
        
        this.passwordStrength = strength;
    },
    
    getPasswordStrengthText() {
        switch(this.passwordStrength) {
            case 0:
            case 1: return 'Sangat Lemah';
            case 2: return 'Lemah';
            case 3: return 'Sedang';
            case 4: return 'Kuat';
            case 5: return 'Sangat Kuat';
            default: return '';
        }
    },
    
    getPasswordStrengthColor() {
        switch(this.passwordStrength) {
            case 0:
            case 1: return 'bg-red-500';
            case 2: return 'bg-orange-500';
            case 3: return 'bg-yellow-500';
            case 4: return 'bg-green-500';
            case 5: return 'bg-emerald-500';
            default: return 'bg-gray-300';
        }
    }
}" class="space-y-6">
    
    <!-- Registration Form -->
    <form class="space-y-5" action="{{ route('register') }}" method="POST" @submit="isLoading = true">
        @csrf
        
        <!-- Name Field -->
        <div class="space-y-2">
            <label for="name" class="block text-sm font-semibold text-gray-700">
                Nama Lengkap
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <input 
                    id="name" 
                    name="name" 
                    type="text" 
                    required 
                    x-model="formData.name"
                    @focus="focusedField = 'name'"
                    @blur="focusedField = null"
                    value="{{ old('name') }}"
                    class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 placeholder-gray-400 shadow-sm focus:shadow-lg focus:bg-white"
                    placeholder="Masukkan nama lengkap Anda"
                >
            </div>
            @error('name')
                <div class="flex items-center space-x-2 text-red-500">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="space-y-2">
            <label for="email" class="block text-sm font-semibold text-gray-700">
                Alamat Email
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                    </svg>
                </div>
                <input 
                    id="email" 
                    name="email" 
                    type="email" 
                    required 
                    x-model="formData.email"
                    @focus="focusedField = 'email'"
                    @blur="focusedField = null"
                    value="{{ old('email') }}"
                    class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 placeholder-gray-400 shadow-sm focus:shadow-lg focus:bg-white"
                    placeholder="Masukkan alamat email Anda"
                >
            </div>
            @error('email')
                <div class="flex items-center space-x-2 text-red-500">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Phone Field -->
        <div class="space-y-2">
            <label for="phone" class="block text-sm font-semibold text-gray-700">
                Nomor Telepon
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <input 
                    id="phone" 
                    name="phone" 
                    type="tel" 
                    required 
                    x-model="formData.phone"
                    @focus="focusedField = 'phone'"
                    @blur="focusedField = null"
                    value="{{ old('phone') }}"
                    class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 placeholder-gray-400 shadow-sm focus:shadow-lg focus:bg-white"
                    placeholder="Masukkan nomor telepon Anda"
                >
            </div>
            @error('phone')
                <div class="flex items-center space-x-2 text-red-500">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="space-y-2">
            <label for="password" class="block text-sm font-semibold text-gray-700">
                Kata Sandi
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <input 
                    id="password" 
                    name="password" 
                    :type="showPassword ? 'text' : 'password'"
                    required 
                    x-model="formData.password"
                    @input="checkPasswordStrength"
                    @focus="focusedField = 'password'"
                    @blur="focusedField = null"
                    class="w-full pl-12 pr-14 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 placeholder-gray-400 shadow-sm focus:shadow-lg focus:bg-white"
                    placeholder="Masukkan kata sandi Anda"
                >
                <button 
                    type="button" 
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-blue-500 transition-colors duration-200 z-10"
                >
                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Password Strength Indicator -->
            <div x-show="formData.password.length > 0" class="mt-2">
                <div class="flex items-center space-x-2">
                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div 
                            class="h-full transition-all duration-300 rounded-full"
                            :class="getPasswordStrengthColor()"
                            :style="`width: ${(passwordStrength / 5) * 100}%`"
                        ></div>
                    </div>
                    <span class="text-xs font-medium text-gray-600" x-text="getPasswordStrengthText()"></span>
                </div>
            </div>
            
            @error('password')
                <div class="flex items-center space-x-2 text-red-500">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Password Confirmation Field -->
        <div class="space-y-2">
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">
                Konfirmasi Kata Sandi
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <input 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    :type="showPasswordConfirmation ? 'text' : 'password'"
                    required 
                    x-model="formData.password_confirmation"
                    @focus="focusedField = 'password_confirmation'"
                    @blur="focusedField = null"
                    class="w-full pl-12 pr-14 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 placeholder-gray-400 shadow-sm focus:shadow-lg focus:bg-white"
                    placeholder="Konfirmasi kata sandi Anda"
                >
                <button 
                    type="button" 
                    @click="showPasswordConfirmation = !showPasswordConfirmation"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-blue-500 transition-colors duration-200 z-10"
                >
                    <svg x-show="!showPasswordConfirmation" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <svg x-show="showPasswordConfirmation" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Password Match Indicator -->
            <div x-show="formData.password_confirmation.length > 0" class="mt-2">
                <div class="flex items-center space-x-2">
                    <svg x-show="formData.password === formData.password_confirmation" class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <svg x-show="formData.password !== formData.password_confirmation" class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="text-xs font-medium" :class="formData.password === formData.password_confirmation ? 'text-green-600' : 'text-red-600'" x-text="formData.password === formData.password_confirmation ? 'Kata sandi cocok' : 'Kata sandi tidak cocok'"></span>
                </div>
            </div>
            
            @error('password_confirmation')
                <div class="flex items-center space-x-2 text-red-500">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Terms and Conditions -->
        <div class="space-y-2">
            <div class="flex items-start space-x-3">
                <input 
                    id="terms" 
                    name="terms" 
                    type="checkbox" 
                    required
                    x-model="formData.terms"
                    class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors"
                >
                <label for="terms" class="block text-sm text-gray-700 leading-relaxed">
                    Saya menyetujui 
                    <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline">Syarat dan Ketentuan</a>
                    serta 
                    <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline">Kebijakan Privasi</a>
                    yang berlaku.
                </label>
            </div>
            @error('terms')
                <div class="flex items-center space-x-2 text-red-500">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Register Button -->
        <button 
            type="submit" 
            :disabled="isLoading || !formData.terms"
            class="group relative w-full flex justify-center items-center px-6 py-4 border border-transparent rounded-xl text-base font-semibold text-white bg-gradient-to-r from-blue-600 via-cyan-600 to-indigo-600 hover:from-blue-700 hover:via-cyan-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/40 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-[1.02] active:scale-[0.98]"
        >
            <svg x-show="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span x-text="isLoading ? 'Sedang mendaftar...' : 'Daftar Akun'" class="relative z-10"></span>
        </button>
    </form>

    <!-- Login Link -->
    <div class="text-center pt-4">
        <p class="text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700 transition-colors duration-200 ml-1 hover:underline">
                Masuk sekarang
            </a>
        </p>
    </div>

    <!-- Trust Indicators -->
    <div class="pt-6 border-t border-gray-200">
        <div class="flex items-center justify-center space-x-6 text-xs text-gray-500">
            <div class="flex items-center">
                <svg class="h-4 w-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span>SSL Aman</span>
            </div>
            <div class="flex items-center">
                <svg class="h-4 w-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <span>Privasi Terlindungi</span>
            </div>
            <div class="flex items-center">
                <svg class="h-4 w-4 mr-1 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Verifikasi Email</span>
            </div>
        </div>
    </div>

    <!-- Password Requirements -->
    <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
        <h4 class="text-sm font-semibold text-blue-800 mb-2">Persyaratan Kata Sandi:</h4>
        <ul class="text-xs text-blue-700 space-y-1">
            <li class="flex items-center">
                <svg class="h-3 w-3 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Minimal 8 karakter
            </li>
            <li class="flex items-center">
                <svg class="h-3 w-3 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Mengandung huruf besar dan kecil
            </li>
            <li class="flex items-center">
                <svg class="h-3 w-3 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Mengandung angka dan simbol
            </li>
        </ul>
    </div>
</div>
@endsection

@section('footer', '© 2024 Universitas Anda. Semua hak cipta dilindungi.')

@push('scripts')
<script>
    // Auto-focus first input
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            document.getElementById('name').focus();
        }, 300);
    });

    // Phone number formatting
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        // Add country code if not present
        if (value.length > 0 && !value.startsWith('62')) {
            if (value.startsWith('0')) {
                value = '62' + value.substring(1);
            } else if (value.startsWith('8')) {
                value = '62' + value;
            }
        }
        
        // Format: +62 xxx-xxxx-xxxx
        if (value.length >= 3) {
            value = value.replace(/^(\d{2})(\d{3})(\d{4})(\d{4}).*/, '+$1 $2-$3-$4');
        } else if (value.length >= 2) {
            value = value.replace(/^(\d{2})(\d+)/, '+$1 $2');
        }
        
        e.target.value = value;
    });

    // Add subtle mouse parallax effect
    document.addEventListener('mousemove', function(e) {
        const shapes = document.querySelectorAll('.float-animation, .float-animation-delay, .float-animation-delay-2');
        const x = (e.clientX / window.innerWidth - 0.5) * 20;
        const y = (e.clientY / window.innerHeight - 0.5) * 20;
        
        shapes.forEach((shape, index) => {
            const multiplier = (index + 1) * 0.5;
            shape.style.transform += ` translate(${x * multiplier}px, ${y * multiplier}px)`;
        });
    });

    // Form validation enhancement
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input[required]');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.classList.add('border-red-300');
                    this.classList.remove('border-gray-200');
                } else {
                    this.classList.remove('border-red-300');
                    this.classList.add('border-gray-200');
                }
            });
        });
    });

    // Password strength tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        let tooltipTimeout;
        
        passwordInput.addEventListener('focus', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'absolute z-20 px-3 py-2 text-xs text-white bg-gray-800 rounded-lg shadow-lg -top-2 left-full ml-2 whitespace-nowrap';
            tooltip.textContent = 'Gunakan kombinasi huruf, angka, dan simbol';
            tooltip.id = 'password-tooltip';
            
            this.parentElement.appendChild(tooltip);
            
            setTimeout(() => {
                tooltip.style.opacity = '1';
                tooltip.style.transform = 'translateY(-100%)';
            }, 100);
        });
        
        passwordInput.addEventListener('blur', function() {
            const tooltip = document.getElementById('password-tooltip');
            if (tooltip) {
                tooltip.remove();
            }
        });
    });
</script>
@endpush