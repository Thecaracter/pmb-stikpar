<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\RegistrationWave;
use App\Models\RegistrationPath;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    /**
     * Display a listing of registrations
     */
    public function index(Request $request)
    {
        try {
            $query = Registration::with(['user', 'wave', 'path', 'form', 'adminPayment', 'registrationPayment']);

            // Filter berdasarkan status
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            // Filter berdasarkan gelombang
            if ($request->has('wave_id') && $request->wave_id !== '') {
                $query->where('wave_id', $request->wave_id);
            }

            // Filter berdasarkan jalur
            if ($request->has('path_id') && $request->path_id !== '') {
                $query->where('path_id', $request->path_id);
            }

            // Search berdasarkan nama, email, atau nomor registrasi
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('registration_number', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('form', function ($formQuery) use ($search) {
                            $formQuery->where('full_name', 'like', "%{$search}%")
                                ->orWhere('phone_number', 'like', "%{$search}%");
                        });
                });
            }

            // Sort
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $registrations = $query->paginate(20)->withQueryString();

            // Data untuk filter
            $waves = RegistrationWave::orderBy('wave_number')->get();
            $paths = RegistrationPath::orderBy('name')->get();

            // Statistics
            $stats = [
                'total' => Registration::count(),
                'pending' => Registration::where('status', 'pending')->count(),
                'waiting_payment' => Registration::where('status', 'waiting_payment')->count(),
                'waiting_documents' => Registration::where('status', 'waiting_documents')->count(),
                'waiting_decision' => Registration::where('status', 'waiting_decision')->count(),
                'passed' => Registration::where('status', 'passed')->count(),
                'failed' => Registration::where('status', 'failed')->count(),
                'waiting_final_payment' => Registration::where('status', 'waiting_final_payment')->count(),
                'completed' => Registration::where('status', 'completed')->count(),
                'rejected' => Registration::where('status', 'rejected')->count(),
            ];

            return view('pages.admin.registrations', compact('registrations', 'waves', 'paths', 'stats'));

        } catch (\Exception $e) {
            Log::error('Error loading registrations page', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('pages.admin.registrations', [
                'registrations' => collect(),
                'waves' => collect(),
                'paths' => collect(),
                'stats' => []
            ])->with('error', 'Terjadi kesalahan saat memuat data pendaftaran.');
        }
    }

    /**
     * Show the specified registration - UPDATED untuk menampilkan semua field form
     */
    public function show($id)
    {
        try {
            $registration = Registration::with([
                'user',
                'wave',
                'path',
                'form',
                'adminPayment',
                'registrationPayment',
                'documentUploads'
            ])->findOrFail($id);

            // If it's an AJAX request, return JSON data
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $registration->id,
                        'registration_number' => $registration->registration_number,
                        'status' => $registration->status,
                        'user' => [
                            'name' => $registration->user->name,
                            'email' => $registration->user->email,
                        ],
                        'wave' => [
                            'name' => $registration->wave->name ?? '-',
                        ],
                        'path' => [
                            'name' => $registration->path->name ?? '-',
                        ],
                        'form' => $registration->form ? [
                            // Data dasar
                            'full_name' => $registration->form->full_name,
                            'email' => $registration->form->email,
                            'phone_number' => $registration->form->phone_number,
                            'birth_date' => $registration->form->birth_date ? $registration->form->birth_date->format('d M Y') : null,
                            'birth_place' => $registration->form->birth_place,
                            'gender' => $registration->form->gender,
                            'address' => $registration->form->address,

                            // Data sekolah
                            'school_origin' => $registration->form->school_origin,
                            'nisn' => $registration->form->nisn,

                            // Data agama & paroki
                            'religion' => $registration->form->religion,
                            'parish_name' => $registration->form->parish_name,

                            // Data orang tua
                            'parent_name' => $registration->form->parent_name,
                            'parent_phone' => $registration->form->parent_phone,
                            'parent_job' => $registration->form->parent_job,
                            'parent_income' => $registration->form->parent_income,
                            'mother_name' => $registration->form->mother_name,
                            'mother_job' => $registration->form->mother_job,

                            // Data prestasi (jika ada)
                            'achievement_type' => $registration->form->achievement_type,
                            'achievement_level' => $registration->form->achievement_level,
                            'achievement_rank' => $registration->form->achievement_rank,
                            'achievement_organizer' => $registration->form->achievement_organizer,
                            'achievement_date' => $registration->form->achievement_date ? $registration->form->achievement_date->format('d M Y') : null,

                            // Data nilai (untuk jalur prestasi)
                            'grade_8_sem2' => $registration->form->grade_8_sem2,
                            'grade_9_sem1' => $registration->form->grade_9_sem1,

                            // Status form
                            'is_completed' => $registration->form->is_completed,
                            'completed_at' => $registration->form->completed_at ? $registration->form->completed_at->format('d M Y H:i') : null,
                        ] : null,
                        'admin_fee_paid' => $registration->admin_fee_paid,
                        'payment_date' => $registration->payment_date ? $registration->payment_date->format('d M Y H:i') : null,
                        'document_submitted_at' => $registration->document_submitted_at ? $registration->document_submitted_at->format('d M Y H:i') : null,
                        'passed_at' => $registration->passed_at ? $registration->passed_at->format('d M Y H:i') : null,
                        'failure_reason' => $registration->failure_reason,
                        'created_at' => $registration->created_at->format('d M Y H:i'),
                        'updated_at' => $registration->updated_at->format('d M Y H:i'),
                        'documents' => $registration->documentUploads->map(function ($doc) {
                            return [
                                'id' => $doc->id,
                                'document_type' => $doc->document_type,
                                'document_name' => $doc->document_name ?? $this->getDocumentDisplayName($doc->document_type),
                                'file_name' => $doc->file_name,
                                'file_path' => $doc->file_path,
                                'file_size' => $this->formatFileSize($doc->file_size),
                                'mime_type' => $doc->mime_type,
                                'verification_status' => $doc->verification_status ?? 'pending',
                                'verification_notes' => $doc->verification_notes,
                                'uploaded_at' => $doc->created_at->format('d M Y H:i'),
                                'download_url' => $this->getDocumentDownloadUrl($doc->file_path),
                            ];
                        }),
                    ]
                ]);
            }

            // For regular requests, return view (create this view if needed)
            return view('pages.admin.registrations.show', compact('registration'));

        } catch (\Exception $e) {
            Log::error('Error loading registration detail', [
                'registration_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pendaftaran tidak ditemukan: ' . $e->getMessage()
                ], 404);
            }

            return redirect()->route('admin.registrations.index')
                ->with('error', 'Data pendaftaran tidak ditemukan.');
        }
    }

    /**
     * Helper function to get document display name
     */
    private function getDocumentDisplayName($documentType)
    {
        $displayNames = [
            'payment_proof' => 'Bukti Pembayaran',
            'diploma' => 'Ijazah',
            'family_card' => 'Kartu Keluarga',
            'id_card' => 'KTP',
            'photo' => 'Foto',
            'kip_certificate' => 'Sertifikat KIP',
            'poverty_certificate' => 'Surat Keterangan Tidak Mampu',
            'report_card' => 'Rapor',
            'achievement_certificate' => 'Sertifikat Prestasi',
            'achievement_recommendation' => 'Surat Rekomendasi Prestasi',
            'achievement_award' => 'Piagam Penghargaan',
            'achievement_documentation' => 'Dokumentasi Prestasi',
            'birth_certificate' => 'Akta Kelahiran',
            'baptism_certificate' => 'Sertifikat Baptis',
            'pastor_recommendation' => 'Surat Rekomendasi Pastor',
            'marriage_certificate' => 'Akta Nikah Orang Tua',
        ];

        return $displayNames[$documentType] ?? ucfirst(str_replace('_', ' ', $documentType));
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

    /**
     * Helper function to get document download URL
     */
    private function getDocumentDownloadUrl($filePath)
    {
        // Check if file starts with 'documents/' (from Storage::disk('public'))
        // or if it's already a full path like 'documents/...'
        if (str_starts_with($filePath, 'documents/')) {
            return asset('storage/' . $filePath);
        } elseif (str_starts_with($filePath, 'storage/')) {
            return asset($filePath);
        } else {
            return asset('storage/' . $filePath);
        }
    }

    /**
     * Update registration status
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,waiting_payment,waiting_documents,waiting_decision,passed,failed,waiting_final_payment,completed,rejected',
            'failure_reason' => 'required_if:status,failed|nullable|string|max:500'
        ], [
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
            'failure_reason.required_if' => 'Alasan penolakan harus diisi jika status ditolak',
            'failure_reason.max' => 'Alasan penolakan maksimal 500 karakter'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $registration = Registration::findOrFail($id);

            $updateData = [
                'status' => $request->status
            ];

            if ($request->status === 'failed' && $request->failure_reason) {
                $updateData['failure_reason'] = $request->failure_reason;
            } elseif ($request->status === 'passed') {
                $updateData['passed_at'] = now();
                $updateData['failure_reason'] = null;
            }

            $registration->update($updateData);

            // Log activity
            Log::info('Registration status updated by admin', [
                'registration_id' => $registration->id,
                'old_status' => $registration->getOriginal('status'),
                'new_status' => $request->status,
                'admin_id' => auth()->id(),
                'failure_reason' => $request->failure_reason ?? null
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status pendaftaran berhasil diupdate!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error updating registration status', [
                'registration_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update registration data
     */
    public function updateData(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'wave_id' => 'required|exists:registration_waves,id',
            'path_id' => 'required|exists:registration_paths,id',
            'admin_fee_paid' => 'nullable|numeric|min:0',
        ], [
            'wave_id.required' => 'Gelombang harus dipilih',
            'wave_id.exists' => 'Gelombang tidak valid',
            'path_id.required' => 'Jalur pendaftaran harus dipilih',
            'path_id.exists' => 'Jalur pendaftaran tidak valid',
            'admin_fee_paid.numeric' => 'Biaya admin harus berupa angka',
            'admin_fee_paid.min' => 'Biaya admin tidak boleh negatif'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $registration = Registration::findOrFail($id);

            $registration->update([
                'wave_id' => $request->wave_id,
                'path_id' => $request->path_id,
                'admin_fee_paid' => $request->admin_fee_paid
            ]);

            // Log activity
            Log::info('Registration data updated by admin', [
                'registration_id' => $registration->id,
                'admin_id' => auth()->id(),
                'changes' => $registration->getChanges()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data pendaftaran berhasil diupdate!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error updating registration data', [
                'registration_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified registration
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $registration = Registration::findOrFail($id);
            $registrationNumber = $registration->registration_number;
            $userName = $registration->user->name;

            $registration->delete();

            // Log activity
            Log::info('Registration deleted by admin', [
                'registration_id' => $id,
                'registration_number' => $registrationNumber,
                'user_name' => $userName,
                'admin_id' => auth()->id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data pendaftaran berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error deleting registration', [
                'registration_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk action for multiple registrations
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registrations' => 'required|array|min:1',
            'registrations.*' => 'exists:registrations,id',
            'action' => 'required|in:accept,reject,delete',
            'failure_reason' => 'required_if:action,reject|nullable|string|max:500'
        ], [
            'registrations.required' => 'Pilih minimal satu pendaftaran',
            'registrations.array' => 'Data pendaftaran tidak valid',
            'registrations.min' => 'Pilih minimal satu pendaftaran',
            'action.required' => 'Aksi harus dipilih',
            'action.in' => 'Aksi tidak valid',
            'failure_reason.required_if' => 'Alasan penolakan harus diisi untuk aksi tolak'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Parse registrations if it's JSON string
            $registrationIds = $request->registrations;
            if (is_string($registrationIds)) {
                $registrationIds = json_decode($registrationIds, true);
            }

            $count = count($registrationIds);

            switch ($request->action) {
                case 'accept':
                    Registration::whereIn('id', $registrationIds)
                        ->update([
                            'status' => 'passed',
                            'passed_at' => now(),
                            'failure_reason' => null
                        ]);
                    $message = "{$count} pendaftaran berhasil diterima";
                    break;

                case 'reject':
                    Registration::whereIn('id', $registrationIds)
                        ->update([
                            'status' => 'failed',
                            'failure_reason' => $request->failure_reason,
                            'passed_at' => null
                        ]);
                    $message = "{$count} pendaftaran berhasil ditolak";
                    break;

                case 'delete':
                    Registration::whereIn('id', $registrationIds)->delete();
                    $message = "{$count} pendaftaran berhasil dihapus";
                    break;
            }

            // Log activity
            Log::info('Bulk action performed on registrations', [
                'registration_ids' => $registrationIds,
                'action' => $request->action,
                'count' => $count,
                'admin_id' => auth()->id(),
                'failure_reason' => $request->failure_reason ?? null
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error performing bulk action', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export registrations to Excel using JavaScript client-side
     */
    public function exportExcel(Request $request)
    {
        // Since we're using client-side export with SheetJS, 
        // this endpoint can return the filtered data as JSON for JavaScript processing
        try {
            $query = Registration::with(['user', 'wave', 'path', 'form']);

            // Apply same filters as index method
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            if ($request->has('wave_id') && $request->wave_id !== '') {
                $query->where('wave_id', $request->wave_id);
            }

            if ($request->has('path_id') && $request->path_id !== '') {
                $query->where('path_id', $request->path_id);
            }

            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('registration_number', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('form', function ($formQuery) use ($search) {
                            $formQuery->where('full_name', 'like', "%{$search}%")
                                ->orWhere('phone_number', 'like', "%{$search}%");
                        });
                });
            }

            $registrations = $query->orderBy('created_at', 'desc')->get();

            $exportData = $registrations->map(function ($registration) {
                return [
                    'No. Registrasi' => $registration->registration_number,
                    'Nama User' => $registration->user->name,
                    'Email' => $registration->user->email,
                    'Nama Lengkap' => $registration->form ? $registration->form->full_name : '',
                    'Gelombang' => $registration->wave->name ?? '',
                    'Jalur' => $registration->path->name ?? '',
                    'Status' => $this->getStatusLabel($registration->status),
                    'Tanggal Daftar' => $registration->created_at->format('d/m/Y H:i'),
                    'NISN' => $registration->form ? $registration->form->nisn : '',
                    'Asal Sekolah' => $registration->form ? $registration->form->school_origin : '',
                    'No. HP' => $registration->form ? $registration->form->phone_number : '',
                    'Nama Ayah' => $registration->form ? $registration->form->parent_name : '',
                    'Nama Ibu' => $registration->form ? $registration->form->mother_name : '',
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $exportData,
                'count' => $exportData->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error preparing export data', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyiapkan data export'
            ], 500);
        }
    }

    /**
     * Get status label in Indonesian
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'Pending',
            'waiting_payment' => 'Menunggu Pembayaran',
            'waiting_documents' => 'Menunggu Dokumen',
            'waiting_decision' => 'Menunggu Keputusan',
            'passed' => 'Lulus',
            'failed' => 'Gagal',
            'waiting_final_payment' => 'Menunggu Pembayaran Akhir',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak'
        ];

        return $labels[$status] ?? ucfirst($status);
    }

    /**
     * Export registrations to PDF
     */
    public function exportPdf(Request $request)
    {
        // TODO: Implement PDF export using libraries like DomPDF or TCPDF
        return response()->json([
            'success' => false,
            'message' => 'Fitur export PDF akan segera tersedia'
        ]);
    }
}