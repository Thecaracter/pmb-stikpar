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
use Carbon\Carbon;

class PaymentVerificationController extends Controller
{
    /**
     * Display payment verification page - SIMPLE untuk Alpine.js
     */
    public function index()
    {
        try {
            // Load initial data dengan transform langsung - ANTI UNDEFINED!
            $payments = PaymentProof::with([
                'registration.user',
                'registration.wave',
                'registration.path'
            ])->orderBy('created_at', 'desc')->get();

            // Transform data biar gak undefined
            $transformedPayments = $payments->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'registration_number' => $payment->registration->registration_number ?? 'N/A',
                    'user_name' => $payment->registration->user->name ?? 'N/A',
                    'user_email' => $payment->registration->user->email ?? 'N/A',
                    'wave_name' => $payment->registration->wave->name ?? 'N/A',
                    'path_name' => $payment->registration->path->name ?? 'N/A',
                    'wave_id' => $payment->registration->wave_id ?? null,
                    'path_id' => $payment->registration->path_id ?? null,
                    'payment_type' => $payment->payment_type ?? 'administration',
                    'payment_type_label' => $payment->payment_type === 'administration' ? 'Administrasi' : 'Daftar Ulang',
                    'amount' => $payment->amount ?? 0,
                    'formatted_amount' => 'Rp ' . number_format($payment->amount ?? 0, 0, ',', '.'),
                    'verification_status' => $payment->verification_status ?? 'pending',
                    'file_url' => $payment->file_url ?? '#',
                    'file_name' => $payment->file_name ?? 'file.pdf',
                    'created_at' => $payment->created_at->format('d/m/Y'),
                    'created_at_full' => $payment->created_at->format('Y-m-d H:i:s'),
                ];
            });

            // Data untuk dropdowns
            $waves = \App\Models\RegistrationWave::orderBy('wave_number')->get();
            $paths = \App\Models\RegistrationPath::orderBy('name')->get();

            // Simple stats
            $stats = [
                'total' => $transformedPayments->count(),
                'pending' => $transformedPayments->where('verification_status', 'pending')->count(),
                'approved' => $transformedPayments->where('verification_status', 'approved')->count(),
                'rejected' => $transformedPayments->where('verification_status', 'rejected')->count(),
            ];

            return view('pages.admin.payment-verification', compact(
                'transformedPayments',
                'waves',
                'paths',
                'stats'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading payment verification page', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return view('pages.admin.payment-verification', [
                'transformedPayments' => collect(),
                'waves' => collect(),
                'paths' => collect(),
                'stats' => ['total' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0]
            ])->with('error', 'Terjadi kesalahan saat memuat halaman.');
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
                'registration.path'
            ])->findOrFail($id);

            return view('pages.admin.payment-detail', compact('payment'));

        } catch (\Exception $e) {
            Log::error('Error loading payment detail', [
                'payment_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->route('admin.payments.index')
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }
    }

    /**
     * Download payment file
     */
    public function downloadFile($id)
    {
        try {
            $payment = PaymentProof::findOrFail($id);
            $filePath = public_path($payment->file_path);

            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'File tidak ditemukan');
            }

            return response()->download($filePath, $payment->file_name);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunduh file');
        }
    }

    /**
     * API: Get filtered payments - UNTUK ALPINE.JS
     */
    public function apiPayments(Request $request)
    {
        try {
            $query = PaymentProof::with([
                'registration.user',
                'registration.wave',
                'registration.path'
            ]);

            // Apply filters - ANTI NULL ERROR dengan filled()
            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('verification_status', $request->status);
            }

            if ($request->filled('payment_type')) {
                $query->where('payment_type', $request->payment_type);
            }

            if ($request->filled('wave_id')) {
                $query->whereHas('registration', function ($q) use ($request) {
                    $q->where('wave_id', $request->wave_id);
                });
            }

            if ($request->filled('path_id')) {
                $query->whereHas('registration', function ($q) use ($request) {
                    $q->where('path_id', $request->path_id);
                });
            }

            // Date filters dengan Carbon - ANTI CRASH!
            if ($request->filled('date_from')) {
                try {
                    $dateFrom = Carbon::parse($request->date_from);
                    $query->whereDate('created_at', '>=', $dateFrom);
                } catch (\Exception $e) {
                    // Skip invalid date
                }
            }

            if ($request->filled('date_to')) {
                try {
                    $dateTo = Carbon::parse($request->date_to);
                    $query->whereDate('created_at', '<=', $dateTo);
                } catch (\Exception $e) {
                    // Skip invalid date
                }
            }

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('registration.user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                        ->orWhereHas('registration', function ($regQuery) use ($search) {
                            $regQuery->where('registration_number', 'like', "%{$search}%");
                        })
                        ->orWhere('file_name', 'like', "%{$search}%");
                });
            }

            $payments = $query->orderBy('created_at', 'desc')->get();

            // Transform untuk Alpine.js
            $transformedPayments = $payments->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'registration_number' => $payment->registration->registration_number,
                    'user_name' => $payment->registration->user->name,
                    'user_email' => $payment->registration->user->email,
                    'wave_name' => $payment->registration->wave->name,
                    'path_name' => $payment->registration->path->name,
                    'payment_type' => $payment->payment_type,
                    'payment_type_label' => $payment->payment_type === 'administration' ? 'Administrasi' : 'Daftar Ulang',
                    'amount' => $payment->amount,
                    'formatted_amount' => 'Rp ' . number_format($payment->amount, 0, ',', '.'),
                    'verification_status' => $payment->verification_status,
                    'file_url' => $payment->file_url,
                    'file_name' => $payment->file_name,
                    'created_at' => $payment->created_at->format('d/m/Y'),
                    'created_at_full' => $payment->created_at->format('d/m/Y H:i'),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $transformedPayments,
                'total' => $transformedPayments->count()
            ]);

        } catch (\Exception $e) {
            Log::error('API Error filtering payments', [
                'error' => $e->getMessage(),
                'filters' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memfilter data',
                'data' => []
            ], 500);
        }
    }

    /**
     * API: Get stats berdasarkan filter - UNTUK ALPINE.JS
     */
    public function apiStats(Request $request)
    {
        try {
            // Pakai filter yang sama seperti apiPayments
            $query = PaymentProof::query();

            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('verification_status', $request->status);
            }

            if ($request->filled('payment_type')) {
                $query->where('payment_type', $request->payment_type);
            }

            if ($request->filled('wave_id')) {
                $query->whereHas('registration', function ($q) use ($request) {
                    $q->where('wave_id', $request->wave_id);
                });
            }

            if ($request->filled('path_id')) {
                $query->whereHas('registration', function ($q) use ($request) {
                    $q->where('path_id', $request->path_id);
                });
            }

            if ($request->filled('date_from')) {
                try {
                    $dateFrom = Carbon::parse($request->date_from);
                    $query->whereDate('created_at', '>=', $dateFrom);
                } catch (\Exception $e) {
                    // Skip
                }
            }

            if ($request->filled('date_to')) {
                try {
                    $dateTo = Carbon::parse($request->date_to);
                    $query->whereDate('created_at', '<=', $dateTo);
                } catch (\Exception $e) {
                    // Skip
                }
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('registration.user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                        ->orWhereHas('registration', function ($regQuery) use ($search) {
                            $regQuery->where('registration_number', 'like', "%{$search}%");
                        })
                        ->orWhere('file_name', 'like', "%{$search}%");
                });
            }

            $filteredPayments = $query->get();

            $stats = [
                'total' => $filteredPayments->count(),
                'pending' => $filteredPayments->where('verification_status', 'pending')->count(),
                'approved' => $filteredPayments->where('verification_status', 'approved')->count(),
                'rejected' => $filteredPayments->where('verification_status', 'rejected')->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => ['total' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0]
            ]);
        }
    }

    /**
     * API: Approve payment
     */
    public function apiApprove(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $payment = PaymentProof::with('registration')->findOrFail($id);

            if ($payment->verification_status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran sudah diverifikasi'
                ], 400);
            }

            $payment->update(['verification_status' => 'approved']);

            // Update registration status
            $registration = $payment->registration;
            if ($payment->payment_type === 'administration') {
                $registration->update([
                    'status' => 'waiting_documents',
                    'admin_fee_paid' => $payment->amount
                ]);
            } elseif ($payment->payment_type === 'registration') {
                $registration->update(['status' => 'completed']);
            }

            Log::info('Payment approved', [
                'payment_id' => $payment->id,
                'admin_id' => auth()->id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil disetujui!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui pembayaran'
            ], 500);
        }
    }

    /**
     * API: Reject payment
     */
    public function apiReject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Alasan penolakan harus diisi'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $payment = PaymentProof::with('registration')->findOrFail($id);

            if ($payment->verification_status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran sudah diverifikasi'
                ], 400);
            }

            $payment->update(['verification_status' => 'rejected']);

            // Update registration status
            $registration = $payment->registration;
            if ($payment->payment_type === 'administration') {
                $registration->update([
                    'status' => 'pending',
                    'admin_fee_paid' => null
                ]);
            } elseif ($payment->payment_type === 'registration') {
                $registration->update(['status' => 'passed']);
            }

            Log::info('Payment rejected', [
                'payment_id' => $payment->id,
                'admin_id' => auth()->id(),
                'reason' => $request->reason
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran ditolak'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak pembayaran'
            ], 500);
        }
    }

    /**
     * API: Bulk approve payments
     */
    public function apiBulkApprove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_ids' => 'required|array|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih minimal satu pembayaran'
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
                $payment->update(['verification_status' => 'approved']);

                if ($payment->payment_type === 'administration') {
                    $payment->registration->update([
                        'status' => 'waiting_documents',
                        'admin_fee_paid' => $payment->amount
                    ]);
                } elseif ($payment->payment_type === 'registration') {
                    $payment->registration->update(['status' => 'completed']);
                }

                $approved_count++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil menyetujui {$approved_count} pembayaran"
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Gagal bulk approve'
            ], 500);
        }
    }

    /**
     * API: Export filtered data via POST
     */
    public function apiExport(Request $request)
    {
        try {
            // Ambil payment_ids yang sudah difilter dari frontend
            $paymentIds = $request->input('payment_ids', []);

            if (empty($paymentIds)) {
                // Export semua data
                $payments = PaymentProof::with([
                    'registration.user',
                    'registration.wave',
                    'registration.path'
                ])->orderBy('created_at', 'desc')->get();
            } else {
                // Export data yang difilter
                $payments = PaymentProof::with([
                    'registration.user',
                    'registration.wave',
                    'registration.path'
                ])->whereIn('id', $paymentIds)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            $filename = 'payments_' . date('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function () use ($payments) {
                $file = fopen('php://output', 'w');

                // UTF-8 BOM biar Excel bisa baca
                fputs($file, "\xEF\xBB\xBF");

                // Headers
                fputcsv($file, [
                    'No Registrasi',
                    'Nama Mahasiswa',
                    'Email',
                    'Gelombang',
                    'Jalur',
                    'Tipe Pembayaran',
                    'Jumlah',
                    'Status',
                    'Tanggal Upload'
                ]);

                // Data
                foreach ($payments as $payment) {
                    fputcsv($file, [
                        $payment->registration->registration_number,
                        $payment->registration->user->name,
                        $payment->registration->user->email,
                        $payment->registration->wave->name,
                        $payment->registration->path->name,
                        $payment->payment_type === 'administration' ? 'Administrasi' : 'Daftar Ulang',
                        'Rp ' . number_format($payment->amount, 0, ',', '.'),
                        ucfirst($payment->verification_status),
                        $payment->created_at->format('d/m/Y H:i')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error exporting payments', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal export data'
            ], 500);
        }
    }
}