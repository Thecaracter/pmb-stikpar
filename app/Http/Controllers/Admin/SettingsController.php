<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\BankAccount;
use App\Models\KipQuota;
use App\Models\RegistrationWave;
use App\Models\RegistrationPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        try {
            // Get configurations with proper handling for empty collections
            $configurations = Configuration::all()->keyBy('key');

            // Get other models with fallback for empty collections
            $bankAccounts = BankAccount::all();
            $kipQuotas = KipQuota::all();
            $waves = RegistrationWave::ordered()->get();
            $registrationPaths = RegistrationPath::ordered()->get();

            // Log for debugging
            Log::info('Settings page loaded', [
                'configurations_count' => $configurations->count(),
                'bank_accounts_count' => $bankAccounts->count(),
                'kip_quotas_count' => $kipQuotas->count(),
                'waves_count' => $waves->count(),
                'registration_paths_count' => $registrationPaths->count()
            ]);

            return view('pages.admin.settings', compact(
                'configurations',
                'bankAccounts',
                'kipQuotas',
                'waves',
                'registrationPaths'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading settings page', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return view with empty collections as fallback
            return view('pages.admin.settings', [
                'configurations' => collect(),
                'bankAccounts' => collect(),
                'kipQuotas' => collect(),
                'waves' => collect(),
                'registrationPaths' => collect(),
            ])->with('error', 'Terjadi kesalahan saat memuat halaman pengaturan. Silakan coba lagi.');
        }
    }

    /**
     * Update system configurations
     */
    public function updateConfigurations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
            'max_upload_size' => 'required|integer|in:1024,2048,3072,4096,5120,10240,15360,20480,25600,51200',
            'allowed_file_types' => 'required|array|min:1',
            'allowed_file_types.*' => 'required|string|in:pdf,jpg,jpeg,png,gif,doc,docx,xls,xlsx',
        ], [
            'contact_email.required' => 'Email kontak harus diisi',
            'contact_email.email' => 'Format email tidak valid',
            'contact_phone.required' => 'Nomor telepon harus diisi',
            'max_upload_size.required' => 'Ukuran maksimal upload harus diisi',
            'max_upload_size.in' => 'Ukuran maksimal upload harus dipilih dari pilihan yang tersedia',
            'allowed_file_types.required' => 'Minimal satu tipe file harus dipilih',
            'allowed_file_types.min' => 'Minimal satu tipe file harus dipilih',
            'allowed_file_types.*.in' => 'Tipe file yang dipilih tidak valid',
        ]);

        if ($validator->fails()) {
            Log::warning('Configuration validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except(['_token', '_method'])
            ]);

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi. Silakan periksa kembali data yang dimasukkan.');
        }

        try {
            DB::beginTransaction();

            // Update configurations
            $configurations = [
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'max_upload_size' => $request->max_upload_size,
                'allowed_file_types' => implode(',', $request->allowed_file_types),
            ];

            foreach ($configurations as $key => $value) {
                Configuration::setValue($key, $value);
            }

            DB::commit();

            Log::info('System configurations updated successfully', [
                'user_id' => auth()->id(),
                'configurations' => $configurations
            ]);

            return redirect()->back()->with('success', 'Konfigurasi sistem berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error updating system configurations', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
                'input' => $request->except(['_token', '_method']),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update registration waves
     */
    public function updateWaves(Request $request)
    {
        // Get dynamic registration paths for validation
        $registrationPaths = RegistrationPath::all();
        $pathCodes = $registrationPaths->pluck('code')->map(function ($code) {
            return strtolower($code);
        })->toArray();

        $validationRules = [
            'waves' => 'required|array|min:1',
            'waves.*.name' => 'required|string|max:100',
            'waves.*.wave_number' => 'required|integer|min:1',
            'waves.*.start_date' => 'required|date',
            'waves.*.end_date' => 'required|date|after_or_equal:waves.*.start_date',
            'waves.*.administration_fee' => 'required|numeric|min:0',
            'waves.*.registration_fee' => 'required|numeric|min:0',
            'waves.*.is_active' => 'nullable|in:on,1,true,0,false',
            'waves.*.available_paths' => 'nullable|array',
        ];

        // Add dynamic validation for each path
        foreach ($pathCodes as $pathCode) {
            $validationRules["waves.*.available_paths.{$pathCode}"] = 'nullable|in:0,1,true,false';
        }

        $validator = Validator::make($request->all(), $validationRules, [
            'waves.required' => 'Minimal harus ada satu gelombang',
            'waves.*.name.required' => 'Nama gelombang harus diisi',
            'waves.*.wave_number.required' => 'Nomor gelombang harus diisi',
            'waves.*.start_date.required' => 'Tanggal mulai harus diisi',
            'waves.*.end_date.required' => 'Tanggal berakhir harus diisi',
            'waves.*.end_date.after_or_equal' => 'Tanggal berakhir harus setelah atau sama dengan tanggal mulai',
            'waves.*.administration_fee.required' => 'Biaya administrasi harus diisi',
            'waves.*.administration_fee.numeric' => 'Biaya administrasi harus berupa angka',
            'waves.*.registration_fee.required' => 'Biaya daftar ulang harus diisi',
            'waves.*.registration_fee.numeric' => 'Biaya daftar ulang harus berupa angka',
        ]);

        if ($validator->fails()) {
            Log::warning('Registration waves validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except(['_token', '_method'])
            ]);

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi gelombang pendaftaran.');
        }

        try {
            DB::beginTransaction();

            // Get existing waves for logging
            $existingWaves = RegistrationWave::all()->toArray();

            // Delete existing waves (use delete instead of truncate for transaction support)
            RegistrationWave::query()->delete();

            // Create new waves
            foreach ($request->waves as $wave) {
                // Convert is_active to boolean
                $isActive = false;
                if (isset($wave['is_active'])) {
                    $isActive = in_array($wave['is_active'], ['on', '1', 'true', true, 1]);
                }

                // Process available_paths dynamically
                $availablePaths = [];
                if (isset($wave['available_paths'])) {
                    foreach ($pathCodes as $pathCode) {
                        $availablePaths[$pathCode] = isset($wave['available_paths'][$pathCode]) ?
                            in_array($wave['available_paths'][$pathCode], ['1', 'true', true, 1]) : true;
                    }
                } else {
                    // Default: all paths available
                    foreach ($pathCodes as $pathCode) {
                        $availablePaths[$pathCode] = true;
                    }
                }

                RegistrationWave::create([
                    'name' => $wave['name'],
                    'wave_number' => $wave['wave_number'],
                    'start_date' => $wave['start_date'],
                    'end_date' => $wave['end_date'],
                    'administration_fee' => $wave['administration_fee'],
                    'registration_fee' => $wave['registration_fee'],
                    'is_active' => $isActive,
                    'available_paths' => $availablePaths,
                ]);
            }

            DB::commit();

            Log::info('Registration waves updated successfully', [
                'user_id' => auth()->id(),
                'old_waves' => $existingWaves,
                'new_waves' => $request->waves
            ]);

            return redirect()->back()->with('success', 'Gelombang pendaftaran berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error updating registration waves', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
                'input' => $request->except(['_token', '_method']),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update bank accounts
     */
    public function updateBankAccounts(Request $request)
    {
        // Log request data untuk debugging
        Log::info('Bank accounts update request received', [
            'request_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url(),
            'has_bank_accounts' => $request->has('bank_accounts'),
            'bank_accounts_count' => $request->has('bank_accounts') ? count($request->bank_accounts) : 0
        ]);

        $validator = Validator::make($request->all(), [
            'bank_accounts' => 'required|array|min:1',
            'bank_accounts.*.bank_name' => 'required|string|max:100',
            'bank_accounts.*.account_number' => 'required|string|max:50',
            'bank_accounts.*.account_holder' => 'required|string|max:100',
            'bank_accounts.*.is_active' => 'nullable|in:on,1,true,0,false',
        ], [
            'bank_accounts.required' => 'Minimal harus ada satu rekening bank',
            'bank_accounts.*.bank_name.required' => 'Nama bank harus diisi',
            'bank_accounts.*.account_number.required' => 'Nomor rekening harus diisi',
            'bank_accounts.*.account_holder.required' => 'Nama pemilik rekening harus diisi',
        ]);

        if ($validator->fails()) {
            Log::warning('Bank accounts validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except(['_token', '_method'])
            ]);

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi rekening bank.');
        }

        try {
            DB::beginTransaction();

            // Check if BankAccount table exists
            if (!DB::getSchemaBuilder()->hasTable('bank_accounts')) {
                throw new \Exception('Table bank_accounts tidak ditemukan. Silakan jalankan migration terlebih dahulu.');
            }

            // Get existing bank accounts for logging
            $existingBanks = BankAccount::all()->toArray();

            // Delete existing bank accounts (use delete instead of truncate for transaction support)
            BankAccount::query()->delete();

            // Create new bank accounts
            foreach ($request->bank_accounts as $account) {
                // Convert is_active to boolean
                $isActive = false;
                if (isset($account['is_active'])) {
                    $isActive = in_array($account['is_active'], ['on', '1', 'true', true, 1]);
                }

                BankAccount::create([
                    'bank_name' => $account['bank_name'],
                    'account_number' => $account['account_number'],
                    'account_holder' => $account['account_holder'],
                    'is_active' => $isActive,
                ]);
            }

            DB::commit();

            Log::info('Bank accounts updated successfully', [
                'user_id' => auth()->id(),
                'old_banks' => $existingBanks,
                'new_banks' => $request->bank_accounts
            ]);

            return redirect()->back()->with('success', 'Rekening bank berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error updating bank accounts', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
                'input' => $request->except(['_token', '_method']),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Create a new bank account
     */
    public function createBankAccount(Request $request)
    {
        // Log request data untuk debugging
        Log::info('Create bank account request received', [
            'request_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        $validator = Validator::make($request->all(), [
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
            'is_active' => 'nullable|in:on,1,true,0,false',
        ], [
            'bank_name.required' => 'Nama bank harus diisi',
            'account_number.required' => 'Nomor rekening harus diisi',
            'account_holder.required' => 'Nama pemilik rekening harus diisi',
        ]);

        if ($validator->fails()) {
            Log::warning('Create bank account validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except(['_token'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Convert is_active to boolean
            $isActive = false;
            if ($request->has('is_active')) {
                $isActive = in_array($request->is_active, ['on', '1', 'true', true, 1]);
            }

            $bankAccount = BankAccount::create([
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'account_holder' => $request->account_holder,
                'is_active' => $isActive,
            ]);

            Log::info('Bank account created successfully', [
                'user_id' => auth()->id(),
                'bank_account' => $bankAccount->toArray()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rekening bank berhasil ditambahkan!',
                'data' => $bankAccount
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating bank account', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
                'input' => $request->except(['_token']),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete bank account
     */
    public function deleteBankAccount($id)
    {
        Log::info('Delete bank account request received', [
            'bank_id' => $id,
            'user_id' => auth()->id()
        ]);

        try {
            $bankAccount = BankAccount::find($id);

            if (!$bankAccount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rekening bank tidak ditemukan'
                ], 404);
            }

            $bankAccount->delete();

            Log::info('Bank account deleted successfully', [
                'user_id' => auth()->id(),
                'deleted_bank' => $bankAccount->toArray()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rekening bank berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting bank account', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
                'bank_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new wave
     */
    public function createWave(Request $request)
    {
        // Log request data untuk debugging
        Log::info('Create wave request received', [
            'request_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'wave_number' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'administration_fee' => 'required|numeric|min:0',
            'registration_fee' => 'required|numeric|min:0',
            'is_active' => 'nullable|in:on,1,true,0,false',
            'available_paths' => 'nullable|array',
        ], [
            'name.required' => 'Nama gelombang harus diisi',
            'wave_number.required' => 'Nomor gelombang harus diisi',
            'start_date.required' => 'Tanggal mulai harus diisi',
            'end_date.required' => 'Tanggal berakhir harus diisi',
            'end_date.after_or_equal' => 'Tanggal berakhir harus setelah atau sama dengan tanggal mulai',
            'administration_fee.required' => 'Biaya administrasi harus diisi',
            'registration_fee.required' => 'Biaya daftar ulang harus diisi',
        ]);

        if ($validator->fails()) {
            Log::warning('Create wave validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except(['_token'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Convert is_active to boolean
            $isActive = false;
            if ($request->has('is_active')) {
                $isActive = in_array($request->is_active, ['on', '1', 'true', true, 1]);
            }

            // Process available paths
            $availablePaths = [];
            if ($request->has('available_paths')) {
                $availablePaths = $request->available_paths;
            }

            $wave = RegistrationWave::create([
                'name' => $request->name,
                'wave_number' => $request->wave_number,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'administration_fee' => $request->administration_fee,
                'registration_fee' => $request->registration_fee,
                'is_active' => $isActive,
                'available_paths' => $availablePaths,
            ]);

            Log::info('Wave created successfully', [
                'user_id' => auth()->id(),
                'wave' => $wave->toArray()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gelombang pendaftaran berhasil ditambahkan!',
                'data' => $wave
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating wave', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
                'input' => $request->except(['_token']),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete wave
     */
    public function deleteWave($id)
    {
        Log::info('Delete wave request received', [
            'wave_id' => $id,
            'user_id' => auth()->id()
        ]);

        try {
            $wave = RegistrationWave::find($id);

            if (!$wave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gelombang pendaftaran tidak ditemukan'
                ], 404);
            }

            $wave->delete();

            Log::info('Wave deleted successfully', [
                'user_id' => auth()->id(),
                'deleted_wave' => $wave->toArray()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gelombang pendaftaran berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting wave', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
                'wave_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update KIP quotas
     */
    public function updateKipQuotas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kip_quotas' => 'required|array|min:1',
            'kip_quotas.*.year' => 'required|integer|min:2020|max:2030',
            'kip_quotas.*.total_quota' => 'required|integer|min:0',
            'kip_quotas.*.remaining_quota' => 'required|integer|min:0',
            'kip_quotas.*.is_active' => 'nullable|in:on,1,true,0,false',
        ], [
            'kip_quotas.required' => 'Minimal harus ada satu kuota KIP',
            'kip_quotas.*.year.required' => 'Tahun harus diisi',
            'kip_quotas.*.year.integer' => 'Tahun harus berupa angka',
            'kip_quotas.*.total_quota.required' => 'Total kuota harus diisi',
            'kip_quotas.*.total_quota.integer' => 'Total kuota harus berupa angka',
            'kip_quotas.*.remaining_quota.required' => 'Sisa kuota harus diisi',
            'kip_quotas.*.remaining_quota.integer' => 'Sisa kuota harus berupa angka',
        ]);

        if ($validator->fails()) {
            Log::warning('KIP quotas validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except(['_token', '_method'])
            ]);

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi kuota KIP.');
        }

        try {
            DB::beginTransaction();

            // Get existing KIP quotas for logging
            $existingQuotas = KipQuota::all()->toArray();

            // Delete existing KIP quotas (use delete instead of truncate for transaction support)
            KipQuota::query()->delete();

            // Create new KIP quotas
            foreach ($request->kip_quotas as $quota) {
                // Convert is_active to boolean
                $isActive = false;
                if (isset($quota['is_active'])) {
                    $isActive = in_array($quota['is_active'], ['on', '1', 'true', true, 1]);
                }

                KipQuota::create([
                    'year' => $quota['year'],
                    'total_quota' => $quota['total_quota'],
                    'remaining_quota' => $quota['remaining_quota'],
                    'is_active' => $isActive,
                ]);
            }

            DB::commit();

            Log::info('KIP quotas updated successfully', [
                'user_id' => auth()->id(),
                'old_quotas' => $existingQuotas,
                'new_quotas' => $request->kip_quotas
            ]);

            return redirect()->back()->with('success', 'Kuota KIP berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error updating KIP quotas', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
                'input' => $request->except(['_token', '_method']),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Create new KIP quota
     */
    public function createKipQuota(Request $request)
    {
        // Log request data untuk debugging
        Log::info('Create KIP quota request received', [
            'request_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        $validator = Validator::make($request->all(), [
            'year' => 'required|integer|min:2020|max:2030',
            'total_quota' => 'required|integer|min:0',
            'remaining_quota' => 'required|integer|min:0',
            'is_active' => 'nullable|in:on,1,true,0,false',
        ], [
            'year.required' => 'Tahun harus diisi',
            'total_quota.required' => 'Total kuota harus diisi',
            'remaining_quota.required' => 'Sisa kuota harus diisi',
        ]);

        if ($validator->fails()) {
            Log::warning('Create KIP quota validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except(['_token'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Convert is_active to boolean
            $isActive = false;
            if ($request->has('is_active')) {
                $isActive = in_array($request->is_active, ['on', '1', 'true', true, 1]);
            }

            $kipQuota = KipQuota::create([
                'year' => $request->year,
                'total_quota' => $request->total_quota,
                'remaining_quota' => $request->remaining_quota,
                'is_active' => $isActive,
            ]);

            Log::info('KIP quota created successfully', [
                'user_id' => auth()->id(),
                'kip_quota' => $kipQuota->toArray()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kuota KIP berhasil ditambahkan!',
                'data' => $kipQuota
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating KIP quota', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
                'input' => $request->except(['_token']),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete KIP quota
     */
    public function deleteKipQuota($id)
    {
        Log::info('Delete KIP quota request received', [
            'quota_id' => $id,
            'user_id' => auth()->id()
        ]);

        try {
            $kipQuota = KipQuota::find($id);

            if (!$kipQuota) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kuota KIP tidak ditemukan'
                ], 404);
            }

            $kipQuota->delete();

            Log::info('KIP quota deleted successfully', [
                'user_id' => auth()->id(),
                'deleted_quota' => $kipQuota->toArray()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kuota KIP berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting KIP quota', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
                'quota_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update registration paths
     */
    public function updateRegistrationPaths(Request $request)
    {
        // Log request data untuk debugging
        Log::info('Registration paths update request received', [
            'request_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url(),
            'has_registration_paths' => $request->has('registration_paths'),
            'registration_paths_count' => $request->has('registration_paths') ? count($request->registration_paths) : 0
        ]);

        $validator = Validator::make($request->all(), [
            'registration_paths' => 'required|array|min:1',
            'registration_paths.*.name' => 'required|string|max:100',
            'registration_paths.*.code' => 'required|string|max:10',
            'registration_paths.*.description' => 'nullable|string|max:500',
            'registration_paths.*.is_active' => 'nullable|in:on,1,true,0,false',
        ], [
            'registration_paths.required' => 'Minimal harus ada satu jalur pendaftaran',
            'registration_paths.*.name.required' => 'Nama jalur pendaftaran harus diisi',
            'registration_paths.*.code.required' => 'Kode jalur pendaftaran harus diisi',
            'registration_paths.*.code.max' => 'Kode jalur pendaftaran maksimal 10 karakter',
            'registration_paths.*.description.max' => 'Deskripsi maksimal 500 karakter',
        ]);

        if ($validator->fails()) {
            Log::warning('Registration paths validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except(['_token', '_method'])
            ]);

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi jalur pendaftaran.');
        }

        try {
            DB::beginTransaction();

            // Get existing paths for logging
            $existingPaths = RegistrationPath::all()->toArray();

            // Delete existing paths (use delete instead of truncate for transaction support)
            RegistrationPath::query()->delete();

            // Create new paths
            foreach ($request->registration_paths as $path) {
                // Convert is_active to boolean
                $isActive = false;
                if (isset($path['is_active'])) {
                    $isActive = in_array($path['is_active'], ['on', '1', 'true', true, 1]);
                }

                RegistrationPath::create([
                    'name' => $path['name'],
                    'code' => strtoupper($path['code']),
                    'description' => $path['description'] ?? null,
                    'is_active' => $isActive,
                ]);
            }

            DB::commit();

            Log::info('Registration paths updated successfully', [
                'user_id' => auth()->id(),
                'old_paths' => $existingPaths,
                'new_paths' => $request->registration_paths
            ]);

            return redirect()->back()->with('success', 'Jalur pendaftaran berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error updating registration paths', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
                'input' => $request->except(['_token', '_method']),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Create a new registration path
     */
    public function createRegistrationPath(Request $request)
    {
        // Log request data untuk debugging
        Log::info('Create registration path request received', [
            'request_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|unique:registration_paths,code',
            'description' => 'nullable|string|max:500',
            'is_active' => 'nullable|in:on,1,true,0,false',
        ], [
            'name.required' => 'Nama jalur pendaftaran harus diisi',
            'code.required' => 'Kode jalur pendaftaran harus diisi',
            'code.unique' => 'Kode jalur pendaftaran sudah digunakan',
            'code.max' => 'Kode jalur pendaftaran maksimal 10 karakter',
            'description.max' => 'Deskripsi maksimal 500 karakter',
        ]);

        if ($validator->fails()) {
            Log::warning('Create registration path validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except(['_token'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Convert is_active to boolean
            $isActive = false;
            if ($request->has('is_active')) {
                $isActive = in_array($request->is_active, ['on', '1', 'true', true, 1]);
            }

            $registrationPath = RegistrationPath::create([
                'name' => $request->name,
                'code' => $request->code,
                'description' => $request->description,
                'is_active' => $isActive,
            ]);

            Log::info('Registration path created successfully', [
                'user_id' => auth()->id(),
                'registration_path' => $registrationPath->toArray()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jalur pendaftaran berhasil ditambahkan!',
                'data' => $registrationPath
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating registration path', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
                'input' => $request->except(['_token']),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete registration path
     */
    public function deleteRegistrationPath($id)
    {
        Log::info('Delete registration path request received', [
            'path_id' => $id,
            'user_id' => auth()->id()
        ]);

        try {
            $registrationPath = RegistrationPath::find($id);

            if (!$registrationPath) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jalur pendaftaran tidak ditemukan'
                ], 404);
            }

            $registrationPath->delete();

            Log::info('Registration path deleted successfully', [
                'user_id' => auth()->id(),
                'deleted_path' => $registrationPath->toArray()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jalur pendaftaran berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting registration path', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
                'path_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to check if tables exist
     */
    private function checkTables()
    {
        $tables = [
            'configurations',
            'bank_accounts',
            'kip_quotas',
            'registration_waves',
            'registration_paths'
        ];

        $missingTables = [];
        foreach ($tables as $table) {
            if (!DB::getSchemaBuilder()->hasTable($table)) {
                $missingTables[] = $table;
            }
        }

        if (!empty($missingTables)) {
            throw new \Exception('Table(s) tidak ditemukan: ' . implode(', ', $missingTables) . '. Silakan jalankan migration terlebih dahulu.');
        }

        return true;
    }
}