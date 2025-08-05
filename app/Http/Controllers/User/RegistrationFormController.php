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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegistrationFormController extends Controller
{
    /**
     * Show the registration form - FIXED untuk handle data parsial
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

            // FIXED: Allow access for waiting_documents dan waiting_payment juga
            if (!in_array($registration->status, ['waiting_documents', 'waiting_decision', 'waiting_payment'])) {
                Log::warning('User trying to access registration form with invalid status', [
                    'user_id' => $user->id,
                    'registration_id' => $registration->id,
                    'current_status' => $registration->status
                ]);

                return redirect()->route('registration.index')
                    ->with('error', 'Anda belum bisa mengisi formulir. Pastkan pembayaran sudah diverifikasi.');
            }

            // FIXED: Always ensure form object exists, even if empty
            $form = $registration->form;
            if (!$form) {
                // Create empty form instance dengan default values
                $form = new RegistrationForm([
                    'registration_id' => $registration->id,
                    'full_name' => '',
                    'email' => '',
                    'phone_number' => '',
                    'birth_date' => null,
                    'birth_place' => '',
                    'gender' => '',
                    'address' => '',
                    'school_origin' => '',
                    'religion' => '',
                    'parish_name' => '',
                    'parent_name' => '',
                    'parent_phone' => '',
                    'parent_job' => '',
                    'mother_name' => '',
                    'mother_job' => '',
                    'parent_income' => null,
                    'nisn' => '',
                    'grade_8_sem2' => null,
                    'grade_9_sem1' => null,
                    'achievement_type' => '',
                    'achievement_level' => '',
                    'achievement_rank' => null,
                    'achievement_organizer' => '',
                    'achievement_date' => null,
                    'is_completed' => false,
                    'completed_at' => null
                ]);

                Log::info('Creating new empty form instance for registration', [
                    'user_id' => $user->id,
                    'registration_id' => $registration->id
                ]);
            }

            // Get required documents based on registration path
            $requiredDocuments = $this->getRequiredDocuments($registration->path->code);

            // FIXED: Always return documents as array, bukan collection
            $uploadedDocuments = $registration->documentUploads()
                ->whereIn('document_type', array_keys($requiredDocuments))
                ->get()
                ->keyBy('document_type')
                ->toArray(); // FIXED: Convert to array for JavaScript

            Log::info('Registration form loaded successfully', [
                'user_id' => $user->id,
                'registration_id' => $registration->id,
                'form_exists' => $form->exists,
                'form_has_data' => !empty($form->full_name),
                'uploaded_documents_count' => count($uploadedDocuments),
                'registration_status' => $registration->status
            ]);

            // FIXED: Debug what we're sending to view
            Log::debug('Data being sent to view', [
                'registration_status' => $registration->status,
                'form_exists' => $form->exists,
                'form_full_name' => $form->full_name ?? 'null',
                'required_documents_count' => count($requiredDocuments),
                'uploaded_documents_keys' => array_keys($uploadedDocuments)
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
     * Save form data - FIXED untuk handle update data parsial
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
            'achievement_organizer' => 'nullable|string|max:100',
            'achievement_date' => 'nullable|date',
        ], [
            // Custom validation messages
            'full_name.required' => 'Nama lengkap harus diisi',
            'gender.required' => 'Jenis kelamin harus dipilih',
            'birth_date.required' => 'Tanggal lahir harus diisi',
            'phone_number.required' => 'Nomor telepon harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'address.required' => 'Alamat harus diisi',
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

        DB::beginTransaction();

        try {
            $user = Auth::user();

            $registration = Registration::where('user_id', $user->id)
                ->whereIn('status', ['waiting_documents', 'waiting_decision', 'waiting_payment'])
                ->first();

            if (!$registration) {
                Log::error('Registration not found for form save', [
                    'user_id' => $user->id
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Pendaftaran tidak ditemukan'
                ], 400);
            }

            // Prepare form data
            $formData = [
                'nisn' => $request->nisn,
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
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

            // FIXED: Always use updateOrCreate untuk handle data parsial
            $form = RegistrationForm::updateOrCreate(
                ['registration_id' => $registration->id],
                $formData
            );

            Log::info('Registration form saved successfully', [
                'user_id' => $user->id,
                'registration_id' => $registration->id,
                'form_id' => $form->id,
                'was_created' => $form->wasRecentlyCreated,
                'action' => $form->wasRecentlyCreated ? 'created' : 'updated'
            ]);

            DB::commit();

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
     * Upload document - Updated for individual upload
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

            // Get original filename and create unique filename
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $uniqueId = time() . '_' . $registration->user_id . '_' . $documentType;
            $filename = $uniqueId . '.' . $extension;

            // Create directory path
            $directory = 'documents/' . $registration->registration_number;

            // Store file
            $filePath = $file->storeAs($directory, $filename, 'public');

            if (!$filePath) {
                Log::error('Failed to store file', [
                    'user_id' => $user->id,
                    'document_type' => $documentType,
                    'original_filename' => $originalName
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan file. Silakan coba lagi.'
                ], 500);
            }

            // Get document name based on type
            $documentName = $this->getDocumentTypeName($documentType);

            // Check if document already exists and delete old file
            $existingDocument = DocumentUpload::where('registration_id', $registration->id)
                ->where('document_type', $documentType)
                ->first();

            if ($existingDocument) {
                // Delete old file
                if (Storage::disk('public')->exists($existingDocument->file_path)) {
                    Storage::disk('public')->delete($existingDocument->file_path);
                }
                $existingDocument->delete();

                Log::info('Replaced existing document', [
                    'user_id' => $user->id,
                    'document_type' => $documentType,
                    'old_file' => $existingDocument->file_path,
                    'new_file' => $filePath
                ]);
            }

            // Save document record
            $documentUpload = DocumentUpload::create([
                'registration_id' => $registration->id,
                'document_type' => $documentType,
                'document_name' => $documentName,
                'file_name' => $originalName,
                'file_path' => $filePath,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'is_required' => 1,
                'verification_status' => 'pending'
            ]);

            Log::info('Document uploaded successfully', [
                'user_id' => $user->id,
                'registration_id' => $registration->id,
                'document_id' => $documentUpload->id,
                'document_type' => $documentType,
                'file_path' => $filePath,
                'file_size' => $file->getSize()
            ]);

            // Check document completion (but don't auto-change status)
            $this->checkDocumentCompletion($registration);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diupload!',
                'document' => [
                    'id' => $documentUpload->id,
                    'type' => $documentType,
                    'name' => $documentName,
                    'filename' => $originalName,
                    'size' => $this->formatFileSize($file->getSize()),
                    'uploaded_at' => $documentUpload->created_at->format('d M Y H:i')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error uploading document', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'document_type' => $request->document_type ?? 'unknown',
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
     * Submit complete registration - Updated for flexible completion
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

            // Check ONLY required documents (not optional ones like marriage certificate)
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
                    'message' => 'Dokumen wajib berikut masih belum diupload: ' . implode(', ', $missingDocuments)
                ], 400);
            }

            // Mark form as completed and update registration status
            $registration->form->update([
                'is_completed' => true,
                'completed_at' => now()
            ]);

            $registration->update([
                'status' => 'waiting_decision'
            ]);

            Log::info('Registration submitted successfully', [
                'user_id' => $user->id,
                'registration_id' => $registration->id,
                'submitted_at' => now()
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
     * Get required documents based on registration path - UPDATED FOR NEW REQUIREMENTS
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
                'name' => 'Surat Nikah',
                'required' => false,
                'description' => 'Fotocopy surat nikah (khusus yang sudah menikah)'
            ],
        ];

        // UPDATED: Tambahkan report_card HANYA untuk jalur PRESTASI
        $pathCodeUpper = strtoupper($pathCode);
        if (in_array($pathCodeUpper, ['PRE', 'PRESTASI'])) {
            $baseDocuments['report_card'] = [
                'name' => 'Rapor',
                'required' => true,
                'description' => 'Fotocopy rapor kelas 11 dan 12 semester 1 yang telah dilegalisir'
            ];
        }

        // Add specific documents based on path
        switch ($pathCodeUpper) {
            case 'KIP':
                $baseDocuments['kip_certificate'] = [
                    'name' => 'Kartu KIP',
                    'required' => true,
                    'description' => 'Fotocopy Kartu Indonesia Pintar (KIP)'
                ];
                // UPDATED: Hapus poverty_certificate requirement untuk KIP
                // $baseDocuments['poverty_certificate'] = [
                //     'name' => 'Surat Keterangan Tidak Mampu',
                //     'required' => true,
                //     'description' => 'SKTM dari kelurahan/desa setempat'
                // ];
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
                // UPDATED: Report card sudah tidak ditambahkan di baseDocuments untuk jalur prestasi
                break;
        }

        Log::debug('Required documents generated', [
            'path_code' => $pathCode,
            'document_count' => count($baseDocuments),
            'required_count' => count(array_filter($baseDocuments, fn($doc) => $doc['required'])),
            'optional_count' => count(array_filter($baseDocuments, fn($doc) => !$doc['required'])),
            'documents' => array_keys($baseDocuments)
        ]);

        return $baseDocuments;
    }

    /**
     * Check if all required documents are uploaded - Updated to only check required docs
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

        // Only check required documents
        foreach ($requiredDocuments as $docType => $docInfo) {
            if ($docInfo['required'] && !in_array($docType, $uploadedDocuments)) {
                $allRequiredUploaded = false;
                $missingDocuments[] = $docInfo['name'];
            }
        }

        Log::info('Document completion check result', [
            'registration_id' => $registration->id,
            'all_required_uploaded' => $allRequiredUploaded,
            'missing_required_documents' => $missingDocuments,
            'form_completed' => $registration->form && $registration->form->is_completed,
            'uploaded_documents_count' => count($uploadedDocuments)
        ]);

        // Note: Don't automatically change status here, let user submit manually
        // This gives more control over when the registration is finalized
    }

    /**
     * Get document type name based on document_type
     */
    private function getDocumentTypeName($documentType)
    {
        $documentTypes = [
            'payment_proof' => 'Bukti Pembayaran',
            'photo' => 'Pas Foto',
            'family_card' => 'Kartu Keluarga',
            'birth_certificate' => 'Akta Kelahiran',
            'id_card' => 'KTP/KIA',
            'diploma' => 'Ijazah SMA/SMK',
            'report_card' => 'Rapor',
            'baptism_certificate' => 'Surat Baptis',
            'pastor_recommendation' => 'Surat Rekomendasi Gereja',
            'marriage_certificate' => 'Surat Nikah',
            'kip_certificate' => 'Kartu KIP',
            'poverty_certificate' => 'Surat Keterangan Tidak Mampu',
            'achievement_certificate' => 'Sertifikat Prestasi',
            'achievement_recommendation' => 'Surat Rekomendasi Prestasi',
            'achievement_award' => 'Piagam Penghargaan',
            'achievement_documentation' => 'Dokumentasi Prestasi',
        ];

        return $documentTypes[$documentType] ?? ucfirst(str_replace('_', ' ', $documentType));
    }

    /**
     * Helper function to format file size
     */
    private function formatFileSize($bytes)
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}