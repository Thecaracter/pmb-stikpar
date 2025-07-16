<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\BankAccount;
use App\Models\KipQuota;
use App\Models\RegistrationWave;
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
            $configurations = Configuration::all()->keyBy('key');
            $bankAccounts = BankAccount::all();
            $kipQuotas = KipQuota::all();
            $waves = RegistrationWave::ordered()->get();

            return view('pages.admin.settings', compact('configurations', 'bankAccounts', 'kipQuotas', 'waves'));
        } catch (\Exception $e) {
            Log::error('Error loading settings page', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat halaman pengaturan.');
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
        $validator = Validator::make($request->all(), [
            'waves' => 'required|array|min:1',
            'waves.*.name' => 'required|string|max:100',
            'waves.*.wave_number' => 'required|integer|min:1',
            'waves.*.start_date' => 'required|date',
            'waves.*.end_date' => 'required|date|after_or_equal:waves.*.start_date',
            'waves.*.administration_fee' => 'required|numeric|min:0',
            'waves.*.registration_fee' => 'required|numeric|min:0',
            'waves.*.is_active' => 'nullable|in:on,1,true,0,false',
        ], [
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

                RegistrationWave::create([
                    'name' => $wave['name'],
                    'wave_number' => $wave['wave_number'],
                    'start_date' => $wave['start_date'],
                    'end_date' => $wave['end_date'],
                    'administration_fee' => $wave['administration_fee'],
                    'registration_fee' => $wave['registration_fee'],
                    'is_active' => $isActive,
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
}