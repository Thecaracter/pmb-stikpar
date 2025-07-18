<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\RegistrationForm;
use App\Models\DocumentUpload;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegistrationFormController extends Controller
{
    /**
     * Show the registration form
     */
    public function index()
    {
        try {
            $user = Auth::user();

            // Get user's registration
            $registration = Registration::where('user_id', $user->id)
                ->with(['wave', 'path', 'form', 'documentUploads'])
                ->first();

            if (!$registration) {
                Log::warning('User trying to access registration form without registration', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);

                return redirect()->route('registration.index')
                    ->with('error', 'Anda belum memiliki pendaftaran. Silakan buat pendaftaran terlebih dahulu.');
            }

            // Check if user can fill form (payment must be verified)
            if (!in_array($registration->status, ['waiting_documents', 'waiting_decision'])) {
                Log::warning('User trying to access registration form with invalid status', [
                    'user_id' => $user->id,
                    'registration_id' => $registration->id,
                    'current_status' => $registration->status
                ]);

                return redirect()->route('registration.index')
                    ->with('error', 'Anda belum bisa mengisi formulir. Pastikan pembayaran sudah diverifikasi.');
            }

            // Get or create form
            $form = $registration->form;
            if (!$form) {
                $form = new RegistrationForm(['registration_id' => $registration->id]);
                Log::info('Creating new form instance for registration', [
                    'user_id' => $user->id,
                    'registration_id' => $registration->id
                ]);
            }

            // Get required documents based on registration path
            $requiredDocuments = $this->getRequiredDocuments($registration->path->code);

            // Get uploaded documents
            $uploadedDocuments = $registration->documentUploads()
                ->whereIn('document_type', array_keys($requiredDocuments))
                ->get()
                ->keyBy('document_type');

            Log::info('Registration form loaded successfully', [
                'user_id' => $user->id,
                'registration_id' => $registration->id,
                'form_exists' => $form->exists,
                'uploaded_documents_count' => $uploadedDocuments->count()
            ]);

            return view('pages.user.registration-form', compact(
                'registration',
                'form',
                'requiredDocuments',
                'uploadedDocuments'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading registration form', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('registration.index')
                ->with('error', 'Terjadi kesalahan saat memuat formulir pendaftaran.');
        }
    }

    /**
     * Save form data
     */
    public function saveForm(Request $request)
    {
        Log::info('Form save request received', [
            'user_id' => Auth::id(),
            'request_data' => $request->except(['_token'])
        ]);

        $validator = Validator::make($request->all(), [
            'nisn' => 'nullable|string|max:20',
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'religion' => 'required|string|max:50',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'school_origin' => 'required|string|max:255',
            'parish_name' => 'required|string|max:100',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'parent_job' => 'required|string|max:100',
            'parent_income' => 'nullable|numeric|min:0',
            'mother_name' => 'required|string|max:255',
            'mother_job' => 'required|string|max:100',
            'grade_8_sem2' => 'nullable|numeric|between:0,100',
            'grade_9_sem1' => 'nullable|numeric|between:0,100',
            // Achievement fields (optional)
            'achievement_type' => 'nullable|string|max:100',
            'achievement_level' => 'nullable|in:national,provincial,district',
            'achievement_rank' => 'nullable|integer|min:1',
            'achievement_organizer' => 'nullable|string|max:255',
            'achievement_date' => 'nullable|date',
        ], [
            'nisn.max' => 'NISN maksimal 20 karakter',
            'full_name.required' => 'Nama lengkap harus diisi',
            'full_name.max' => 'Nama lengkap maksimal 255 karakter',
            'gender.required' => 'Jenis kelamin harus dipilih',
            'gender.in' => 'Jenis kelamin tidak valid',
            'religion.required' => 'Agama harus diisi',
            'birth_place.required' => 'Tempat lahir harus diisi',
            'birth_date.required' => 'Tanggal lahir harus diisi',
            'birth_date.date' => 'Format tanggal lahir tidak valid',
            'phone_number.required' => 'Nomor telepon harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'address.required' => 'Alamat harus diisi',
            'address.max' => 'Alamat maksimal 500 karakter',
            'school_origin.required' => 'Asal sekolah harus diisi',
            'parish_name.required' => 'Nama gereja/denominasi harus diisi',
            'parent_name.required' => 'Nama ayah harus diisi',
            'parent_phone.required' => 'Nomor telepon ayah harus diisi',
            'parent_job.required' => 'Pekerjaan ayah harus diisi',
            'mother_name.required' => 'Nama ibu harus diisi',
            'mother_job.required' => 'Pekerjaan ibu harus diisi',
            'grade_8_sem2.numeric' => 'Nilai harus berupa angka',
            'grade_8_sem2.between' => 'Nilai harus antara 0-100',
            'grade_9_sem1.numeric' => 'Nilai harus berupa angka',
            'grade_9_sem1.between' => 'Nilai harus antara 0-100',
            'achievement_rank.integer' => 'Ranking harus berupa angka',
            'achievement_rank.min' => 'Ranking minimal 1',
            'achievement_date.date' => 'Format tanggal prestasi tidak valid',
        ]);

        if ($validator->fails()) {
            Log::warning('Form validation failed', [
                'user_id' => Auth::id(),
                'errors' => $validator->errors()->toArray()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();

            $registration = Registration::where('user_id', $user->id)
                ->whereIn('status', ['waiting_documents', 'waiting_decision'])
                ->first();

            if (!$registration) {
                Log::error('Registration not found for form save', [
                    'user_id' => $user->id
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Pendaftaran tidak ditemukan atau tidak dalam status yang tepat'
                ], 400);
            }

            DB::beginTransaction();

            // Prepare form data
            $formData = [
                'nisn' => $request->nisn,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'birth_date' => $request->birth_date,
                'birth_place' => $request->birth_place,
                'gender' => $request->gender,
                'address' => $request->address,
                'school_origin' => $request->school_origin,
                'religion' => $request->religion,
                'parish_name' => $request->parish_name,
                'parent_name' => $request->parent_name,
                'parent_phone' => $request->parent_phone,
                'parent_job' => $request->parent_job,
                'parent_income' => $request->parent_income,
                'mother_name' => $request->mother_name,
                'mother_job' => $request->mother_job,
                'grade_8_sem2' => $request->grade_8_sem2,
                'grade_9_sem1' => $request->grade_9_sem1,
                // Achievement fields
                'achievement_type' => $request->achievement_type,
                'achievement_level' => $request->achievement_level,
                'achievement_rank' => $request->achievement_rank,
                'achievement_organizer' => $request->achievement_organizer,
                'achievement_date' => $request->achievement_date,
            ];

            // Update or create form
            $form = RegistrationForm::updateOrCreate(
                ['registration_id' => $registration->id],
                $formData
            );

            DB::commit();

            Log::info('Registration form saved successfully', [
                'user_id' => $user->id,
                'registration_id' => $registration->id,
                'form_id' => $form->id,
                'was_created' => $form->wasRecentlyCreated
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data formulir berhasil disimpan!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error saving registration form', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload document - Updated to save directly to public folder with Windows compatibility
     */
    public function uploadDocument(Request $request)
    {
        Log::info('Document upload request received', [
            'user_id' => Auth::id(),
            'document_type' => $request->document_type,
            'original_filename' => $request->file('document_file') ? $request->file('document_file')->getClientOriginalName() : null
        ]);

        // Get configuration values
        $maxUploadSize = Configuration::getValue('max_upload_size', 5120); // Default 5MB in KB
        $allowedTypes = Configuration::getValue('allowed_file_types', 'pdf,jpg,jpeg,png');

        $validator = Validator::make($request->all(), [
            'document_type' => 'required|string',
            'document_file' => [
                'required',
                'file',
                'mimes:' . $allowedTypes,
                'max:' . $maxUploadSize,
            ]
        ], [
            'document_type.required' => 'Tipe dokumen harus dipilih',
            'document_file.required' => 'File dokumen harus diupload',
            'document_file.file' => 'File tidak valid',
            'document_file.mimes' => 'File harus berformat: ' . str_replace(',', ', ', $allowedTypes),
            'document_file.max' => 'Ukuran file maksimal ' . ($maxUploadSize / 1024) . ' MB',
        ]);

        if ($validator->fails()) {
            Log::warning('Document upload validation failed', [
                'user_id' => Auth::id(),
                'errors' => $validator->errors()->toArray()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $registration = Registration::where('user_id', $user->id)->first();

            if (!$registration) {
                Log::error('Registration not found for document upload', [
                    'user_id' => $user->id
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Pendaftaran tidak ditemukan'
                ], 400);
            }

            $file = $request->file('document_file');
            $documentType = $request->document_type;

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

            // Check file size manually (additional check)
            $maxUploadSizeBytes = $maxUploadSize * 1024;
            if ($file->getSize() > $maxUploadSizeBytes) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ukuran file melebihi batas maksimal ' . ($maxUploadSize >= 1024 ? round($maxUploadSize / 1024, 1) . 'MB' : $maxUploadSize . 'KB')
                ], 400);
            }

            // Get required documents to validate document type
            $requiredDocuments = $this->getRequiredDocuments($registration->path->code);

            if (!isset($requiredDocuments[$documentType])) {
                Log::warning('Invalid document type for upload', [
                    'user_id' => $user->id,
                    'registration_id' => $registration->id,
                    'document_type' => $documentType,
                    'path_code' => $registration->path->code
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Tipe dokumen tidak valid untuk jalur pendaftaran ini'
                ], 400);
            }

            // Generate filename
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . $user->id . '_' . $documentType . '.' . $extension;

            // Create directory if not exists
            $uploadPath = public_path('documents/' . $registration->registration_number);
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Set file path for database storage (relative path from public)
            $filePath = 'documents/' . $registration->registration_number . '/' . $fileName;

            DB::beginTransaction();

            // Delete existing document of same type if exists
            $existingDocument = DocumentUpload::where('registration_id', $registration->id)
                ->where('document_type', $documentType)
                ->first();

            if ($existingDocument) {
                Log::info('Replacing existing document', [
                    'user_id' => $user->id,
                    'registration_id' => $registration->id,
                    'document_type' => $documentType,
                    'old_file' => $existingDocument->file_path
                ]);

                // Delete old file from public directory
                $oldFilePath = public_path($existingDocument->file_path);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
                $existingDocument->delete();
            }

            // Use copy method for better Windows compatibility
            $destinationPath = $uploadPath . '/' . $fileName;

            // Method 1: Try to copy file content directly (most reliable)
            $fileContent = file_get_contents($file->getRealPath());
            if ($fileContent === false) {
                throw new \Exception('Tidak dapat membaca file yang diupload');
            }

            $success = file_put_contents($destinationPath, $fileContent);
            if ($success === false) {
                throw new \Exception('Gagal menyimpan file ke direktori public');
            }

            // Verify file was created successfully and has correct size
            if (!file_exists($destinationPath)) {
                throw new \Exception('File gagal tersimpan di direktori tujuan');
            }

            if (filesize($destinationPath) !== $file->getSize()) {
                unlink($destinationPath); // Clean up invalid file
                throw new \Exception('Ukuran file tidak sesuai setelah upload');
            }

            // Create new document record
            $documentUpload = DocumentUpload::create([
                'registration_id' => $registration->id,
                'document_type' => $documentType,
                'document_name' => $requiredDocuments[$documentType]['name'],
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath, // Relative path from public folder
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'verification_status' => 'pending',
                'is_required' => $requiredDocuments[$documentType]['required'] ?? true,
            ]);

            // Update registration status if all required documents are uploaded
            $this->checkDocumentCompletion($registration);

            DB::commit();

            Log::info('Document uploaded successfully', [
                'user_id' => $user->id,
                'registration_id' => $registration->id,
                'document_type' => $documentType,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'document_upload_id' => $documentUpload->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diupload!',
                'data' => [
                    'document_type' => $documentType,
                    'file_name' => $file->getClientOriginalName(),
                    'document_name' => $requiredDocuments[$documentType]['name']
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error uploading document', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'document_type' => $request->document_type ?? null,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit complete registration - FIXED to use waiting_decision
     */
    public function submitRegistration(Request $request)
    {
        Log::info('Registration submit request received', [
            'user_id' => Auth::id()
        ]);

        try {
            $user = Auth::user();

            $registration = Registration::where('user_id', $user->id)
                ->with(['form', 'documentUploads'])
                ->whereIn('status', ['waiting_documents', 'waiting_decision'])
                ->first();

            if (!$registration) {
                Log::error('Registration not found for submit', [
                    'user_id' => $user->id
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Pendaftaran tidak ditemukan'
                ], 400);
            }

            // Check if form is completed
            if (!$registration->form) {
                Log::warning('Form not completed for submit', [
                    'user_id' => $user->id,
                    'registration_id' => $registration->id
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Silakan lengkapi formulir pendaftaran terlebih dahulu'
                ], 400);
            }

            // Check if all required documents are uploaded
            $requiredDocuments = $this->getRequiredDocuments($registration->path->code);
            $uploadedDocuments = $registration->documentUploads->pluck('document_type')->toArray();

            $missingDocuments = [];
            foreach ($requiredDocuments as $docType => $docInfo) {
                if ($docInfo['required'] && !in_array($docType, $uploadedDocuments)) {
                    $missingDocuments[] = $docInfo['name'];
                }
            }

            if (!empty($missingDocuments)) {
                Log::warning('Missing required documents for submit', [
                    'user_id' => $user->id,
                    'registration_id' => $registration->id,
                    'missing_documents' => $missingDocuments
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Dokumen berikut masih belum diupload: ' . implode(', ', $missingDocuments)
                ], 400);
            }

            DB::beginTransaction();

            // Update form as completed
            $registration->form->update([
                'is_completed' => true,
                'completed_at' => now()
            ]);

            // Update registration status to waiting_decision (FIXED)
            $registration->update([
                'status' => 'waiting_decision',
                'document_submitted_at' => now()
            ]);

            DB::commit();

            Log::info('Registration submitted successfully', [
                'user_id' => $user->id,
                'registration_id' => $registration->id,
                'submitted_at' => now(),
                'new_status' => 'waiting_decision'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil disubmit! Dokumen Anda akan direview oleh tim seleksi.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error submitting registration', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get required documents based on registration path
     */
    private function getRequiredDocuments($pathCode)
    {
        Log::debug('Getting required documents for path', [
            'path_code' => $pathCode
        ]);

        $baseDocuments = [
            'photo' => [
                'name' => 'Pas Foto',
                'required' => true,
                'description' => 'Pas foto terbaru ukuran 3x4 dengan latar belakang putih'
            ],
            'family_card' => [
                'name' => 'Kartu Keluarga',
                'required' => true,
                'description' => 'Fotocopy Kartu Keluarga yang masih berlaku'
            ],
            'birth_certificate' => [
                'name' => 'Akta Kelahiran',
                'required' => true,
                'description' => 'Fotocopy akta kelahiran'
            ],
            'id_card' => [
                'name' => 'KTP/KIA',
                'required' => true,
                'description' => 'Fotocopy KTP atau Kartu Identitas Anak'
            ],
            'diploma' => [
                'name' => 'Ijazah SMA/SMK',
                'required' => true,
                'description' => 'Fotocopy ijazah SMA/SMK/MA yang telah dilegalisir'
            ],
            'report_card' => [
                'name' => 'Rapor',
                'required' => true,
                'description' => 'Fotocopy rapor kelas 11 dan 12 semester 1 yang telah dilegalisir'
            ],
            'baptism_certificate' => [
                'name' => 'Surat Baptis',
                'required' => true,
                'description' => 'Surat baptis dari gereja (khusus yang beragama Kristen)'
            ],
            'pastor_recommendation' => [
                'name' => 'Surat Rekomendasi Gereja',
                'required' => true,
                'description' => 'Surat rekomendasi dari pastor/pendeta gereja'
            ],
            'marriage_certificate' => [
                'name' => 'Surat Nikah Orang Tua',
                'required' => true,
                'description' => 'Fotocopy surat nikah gereja orang tua'
            ],
        ];

        // Add specific documents based on path
        switch (strtoupper($pathCode)) {
            case 'KIP':
                $baseDocuments['kip_certificate'] = [
                    'name' => 'Kartu KIP',
                    'required' => true,
                    'description' => 'Fotocopy Kartu Indonesia Pintar (KIP)'
                ];
                $baseDocuments['poverty_certificate'] = [
                    'name' => 'Surat Keterangan Tidak Mampu',
                    'required' => true,
                    'description' => 'SKTM dari kelurahan/desa setempat'
                ];
                break;

            case 'PRE':
            case 'PRESTASI':
                $baseDocuments['achievement_certificate'] = [
                    'name' => 'Sertifikat Prestasi',
                    'required' => true,
                    'description' => 'Fotocopy sertifikat prestasi yang dimiliki'
                ];
                $baseDocuments['achievement_recommendation'] = [
                    'name' => 'Surat Rekomendasi Prestasi',
                    'required' => false,
                    'description' => 'Surat rekomendasi terkait prestasi (opsional)'
                ];
                break;
        }

        Log::debug('Required documents generated', [
            'path_code' => $pathCode,
            'document_count' => count($baseDocuments),
            'document_types' => array_keys($baseDocuments)
        ]);

        return $baseDocuments;
    }

    /**
     * Check if all required documents are uploaded - FIXED to use waiting_decision
     */
    private function checkDocumentCompletion($registration)
    {
        Log::debug('Checking document completion', [
            'registration_id' => $registration->id
        ]);

        $requiredDocuments = $this->getRequiredDocuments($registration->path->code);
        $uploadedDocuments = $registration->documentUploads->pluck('document_type')->toArray();

        $allRequiredUploaded = true;
        $missingDocuments = [];

        foreach ($requiredDocuments as $docType => $docInfo) {
            if ($docInfo['required'] && !in_array($docType, $uploadedDocuments)) {
                $allRequiredUploaded = false;
                $missingDocuments[] = $docInfo['name'];
            }
        }

        Log::info('Document completion check result', [
            'registration_id' => $registration->id,
            'all_required_uploaded' => $allRequiredUploaded,
            'missing_documents' => $missingDocuments,
            'form_completed' => $registration->form && $registration->form->is_completed
        ]);

        // Update status to waiting_decision if all documents uploaded and form completed (FIXED)
        if ($allRequiredUploaded && $registration->form && $registration->form->is_completed) {
            $registration->update(['status' => 'waiting_decision']);

            Log::info('Registration status updated to waiting_decision', [
                'registration_id' => $registration->id
            ]);
        }
    }
}