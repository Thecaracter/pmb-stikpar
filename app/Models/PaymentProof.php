<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentProof extends Model
{
    protected $fillable = [
        'registration_id',
        'payment_type',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'amount',
        'verification_status'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'amount' => 'decimal:2'
    ];

    /**
     * Registration that owns this payment proof
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Admin who verified this payment (if verified_by column exists)
     */
    public function verifiedBy(): BelongsTo
    {
        // This relationship only works if verified_by column exists
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope for specific payment type
     */
    public function scopeType($query, $type)
    {
        return $query->where('payment_type', $type);
    }

    /**
     * Scope for pending verification
     */
    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    /**
     * Scope for approved payments
     */
    public function scopeApproved($query)
    {
        return $query->where('verification_status', 'approved');
    }

    /**
     * Scope for rejected payments
     */
    public function scopeRejected($query)
    {
        return $query->where('verification_status', 'rejected');
    }

    /**
     * Scope for administration payments
     */
    public function scopeAdministration($query)
    {
        return $query->where('payment_type', 'administration');
    }

    /**
     * Scope for registration payments
     */
    public function scopeRegistrationFee($query)
    {
        return $query->where('payment_type', 'registration');
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format((float) $this->amount, 0, ',', '.');
    }

    /**
     * Get status label with color
     */
    public function getStatusLabelAttribute()
    {
        switch ($this->verification_status) {
            case 'approved':
                return 'Disetujui';
            case 'rejected':
                return 'Ditolak';
            case 'pending':
            default:
                return 'Menunggu Verifikasi';
        }
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute()
    {
        switch ($this->verification_status) {
            case 'approved':
                return 'green';
            case 'rejected':
                return 'red';
            case 'pending':
            default:
                return 'yellow';
        }
    }

    /**
     * Get payment type label
     */
    public function getPaymentTypeLabelAttribute()
    {
        switch ($this->payment_type) {
            case 'administration':
                return 'Biaya Administrasi';
            case 'registration':
                return 'Biaya Daftar Ulang';
            default:
                return ucfirst($this->payment_type);
        }
    }

    /**
     * Check if payment is approved
     */
    public function isApproved()
    {
        return $this->verification_status === 'approved';
    }

    /**
     * Check if payment is rejected
     */
    public function isRejected()
    {
        return $this->verification_status === 'rejected';
    }

    /**
     * Check if payment is pending
     */
    public function isPending()
    {
        return $this->verification_status === 'pending';
    }

    /**
     * Get file URL
     */
    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            // Direct access to public folder
            return asset($this->file_path);
        }
        return null;
    }

    /**
     * Get verification badge HTML
     */
    public function getVerificationBadgeAttribute()
    {
        $badgeClasses = [
            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'approved' => 'bg-green-100 text-green-800 border-green-200',
            'rejected' => 'bg-red-100 text-red-800 border-red-200'
        ];

        $class = $badgeClasses[$this->verification_status] ?? $badgeClasses['pending'];

        return "<span class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {$class}'>{$this->status_label}</span>";
    }

    /**
     * Check if file exists in storage
     */
    public function fileExists()
    {
        return $this->file_path && file_exists(public_path($this->file_path));
    }

    /**
     * Get file extension
     */
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    /**
     * Check if file is image
     */
    public function isImage()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        return in_array(strtolower($this->file_extension), $imageExtensions);
    }

    /**
     * Check if file is PDF
     */
    public function isPdf()
    {
        return strtolower($this->file_extension) === 'pdf';
    }

    /**
     * Get verification timeline
     */
    public function getVerificationTimelineAttribute()
    {
        $timeline = [];

        // Upload
        $timeline[] = [
            'status' => 'uploaded',
            'label' => 'File Diupload',
            'date' => $this->created_at,
            'completed' => true,
            'description' => "File {$this->file_name} berhasil diupload"
        ];

        // Verification
        if ($this->verification_status !== 'pending') {
            $timeline[] = [
                'status' => 'verified',
                'label' => $this->verification_status === 'approved' ? 'Disetujui' : 'Ditolak',
                'date' => $this->updated_at,
                'completed' => true,
                'description' => $this->verification_status === 'approved' ? 'Pembayaran disetujui' : 'Pembayaran ditolak'
            ];
        } else {
            $timeline[] = [
                'status' => 'pending',
                'label' => 'Menunggu Verifikasi',
                'date' => null,
                'completed' => false,
                'description' => 'Sedang menunggu verifikasi admin'
            ];
        }

        return collect($timeline);
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for filtering by amount range
     */
    public function scopeAmountRange($query, $minAmount, $maxAmount)
    {
        return $query->whereBetween('amount', [$minAmount, $maxAmount]);
    }

    /**
     * Get daily statistics
     */
    public static function getDailyStats($date = null)
    {
        $date = $date ?: today();

        return [
            'total' => static::whereDate('created_at', $date)->count(),
            'pending' => static::whereDate('created_at', $date)->pending()->count(),
            'approved' => static::whereDate('updated_at', $date)->approved()->count(),
            'rejected' => static::whereDate('updated_at', $date)->rejected()->count(),
            'revenue' => static::whereDate('updated_at', $date)->approved()->sum('amount')
        ];
    }

    /**
     * Get monthly statistics
     */
    public static function getMonthlyStats($year = null, $month = null)
    {
        $year = $year ?: now()->year;
        $month = $month ?: now()->month;

        return [
            'total' => static::whereYear('created_at', $year)->whereMonth('created_at', $month)->count(),
            'pending' => static::whereYear('created_at', $year)->whereMonth('created_at', $month)->pending()->count(),
            'approved' => static::whereYear('updated_at', $year)->whereMonth('updated_at', $month)->approved()->count(),
            'rejected' => static::whereYear('updated_at', $year)->whereMonth('updated_at', $month)->rejected()->count(),
            'revenue' => static::whereYear('updated_at', $year)->whereMonth('updated_at', $month)->approved()->sum('amount')
        ];
    }
}