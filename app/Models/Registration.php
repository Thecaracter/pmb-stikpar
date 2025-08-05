<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Registration extends Model
{
    protected $fillable = [
        'registration_number',
        'user_id',
        'wave_id',
        'path_id',
        'status',
        'admin_fee_paid',
        'payment_date',
        'document_submitted_at',
        'passed_at',
        'failure_reason'
    ];

    protected $casts = [
        'admin_fee_paid' => 'decimal:2',
        'payment_date' => 'datetime',
        'document_submitted_at' => 'datetime',
        'passed_at' => 'datetime'
    ];

    /**
     * User who registered
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Registration wave
     */
    public function wave(): BelongsTo
    {
        return $this->belongsTo(RegistrationWave::class, 'wave_id');
    }

    /**
     * Registration path
     */
    public function path(): BelongsTo
    {
        return $this->belongsTo(RegistrationPath::class, 'path_id');
    }

    /**
     * Registration form
     */
    public function form(): HasOne
    {
        return $this->hasOne(RegistrationForm::class);
    }

    /**
     * Document uploads (KK, KTP, ijazah, dll)
     */
    public function documents(): HasMany
    {
        return $this->hasMany(DocumentUpload::class);
    }

    /**
     * Document uploads (alias)
     */
    public function documentUploads(): HasMany
    {
        return $this->hasMany(DocumentUpload::class);
    }

    /**
     * Activity logs
     */
    public function logs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Graduation proof
     */
    public function graduationProof(): HasOne
    {
        return $this->hasOne(GraduationProof::class);
    }

    // ===== PAYMENT PROOF RELATIONSHIPS =====

    /**
     * Payment proofs (bukti pembayaran admin & daftar ulang)
     */
    public function paymentProofs(): HasMany
    {
        return $this->hasMany(PaymentProof::class);
    }

    /**
     * Administration payment proof (bukti biaya admin)
     */
    public function adminPayment(): HasOne
    {
        return $this->hasOne(PaymentProof::class)->where('payment_type', 'administration');
    }

    /**
     * Registration payment proof (bukti daftar ulang)
     */
    public function registrationPayment(): HasOne
    {
        return $this->hasOne(PaymentProof::class)->where('payment_type', 'registration');
    }

    /**
     * Get payment proof document (DEPRECATED - gunakan adminPayment())
     */
    public function paymentProof(): HasOne
    {
        return $this->hasOne(DocumentUpload::class)->where('document_type', 'payment_proof');
    }

    // ===== SCOPES =====

    /**
     * Scope for specific status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for pending registrations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for waiting payment verification
     */
    public function scopeWaitingPayment($query)
    {
        return $query->where('status', 'waiting_payment');
    }

    /**
     * Scope for waiting documents
     */
    public function scopeWaitingDocuments($query)
    {
        return $query->where('status', 'waiting_documents');
    }

    /**
     * Scope for waiting decision
     */
    public function scopeWaitingDecision($query)
    {
        return $query->where('status', 'waiting_decision');
    }

    /**
     * Scope for passed registrations
     */
    public function scopePassed($query)
    {
        return $query->where('status', 'passed');
    }

    /**
     * Scope for failed registrations
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for waiting final payment
     */
    public function scopeWaitingFinalPayment($query)
    {
        return $query->where('status', 'waiting_final_payment');
    }

    /**
     * Scope for completed registrations
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for rejected registrations
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // ===== STATUS LABELS (UPDATED) =====

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'Menunggu Upload Bukti Pembayaran Administrasi';
            case 'waiting_payment':
                return 'Menunggu Verifikasi Pembayaran Administrasi';
            case 'waiting_documents':
                return 'Pembayaran Disetujui - Silakan Isi Formulir & Upload Dokumen';
            case 'waiting_decision':
                return 'Dokumen Sedang Direview - Menunggu Keputusan Seleksi';
            case 'passed':
                return 'Lulus Seleksi - Upload Bukti Daftar Ulang';
            case 'failed':
                return 'Tidak Lulus Seleksi';
            case 'waiting_final_payment':
                return 'Menunggu Verifikasi Pembayaran Daftar Ulang';
            case 'completed':
                return 'Pendaftaran Selesai - Diterima';
            case 'rejected':
                return 'Pendaftaran Ditolak';
            default:
                return 'Status Tidak Diketahui';
        }
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'yellow';
            case 'waiting_payment':
                return 'blue';
            case 'waiting_documents':
                return 'green';
            case 'waiting_decision':
                return 'indigo';
            case 'passed':
                return 'purple';
            case 'failed':
                return 'red';
            case 'waiting_final_payment':
                return 'orange';
            case 'completed':
                return 'emerald';
            case 'rejected':
                return 'gray';
            default:
                return 'gray';
        }
    }

    /**
     * Get status description for user
     */
    public function getStatusDescriptionAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'Upload bukti pembayaran biaya administrasi untuk melanjutkan pendaftaran.';
            case 'waiting_payment':
                return 'Bukti pembayaran Anda sedang diverifikasi oleh admin. Harap menunggu konfirmasi.';
            case 'waiting_documents':
                return 'Pembayaran telah diverifikasi. Silakan lengkapi formulir dan upload semua dokumen persyaratan.';
            case 'waiting_decision':
                return 'Formulir dan dokumen Anda sedang direview oleh tim seleksi. Hasil seleksi akan diumumkan sesuai jadwal.';
            case 'passed':
                return 'Selamat! Anda dinyatakan LULUS seleksi. Silakan upload bukti pembayaran daftar ulang.';
            case 'failed':
                return 'Mohon maaf, Anda belum berhasil dalam seleksi kali ini. Terima kasih atas partisipasinya.';
            case 'waiting_final_payment':
                return 'Bukti pembayaran daftar ulang Anda sedang diverifikasi oleh admin. Harap menunggu konfirmasi.';
            case 'completed':
                return 'Selamat! Pendaftaran Anda telah selesai dan Anda resmi diterima. Selamat bergabung!';
            case 'rejected':
                return 'Pendaftaran Anda telah ditolak. Silakan hubungi admin untuk informasi lebih lanjut.';
            default:
                return 'Status pendaftaran tidak dikenali. Hubungi admin untuk bantuan.';
        }
    }

    /**
     * Get formatted admin fee
     */
    public function getFormattedAdminFeeAttribute()
    {
        return 'Rp ' . number_format((float) $this->admin_fee_paid, 0, ',', '.');
    }

    // ===== HELPER METHODS (UPDATED) =====

    /**
     * Check if registration can upload admin payment proof
     */
    public function canUploadAdminPayment()
    {
        return $this->status === 'pending' && !$this->adminPayment;
    }

    /**
     * Check if registration can upload registration payment proof
     */
    public function canUploadRegistrationPayment()
    {
        return $this->status === 'passed' && !$this->registrationPayment;
    }

    /**
     * Check if registration can upload payment proof (DEPRECATED)
     */
    public function canUploadPayment()
    {
        return $this->canUploadAdminPayment();
    }

    /**
     * Check if registration can fill form
     */
    public function canFillForm()
    {
        return $this->status === 'waiting_documents';
    }

    /**
     * Check if registration can edit form/documents
     */
    public function canEditForm()
    {
        return in_array($this->status, ['waiting_documents']);
    }

    /**
     * Check if registration can upload documents
     */
    public function canUploadDocuments()
    {
        return in_array($this->status, ['waiting_documents']);
    }

    /**
     * Check if registration is in review phase
     */
    public function isInReview()
    {
        return $this->status === 'waiting_decision';
    }

    /**
     * Check if registration is completed (final status)
     */
    public function isCompleted()
    {
        return in_array($this->status, ['passed', 'failed', 'completed', 'rejected']);
    }

    /**
     * Check if registration is active (in progress)
     */
    public function isActive()
    {
        return in_array($this->status, [
            'pending',
            'waiting_payment',
            'waiting_documents',
            'waiting_decision',
            'waiting_final_payment'
        ]);
    }

    /**
     * Check if registration is successful
     */
    public function isPassed()
    {
        return $this->status === 'passed';
    }

    /**
     * Check if registration failed
     */
    public function isFailed()
    {
        return in_array($this->status, ['failed', 'rejected']);
    }

    /**
     * Check if registration is fully completed
     */
    public function isFullyCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if registration is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if registration is waiting final payment verification
     */
    public function isWaitingFinalPayment()
    {
        return $this->status === 'waiting_final_payment';
    }

    /**
     * Check if admin payment is verified
     */
    public function isAdminPaymentVerified()
    {
        return $this->adminPayment && $this->adminPayment->isApproved();
    }

    /**
     * Check if registration payment is verified
     */
    public function isRegistrationPaymentVerified()
    {
        return $this->registrationPayment && $this->registrationPayment->isApproved();
    }

    /**
     * Check if payment is verified (DEPRECATED)
     */
    public function isPaymentVerified()
    {
        return $this->isAdminPaymentVerified();
    }

    /**
     * Check if registration has admin payment proof
     */
    public function hasAdminPaymentProof()
    {
        return $this->adminPayment()->exists();
    }

    /**
     * Check if registration has registration payment proof
     */
    public function hasRegistrationPaymentProof()
    {
        return $this->registrationPayment()->exists();
    }

    /**
     * Check if registration has payment proof (DEPRECATED)
     */
    public function hasPaymentProof()
    {
        return $this->hasAdminPaymentProof();
    }

    /**
     * Generate unique registration number
     */
    public static function generateRegistrationNumber($waveId, $pathCode)
    {
        $year = date('Y');
        $wave = str_pad($waveId, 2, '0', STR_PAD_LEFT);
        $count = self::where('wave_id', $waveId)->count() + 1;
        $sequence = str_pad($count, 4, '0', STR_PAD_LEFT);

        return $year . $pathCode . $wave . $sequence;
    }

    /**
     * Get registration timeline (UPDATED with new statuses)
     */
    public function getTimelineAttribute()
    {
        $timeline = [];

        // 1. Registration created
        $timeline[] = [
            'status' => 'created',
            'label' => 'Pendaftaran Dibuat',
            'date' => $this->created_at,
            'completed' => true
        ];

        // 2. Admin payment uploaded
        if ($this->adminPayment) {
            $timeline[] = [
                'status' => 'admin_payment_uploaded',
                'label' => 'Bukti Pembayaran Administrasi Diupload',
                'date' => $this->adminPayment->created_at,
                'completed' => true
            ];
        }

        // 3. Admin payment verified
        if ($this->isAdminPaymentVerified()) {
            $timeline[] = [
                'status' => 'admin_payment_verified',
                'label' => 'Pembayaran Administrasi Diverifikasi',
                'date' => $this->adminPayment->verified_at ?? $this->adminPayment->updated_at,
                'completed' => true
            ];
        }

        // 4. Documents submitted
        if ($this->document_submitted_at) {
            $timeline[] = [
                'status' => 'documents_submitted',
                'label' => 'Formulir & Dokumen Disubmit',
                'date' => $this->document_submitted_at,
                'completed' => true
            ];
        }

        // 5. Under review (waiting_decision)
        if (in_array($this->status, ['waiting_decision', 'passed', 'failed', 'waiting_final_payment', 'completed', 'rejected'])) {
            $timeline[] = [
                'status' => 'under_review',
                'label' => 'Berkas Sedang Direview',
                'date' => $this->document_submitted_at ?? $this->updated_at,
                'completed' => true
            ];
        }

        // 6. Selection result
        if ($this->passed_at) {
            $resultLabel = match ($this->status) {
                'passed', 'waiting_final_payment', 'completed' => 'Lulus Seleksi',
                'failed' => 'Tidak Lulus Seleksi',
                'rejected' => 'Pendaftaran Ditolak',
                default => $this->status === 'passed' ? 'Lulus Seleksi' : 'Tidak Lulus Seleksi'
            };

            $timeline[] = [
                'status' => 'selection_result',
                'label' => $resultLabel,
                'date' => $this->passed_at,
                'completed' => true
            ];
        }

        // 7. Registration payment uploaded (jika lulus)
        if (in_array($this->status, ['passed', 'waiting_final_payment', 'completed']) && $this->registrationPayment) {
            $timeline[] = [
                'status' => 'registration_payment_uploaded',
                'label' => 'Bukti Daftar Ulang Diupload',
                'date' => $this->registrationPayment->created_at,
                'completed' => true
            ];
        }

        // 8. Registration payment verified / Final status
        if ($this->status === 'waiting_final_payment' && $this->registrationPayment) {
            $timeline[] = [
                'status' => 'waiting_final_verification',
                'label' => 'Menunggu Verifikasi Daftar Ulang',
                'date' => $this->registrationPayment->created_at,
                'completed' => false
            ];
        }

        // 9. Final completion
        if ($this->status === 'completed') {
            $timeline[] = [
                'status' => 'registration_completed',
                'label' => 'Pendaftaran Selesai - Diterima',
                'date' => $this->registrationPayment->verified_at ?? $this->updated_at,
                'completed' => true
            ];
        }

        // Handle rejected status
        if ($this->status === 'rejected') {
            $timeline[] = [
                'status' => 'registration_rejected',
                'label' => 'Pendaftaran Ditolak',
                'date' => $this->updated_at,
                'completed' => true
            ];
        }

        return collect($timeline);
    }
}