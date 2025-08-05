<?php

namespace App\Http\Controllers\User;

use App\Models\BankAccount;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Models\PaymentProof;
use App\Models\Configuration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SelectionResultController extends Controller
{
    /**
     * Show selection result page (check kelulusan)
     */
    public function index()
    {
        try {
            $user = Auth::user();

            // Get user's registration
            $registration = Registration::where('user_id', $user->id)
                ->with(['wave', 'path', 'registrationPayment', 'form'])
                ->first();

            if (!$registration) {
                return redirect()->route('registration.index')
                    ->with('error', 'Anda belum memiliki pendaftaran aktif.');
            }

            // Get active bank accounts for payment info
            $bankAccounts = BankAccount::where('is_active', true)
                ->orderBy('bank_name')
                ->get();

            return view('pages.user.selection-result', compact(
                'registration',
                'bankAccounts'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading selection result page', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('registration.index')
                ->with('error', 'Terjadi kesalahan saat memuat halaman hasil seleksi.');
        }
    }

    /**
     * Upload bukti pembayaran daftar ulang
     */
    public function uploadPayment(Request $request)
    {
        $maxUploadSize = Configuration::getValue('max_upload_size', 5120);
        $allowedTypes = Configuration::getValue('allowed_file_types', 'pdf,jpg,jpeg,png');

        $validator = Validator::make($request->all(), [
            'payment_proof' => [
                'required',
                'file',
                'mimes:' . $allowedTypes,
                'max:' . $maxUploadSize,
            ],
            'notes' => 'nullable|string|max:500'
        ], [
            'payment_proof.required' => 'Bukti pembayaran daftar ulang harus diupload',
            'payment_proof.file' => 'File tidak valid',
            'payment_proof.mimes' => 'File harus berformat: ' . strtoupper(str_replace(',', ', ', $allowedTypes)),
            'payment_proof.max' => 'Ukuran file maksimal ' . ($maxUploadSize >= 1024 ? round($maxUploadSize / 1024, 1) . 'MB' : $maxUploadSize . 'KB'),
            'notes.max' => 'Catatan maksimal 500 karakter'
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

            // Get registration that passed selection
            $registration = Registration::where('user_id', $user->id)
                ->where('status', 'passed')
                ->lockForUpdate()
                ->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pendaftaran tidak ditemukan atau belum lulus seleksi'
                ], 400);
            }

            // Check if already uploaded
            $existingPayment = PaymentProof::where('registration_id', $registration->id)
                ->where('payment_type', 'registration')
                ->first();

            if ($existingPayment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bukti pembayaran daftar ulang sudah diupload sebelumnya'
                ], 400);
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
            $fileName = time() . '_registration_' . $user->id . '_' . $registration->registration_number . '.' . $extension;

            // Create directory if not exists
            $uploadPath = public_path('bukti_daftar_ulang');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Move file directly to public folder with alternative method
            $filePath = 'bukti_daftar_ulang/' . $fileName;

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

            // Create payment proof record
            PaymentProof::create([
                'registration_id' => $registration->id,
                'payment_type' => 'registration',
                'file_name' => $originalName,
                'file_path' => $filePath,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'amount' => $registration->wave->registration_fee,
                'verification_status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Update registration status
            $registration->update([
                'status' => 'waiting_final_payment',
            ]);

            DB::commit();

            Log::info('Registration payment proof uploaded successfully', [
                'user_id' => $user->id,
                'registration_id' => $registration->id,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => $file->getSize()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran daftar ulang berhasil diupload! Menunggu verifikasi admin.',
                'data' => [
                    'file_name' => $originalName,
                    'status' => 'waiting_final_payment'
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error uploading registration payment proof', [
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
}