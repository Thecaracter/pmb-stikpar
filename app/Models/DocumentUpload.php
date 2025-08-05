<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentUpload extends Model
{
    protected $fillable = [
        'registration_id',
        'document_type',
        'document_name',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'verification_status',
        'verification_notes',
        'is_required',
        'rejection_reason',
        'verified_by',
        'verified_at',
        'notes'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'verified_at' => 'datetime',
        'is_required' => 'boolean'
    ];

    /**
     * Registration that owns this document
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Admin who verified this document
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope for specific document type
     */
    public function scopeType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    /**
     * Scope for verified documents
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'approved');
    }

    /**
     * Scope for pending verification
     */
    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    /**
     * Scope for rejected documents
     */
    public function scopeRejected($query)
    {
        return $query->where('verification_status', 'rejected');
    }

    /**
     * Scope for required documents
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Scope for optional documents
     */
    public function scopeOptional($query)
    {
        return $query->where('is_required', false);
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
     * Get document display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->document_name ?: $this->getDocumentTypeName();
    }

    /**
     * Get document type name based on document_type - UPDATED with correct names
     */
    public function getDocumentTypeName()
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
            'marriage_certificate' => 'Surat Nikah', // UPDATED: For student's marriage certificate
            'kip_certificate' => 'Kartu KIP',
            'poverty_certificate' => 'Surat Keterangan Tidak Mampu',
            'achievement_certificate' => 'Sertifikat Prestasi',
            'achievement_recommendation' => 'Surat Rekomendasi Prestasi',
            'achievement_award' => 'Piagam Penghargaan',
            'achievement_documentation' => 'Dokumentasi Prestasi',
        ];

        return $documentTypes[$this->document_type] ?? ucfirst(str_replace('_', ' ', $this->document_type));
    }

    /**
     * Check if document is approved
     */
    public function isApproved()
    {
        return $this->verification_status === 'approved';
    }

    /**
     * Check if document is rejected
     */
    public function isRejected()
    {
        return $this->verification_status === 'rejected';
    }

    /**
     * Check if document is pending
     */
    public function isPending()
    {
        return $this->verification_status === 'pending';
    }

    /**
     * Check if document is required
     */
    public function isRequired()
    {
        return $this->is_required;
    }

    /**
     * Get file URL - UPDATED to use public path instead of storage
     */
    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            // Since files are stored in public folder, use asset() directly
            return asset($this->file_path);
        }
        return null;
    }

    /**
     * Get verification badge HTML
     */
    public function getVerificationBadgeAttribute()
    {
        $badges = [
            'approved' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>',
            'rejected' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>',
            'pending' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>',
        ];

        return $badges[$this->verification_status] ?? $badges['pending'];
    }

    /**
     * Get required status badge - NEW method for UI
     */
    public function getRequiredBadgeAttribute()
    {
        if ($this->is_required) {
            return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Wajib</span>';
        } else {
            return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Opsional</span>';
        }
    }

    /**
     * Get document description based on type - NEW method
     */
    public function getDocumentDescription()
    {
        $descriptions = [
            'photo' => 'Pas foto terbaru ukuran 3x4 dengan latar belakang putih',
            'family_card' => 'Fotocopy Kartu Keluarga yang masih berlaku',
            'birth_certificate' => 'Fotocopy akta kelahiran',
            'id_card' => 'Fotocopy KTP atau Kartu Identitas Anak',
            'diploma' => 'Fotocopy ijazah SMA/SMK/MA yang telah dilegalisir',
            'report_card' => 'Fotocopy rapor kelas 11 dan 12 semester 1 yang telah dilegalisir',
            'baptism_certificate' => 'Surat baptis dari gereja (khusus yang beragama Kristen)',
            'pastor_recommendation' => 'Surat rekomendasi dari pastor/pendeta gereja',
            'marriage_certificate' => 'Fotocopy surat nikah (khusus yang sudah menikah)',
            'kip_certificate' => 'Fotocopy Kartu Indonesia Pintar (KIP)',
            'poverty_certificate' => 'SKTM dari kelurahan/desa setempat',
            'achievement_certificate' => 'Fotocopy sertifikat prestasi yang dimiliki',
            'achievement_recommendation' => 'Surat rekomendasi terkait prestasi (opsional)',
        ];

        return $descriptions[$this->document_type] ?? 'Dokumen pendukung pendaftaran';
    }

    /**
     * Check if file exists in public directory - NEW method
     */
    public function fileExists()
    {
        if (!$this->file_path) {
            return false;
        }

        return file_exists(public_path($this->file_path));
    }

    /**
     * Get file extension - NEW method
     */
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    /**
     * Check if file is image - NEW method
     */
    public function isImage()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        return in_array(strtolower($this->file_extension), $imageExtensions);
    }

    /**
     * Check if file is PDF - NEW method
     */
    public function isPdf()
    {
        return strtolower($this->file_extension) === 'pdf';
    }

    /**
     * Get file icon class for UI - NEW method
     */
    public function getFileIconAttribute()
    {
        if ($this->isImage()) {
            return 'fas fa-image text-blue-500';
        } elseif ($this->isPdf()) {
            return 'fas fa-file-pdf text-red-500';
        } else {
            return 'fas fa-file text-gray-500';
        }
    }

    /**
     * Scope for registration path - NEW scope
     */
    public function scopeForPath($query, $pathCode)
    {
        return $query->whereHas('registration', function ($q) use ($pathCode) {
            $q->whereHas('path', function ($q2) use ($pathCode) {
                $q2->where('code', $pathCode);
            });
        });
    }

    /**
     * Get validation errors if any - NEW method for better error handling
     */
    public function getValidationErrors()
    {
        $errors = [];

        // Check file existence
        if (!$this->fileExists()) {
            $errors[] = 'File tidak ditemukan di server';
        }

        // Check file size
        if ($this->file_size > 5242880) { // 5MB
            $errors[] = 'Ukuran file melebihi batas maksimal 5MB';
        }

        // Check mime type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
        if (!in_array($this->mime_type, $allowedTypes)) {
            $errors[] = 'Tipe file tidak diizinkan';
        }

        return $errors;
    }

    /**
     * Mark as verified by admin - NEW method
     */
    public function markAsVerified($adminId, $notes = null)
    {
        $this->update([
            'verification_status' => 'approved',
            'verified_by' => $adminId,
            'verified_at' => now(),
            'verification_notes' => $notes
        ]);
    }

    /**
     * Mark as rejected by admin - NEW method
     */
    public function markAsRejected($adminId, $reason, $notes = null)
    {
        $this->update([
            'verification_status' => 'rejected',
            'verified_by' => $adminId,
            'verified_at' => now(),
            'rejection_reason' => $reason,
            'verification_notes' => $notes
        ]);
    }
}