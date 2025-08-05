<?php

namespace App\Http\Controllers\User;

use App\Models\KipQuota;
use App\Models\BankAccount;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Models\DocumentUpload;
use App\Models\PaymentProof;
use App\Models\RegistrationPath;
use App\Models\RegistrationWave;
use App\Models\Configuration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();

            $existingRegistration = Registration::where('user_id', $user->id)
                ->with(['wave', 'path', 'documentUploads', 'adminPayment', 'registrationPayment'])
                ->first();

            // Redirect ke check kelulusan jika sudah dalam tahap advanced
            if ($existingRegistration && in_array($existingRegistration->status, ['waiting_decision', 'passed', 'waiting_final_payment', 'completed', 'failed', 'rejected'])) {
                return redirect()->route('selection-result.index');
            }

            // FIXED: Check for rejected admin payment
            $hasRejectedAdminPayment = false;
            $rejectedPaymentReason = null;

            if ($existingRegistration && $existingRegistration->adminPayment && $existingRegistration->adminPayment->verification_status === 'rejected') {
                $hasRejectedAdminPayment = true;
                $rejectedPaymentReason = $existingRegistration->adminPayment->verification_notes;
            }

            $activeWaves = RegistrationWave::where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->orderBy('wave_number')
                ->get();

            $activePaths = RegistrationPath::where('is_active', true)
                ->orderBy('name')
                ->get();

            $bankAccounts = BankAccount::where('is_active', true)
                ->orderBy('bank_name')
                ->get();

            $currentYear = date('Y');
            $kipQuota = KipQuota::where('year', $currentYear)
                ->where('is_active', true)
                ->first();

            $activePaths->each(function ($path) use ($kipQuota) {
                if (strtoupper($path->code) === 'KIP') {
                    $path->kip_quota = $kipQuota ? $kipQuota->remaining_quota : 0;
                }
            });

            return view('pages.user.registration', compact(
                'existingRegistration',
                'activeWaves',
                'activePaths',
                'bankAccounts',
                'kipQuota',
                'hasRejectedAdminPayment',  // FIXED: Pass this variable
                'rejectedPaymentReason'     // FIXED: Pass this variable
            ));
        } catch (\Exception $e) {
            Log::error('Error loading registration page', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('pages.user.registration', [
                'existingRegistration' => null,
                'activeWaves' => collect(),
                'activePaths' => collect(),
                'bankAccounts' => collect(),
                'kipQuota' => null,
                'hasRejectedAdminPayment' => false,  // FIXED: Pass default value
                'rejectedPaymentReason' => null,     // FIXED: Pass default value
            ])->with('error', 'Terjadi kesalahan saat memuat halaman pendaftaran.');
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wave_id' => 'required|exists:registration_waves,id',
            'path_id' => 'required|exists:registration_paths,id',
        ], [
            'wave_id.required' => 'Gelombang pendaftaran harus dipilih',
            'wave_id.exists' => 'Gelombang pendaftaran tidak valid',
            'path_id.required' => 'Jalur pendaftaran harus dipilih',
            'path_id.exists' => 'Jalur pendaftaran tidak valid',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();

            $existingRegistration = Registration::where('user_id', $user->id)->first();
            if ($existingRegistration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memiliki pendaftaran'
                ], 400);
            }

            $wave = RegistrationWave::where('id', $request->wave_id)
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if (!$wave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gelombang pendaftaran tidak aktif atau sudah berakhir'
                ], 400);
            }

            $path = RegistrationPath::where('id', $request->path_id)
                ->where('is_active', true)
                ->first();

            if (!$path) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jalur pendaftaran tidak aktif'
                ], 400);
            }

            $availablePaths = $wave->available_paths ?? [];
            $pathCode = strtolower($path->code);
            if (!isset($availablePaths[$pathCode]) || !$availablePaths[$pathCode]) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jalur pendaftaran tidak tersedia untuk gelombang ini'
                ], 400);
            }

            if (strtolower($path->code) === 'kip') {
                $currentYear = date('Y');
                $kipQuota = KipQuota::where('year', $currentYear)
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->first();

                if (!$kipQuota) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kuota KIP untuk tahun ini tidak tersedia'
                    ], 400);
                }

                if ($kipQuota->remaining_quota <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kuota KIP untuk tahun ini sudah habis'
                    ], 400);
                }
            }

            DB::beginTransaction();

            $registrationNumber = $this->generateRegistrationNumber($wave, $path);

            $registration = Registration::create([
                'registration_number' => $registrationNumber,
                'user_id' => $user->id,
                'wave_id' => $wave->id,
                'path_id' => $path->id,
                'status' => 'pending',
            ]);

            if (strtolower($path->code) === 'kip') {
                $kipQuota->decrement('remaining_quota');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil dibuat! Silakan upload bukti pembayaran administrasi.',
                'data' => [
                    'registration_number' => $registrationNumber,
                    'administration_fee' => $wave->administration_fee,
                    'wave_name' => $wave->name,
                    'path_name' => $path->name,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error creating registration', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'input' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload bukti pembayaran administrasi - FIXED untuk handle rejected payment
     */
    public function uploadAdminPayment(Request $request)
    {
        $maxUploadSize = Configuration::getValue('max_upload_size', 5120);
        $allowedTypes = Configuration::getValue('allowed_file_types', 'pdf,jpg,jpeg,png');

        $validator = Validator::make($request->all(), [
            'payment_proof' => [
                'required',
                'file',
                'mimes:' . $allowedTypes,
                'max:' . $maxUploadSize,
            ]
        ], [
            'payment_proof.required' => 'Bukti pembayaran administrasi harus diupload',
            'payment_proof.file' => 'File tidak valid',
            'payment_proof.mimes' => 'File harus berformat: ' . strtoupper(str_replace(',', ', ', $allowedTypes)),
            'payment_proof.max' => 'Ukuran file maksimal ' . ($maxUploadSize >= 1024 ? round($maxUploadSize / 1024, 1) . 'MB' : $maxUploadSize . 'KB'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $registration = Registration::where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pendaftaran tidak ditemukan atau sudah tidak bisa upload bukti pembayaran'
                ], 400);
            }

            // FIXED: Handle existing payment (allow re-upload if rejected)
            $existingPayment = PaymentProof::where('registration_id', $registration->id)
                ->where('payment_type', 'administration')
                ->first();

            if ($existingPayment) {
                if ($existingPayment->verification_status === 'approved') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bukti pembayaran administrasi sudah disetujui sebelumnya'
                    ], 400);
                } elseif ($existingPayment->verification_status === 'pending') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bukti pembayaran administrasi sedang dalam proses verifikasi'
                    ], 400);
                }
                // If rejected, allow re-upload (will be handled below)
            }

            $file = $request->file('payment_proof');

            // Additional file validation
            if (!$file || !$file->isValid()) {
                Log::error('Invalid file upload', [
                    'user_id' => $user->id,
                    'file_error' => $file ? $file->getErrorMessage() : 'No file',
                    'upload_error_code' => $file ? $file->getError() : null
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'File tidak valid atau gagal diupload. Silakan coba lagi.'
                ], 400);
            }

            // Check file size manually
            $maxUploadSizeBytes = $maxUploadSize * 1024;
            if ($file->getSize() > $maxUploadSizeBytes) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ukuran file melebihi batas maksimal ' . ($maxUploadSize >= 1024 ? round($maxUploadSize / 1024, 1) . 'MB' : $maxUploadSize . 'KB')
                ], 400);
            }

            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_admin_' . $user->id . '_' . $registration->registration_number . '.' . $extension;

            // Create directory if not exists
            $uploadPath = public_path('bukti_administrasi');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Move file directly to public folder with alternative method
            $filePath = 'bukti_administrasi/' . $fileName;

            try {
                // Alternative method: copy file content
                $fileContent = file_get_contents($file->getPathname());
                if ($fileContent === false) {
                    throw new \Exception('Cannot read uploaded file');
                }

                $destinationPath = public_path($filePath);
                $success = file_put_contents($destinationPath, $fileContent);

                if ($success === false) {
                    throw new \Exception('Cannot write file to destination');
                }

                // Verify file was created and has correct size
                if (!file_exists($destinationPath) || filesize($destinationPath) !== $file->getSize()) {
                    throw new \Exception('File verification failed');
                }

            } catch (\Exception $moveException) {
                Log::error('File save failed', [
                    'error' => $moveException->getMessage(),
                    'user_id' => $user->id,
                    'file_name' => $fileName,
                    'upload_path' => $uploadPath,
                    'file_size' => $file->getSize()
                ]);

                throw new \Exception('Gagal menyimpan file: ' . $moveException->getMessage());
            }

            DB::beginTransaction();

            // FIXED: Delete old rejected payment if exists
            if ($existingPayment && $existingPayment->verification_status === 'rejected') {
                // Delete old file
                if ($existingPayment->file_path && file_exists(public_path($existingPayment->file_path))) {
                    unlink(public_path($existingPayment->file_path));
                }

                Log::info('Deleting old rejected payment before uploading new one', [
                    'user_id' => $user->id,
                    'registration_id' => $registration->id,
                    'old_payment_id' => $existingPayment->id,
                    'old_file_path' => $existingPayment->file_path
                ]);

                $existingPayment->delete();
            }

            // Create new payment proof
            PaymentProof::create([
                'registration_id' => $registration->id,
                'payment_type' => 'administration',
                'file_name' => $originalName,
                'file_path' => $filePath,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'amount' => $registration->wave->administration_fee,
                'verification_status' => 'pending',
            ]);

            $registration->update([
                'status' => 'waiting_payment',
                'payment_date' => now(),
            ]);

            DB::commit();

            Log::info('Payment proof uploaded successfully', [
                'user_id' => $user->id,
                'registration_id' => $registration->id,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'is_reupload' => $existingPayment ? true : false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran administrasi berhasil diupload! Menunggu verifikasi admin.',
                'data' => [
                    'file_name' => $originalName,
                    'status' => 'waiting_payment'
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error uploading admin payment proof', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload payment (backward compatibility)
     */
    public function uploadPayment(Request $request)
    {
        return $this->uploadAdminPayment($request);
    }

    public function cancelRegistration($registrationId)
    {
        try {
            DB::beginTransaction();

            $registration = Registration::where('id', $registrationId)
                ->with('path')
                ->lockForUpdate()
                ->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pendaftaran tidak ditemukan'
                ], 404);
            }

            if (strtolower($registration->path->code) === 'kip') {
                $currentYear = date('Y');
                $kipQuota = KipQuota::where('year', $currentYear)
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->first();

                if ($kipQuota) {
                    $kipQuota->increment('remaining_quota');
                }
            }

            // Delete payment proof files
            $paymentProofs = PaymentProof::where('registration_id', $registration->id)->get();
            foreach ($paymentProofs as $proof) {
                if ($proof->file_path && file_exists(public_path($proof->file_path))) {
                    unlink(public_path($proof->file_path));
                }
            }

            $registration->documentUploads()->delete();
            $registration->paymentProofs()->delete();
            $registration->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil dibatalkan'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error cancelling registration', [
                'error' => $e->getMessage(),
                'registration_id' => $registrationId,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateRegistrationNumber($wave, $path)
    {
        $year = date('Y');
        $waveNumber = str_pad($wave->wave_number, 2, '0', STR_PAD_LEFT);
        $pathCode = strtoupper($path->code);

        $lastRegistration = Registration::where('wave_id', $wave->id)
            ->where('path_id', $path->id)
            ->where('registration_number', 'like', "{$year}{$waveNumber}{$pathCode}%")
            ->orderBy('registration_number', 'desc')
            ->first();

        if ($lastRegistration) {
            $lastNumber = substr($lastRegistration->registration_number, -4);
            $sequence = intval($lastNumber) + 1;
        } else {
            $sequence = 1;
        }

        $sequenceNumber = str_pad($sequence, 4, '0', STR_PAD_LEFT);

        return "{$year}{$waveNumber}{$pathCode}{$sequenceNumber}";
    }
}