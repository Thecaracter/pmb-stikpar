<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\PaymentProof;
use App\Models\DocumentUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PaymentVerificationController extends Controller
{
    /**
     * Display payment verification page
     */
    public function index(Request $request = null)
    {
        // Handle case when Request is not injected
        if (!$request) {
            $request = request();
        }

        try {
            $query = PaymentProof::with([
                'registration.user',
                'registration.wave',
                'registration.path'
            ]);

            // Filter berdasarkan status
            if ($request->has('status') && $request->status !== '') {
                $query->where('verification_status', $request->status);
            }

            // Filter berdasarkan payment type
            if ($request->has('payment_type') && $request->payment_type !== '') {
                $query->where('payment_type', $request->payment_type);
            }

            // Filter berdasarkan gelombang
            if ($request->has('wave_id') && $request->wave_id !== '') {
                $query->whereHas('registration', function ($q) use ($request) {
                    $q->where('wave_id', $request->wave_id);
                });
            }

            // Filter berdasarkan jalur
            if ($request->has('path_id') && $request->path_id !== '') {
                $query->whereHas('registration', function ($q) use ($request) {
                    $q->where('path_id', $request->path_id);
                });
            }

            // Search berdasarkan nama atau nomor registrasi
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('registration.user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                        ->orWhereHas('registration', function ($regQuery) use ($search) {
                            $regQuery->where('registration_number', 'like', "%{$search}%");
                        });
                });
            }

            // Sort berdasarkan tanggal terbaru
            $payments = $query->orderBy('created_at', 'desc')
                ->paginate(20)
                ->withQueryString();

            // Data untuk filter
            $waves = \App\Models\RegistrationWave::orderBy('wave_number')->get();
            $paths = \App\Models\RegistrationPath::orderBy('name')->get();

            // Statistics
            $stats = [
                'total' => PaymentProof::count(),
                'pending' => PaymentProof::where('verification_status', 'pending')->count(),
                'approved' => PaymentProof::where('verification_status', 'approved')->count(),
                'rejected' => PaymentProof::where('verification_status', 'rejected')->count(),
            ];

            return view('pages.admin.payment-verification', compact(
                'payments',
                'waves',
                'paths',
                'stats'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading payment verification page', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('pages.admin.payment-verification', [
                'payments' => collect(),
                'waves' => collect(),
                'paths' => collect(),
                'stats' => [
                    'total' => 0,
                    'pending' => 0,
                    'approved' => 0,
                    'rejected' => 0,
                ]
            ])->with('error', 'Terjadi kesalahan saat memuat halaman verifikasi pembayaran.');
        }
    }

    /**
     * Show payment detail
     */
    public function show($id)
    {
        try {
            $payment = PaymentProof::with([
                'registration.user',
                'registration.wave',
                'registration.path',
                'registration.documentUploads'
            ])->findOrFail($id);

            return view('pages.admin.payment-detail', compact('payment'));

        } catch (\Exception $e) {
            Log::error('Error loading payment detail', [
                'payment_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->route('admin.payment-verification.index')
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }
    }

    /**
     * Approve payment
     */
    public function approve(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:500'
        ], [
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
            DB::beginTransaction();

            $payment = PaymentProof::with('registration')->findOrFail($id);

            if ($payment->verification_status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran sudah diverifikasi sebelumnya'
                ], 400);
            }

            // Update payment status
            $payment->update([
                'verification_status' => 'approved'
            ]);

            // Update registration status and admin fee
            $registration = $payment->registration;
            $registration->update([
                'status' => 'waiting_documents',
                'admin_fee_paid' => $payment->amount
            ]);

            // Log activity
            Log::info('Payment approved', [
                'payment_id' => $payment->id,
                'registration_id' => $registration->id,
                'admin_id' => auth()->id(),
                'amount' => $payment->amount,
                'notes' => $request->notes
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil disetujui!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error approving payment', [
                'payment_id' => $id,
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
     * Reject payment
     */
    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:500'
        ], [
            'reason.required' => 'Alasan penolakan harus diisi',
            'reason.max' => 'Alasan penolakan maksimal 500 karakter'
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

            $payment = PaymentProof::with('registration')->findOrFail($id);

            if ($payment->verification_status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran sudah diverifikasi sebelumnya'
                ], 400);
            }

            // Update payment status
            $payment->update([
                'verification_status' => 'rejected'
            ]);

            // Update registration status back to pending
            $registration = $payment->registration;
            $registration->update([
                'status' => 'pending',
                'admin_fee_paid' => null
            ]);

            // Log activity
            Log::info('Payment rejected', [
                'payment_id' => $payment->id,
                'registration_id' => $registration->id,
                'admin_id' => auth()->id(),
                'reason' => $request->reason
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran ditolak. Mahasiswa akan diminta upload ulang.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error rejecting payment', [
                'payment_id' => $id,
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
     * Bulk approve payments
     */
    public function bulkApprove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_ids' => 'required|array|min:1',
            'payment_ids.*' => 'required|exists:payment_proofs,id',
            'notes' => 'nullable|string|max:500'
        ], [
            'payment_ids.required' => 'Pilih minimal satu pembayaran',
            'payment_ids.array' => 'Data pembayaran tidak valid',
            'payment_ids.min' => 'Pilih minimal satu pembayaran',
            'payment_ids.*.exists' => 'Data pembayaran tidak valid'
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

            $payments = PaymentProof::with('registration')
                ->whereIn('id', $request->payment_ids)
                ->where('verification_status', 'pending')
                ->get();

            if ($payments->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada pembayaran yang dapat disetujui'
                ], 400);
            }

            $approved_count = 0;

            foreach ($payments as $payment) {
                // Update payment status
                $payment->update([
                    'verification_status' => 'approved'
                ]);

                // Update registration status
                $payment->registration->update([
                    'status' => 'waiting_documents',
                    'admin_fee_paid' => $payment->amount
                ]);

                $approved_count++;
            }

            // Log activity
            Log::info('Bulk payment approval', [
                'payment_ids' => $request->payment_ids,
                'approved_count' => $approved_count,
                'admin_id' => auth()->id(),
                'notes' => $request->notes
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil menyetujui {$approved_count} pembayaran"
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error bulk approving payments', [
                'payment_ids' => $request->payment_ids ?? [],
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
     * Get payment statistics
     */
    public function statistics()
    {
        try {
            $stats = [
                // Basic counts
                'total_payments' => PaymentProof::count(),
                'pending_payments' => PaymentProof::where('verification_status', 'pending')->count(),
                'approved_payments' => PaymentProof::where('verification_status', 'approved')->count(),
                'rejected_payments' => PaymentProof::where('verification_status', 'rejected')->count(),

                // Today's activity
                'today_payments' => PaymentProof::whereDate('created_at', today())->count(),
                'today_approved' => PaymentProof::whereDate('verified_at', today())
                    ->where('verification_status', 'approved')
                    ->count(),

                // Revenue statistics
                'total_revenue' => PaymentProof::where('verification_status', 'approved')
                    ->sum('amount'),
                'pending_revenue' => PaymentProof::where('verification_status', 'pending')
                    ->sum('amount'),

                // Payment type breakdown
                'administration_payments' => PaymentProof::where('payment_type', 'administration')->count(),
                'registration_payments' => PaymentProof::where('payment_type', 'registration')->count(),

                // Monthly trend (last 6 months)
                'monthly_trend' => $this->getMonthlyTrend(),

                // Path distribution
                'path_distribution' => $this->getPathDistribution()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting payment statistics', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik'
            ], 500);
        }
    }

    /**
     * Download payment file
     */
    public function downloadFile($id)
    {
        try {
            $payment = PaymentProof::findOrFail($id);

            if (!Storage::disk('public')->exists($payment->file_path)) {
                return redirect()->back()->with('error', 'File tidak ditemukan');
            }

            // Log download activity
            Log::info('Payment file downloaded', [
                'payment_id' => $payment->id,
                'file_path' => $payment->file_path,
                'downloaded_by' => auth()->id()
            ]);

            return Storage::disk('public')->download(
                $payment->file_path,
                $payment->file_name
            );

        } catch (\Exception $e) {
            Log::error('Error downloading payment file', [
                'payment_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Gagal mengunduh file');
        }
    }

    /**
     * Export payment data to Excel
     */
    public function export(Request $request)
    {
        try {
            $query = PaymentProof::with([
                'registration.user',
                'registration.wave',
                'registration.path'
            ]);

            // Apply same filters as index method
            if ($request->has('status') && $request->status !== '') {
                $query->where('verification_status', $request->status);
            }

            if ($request->has('payment_type') && $request->payment_type !== '') {
                $query->where('payment_type', $request->payment_type);
            }

            if ($request->has('wave_id') && $request->wave_id !== '') {
                $query->whereHas('registration', function ($q) use ($request) {
                    $q->where('wave_id', $request->wave_id);
                });
            }

            if ($request->has('path_id') && $request->path_id !== '') {
                $query->whereHas('registration', function ($q) use ($request) {
                    $q->where('path_id', $request->path_id);
                });
            }

            $payments = $query->orderBy('created_at', 'desc')->get();

            $filename = 'payment_verification_' . date('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function () use ($payments) {
                $file = fopen('php://output', 'w');

                // CSV Headers
                fputcsv($file, [
                    'No Registrasi',
                    'Nama Mahasiswa',
                    'Email',
                    'Gelombang',
                    'Jalur',
                    'Tipe Pembayaran',
                    'Jumlah',
                    'Status Verifikasi',
                    'Tanggal Upload',
                    'Tanggal Verifikasi',
                    'Verified By',
                    'Notes'
                ]);

                // CSV Data
                foreach ($payments as $payment) {
                    fputcsv($file, [
                        $payment->registration->registration_number,
                        $payment->registration->user->name,
                        $payment->registration->user->email,
                        $payment->registration->wave->name,
                        $payment->registration->path->name,
                        ucfirst($payment->payment_type),
                        number_format($payment->amount, 0, ',', '.'),
                        ucfirst($payment->verification_status),
                        $payment->created_at->format('d/m/Y H:i'),
                        $payment->verified_at ? $payment->verified_at->format('d/m/Y H:i') : '-',
                        $payment->verifiedBy ? $payment->verifiedBy->name : '-',
                        $payment->verification_notes ?: '-'
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error exporting payment data', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Gagal mengekspor data');
        }
    }

    /**
     * Get monthly trend data for charts
     */
    private function getMonthlyTrend()
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'total' => PaymentProof::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'approved' => PaymentProof::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('verification_status', 'approved')
                    ->count(),
                'revenue' => PaymentProof::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('verification_status', 'approved')
                    ->sum('amount')
            ];
        }
        return $months;
    }

    /**
     * Get path distribution data
     */
    private function getPathDistribution()
    {
        return PaymentProof::join('registrations', 'payment_proofs.registration_id', '=', 'registrations.id')
            ->join('registration_paths', 'registrations.path_id', '=', 'registration_paths.id')
            ->groupBy('registration_paths.name')
            ->selectRaw('registration_paths.name as path_name, COUNT(*) as count')
            ->pluck('count', 'path_name')
            ->toArray();
    }
}