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
     * Get document type name based on document_type
     */
    public function getDocumentTypeName()
    {
        $documentTypes = [
            'payment_proof' => 'Bukti Pembayaran',
            'photo' => 'Foto',
            'diploma' => 'Ijazah/Surat Keterangan Lulus',
            'birth_certificate' => 'Akta Kelahiran',
            'id_card' => 'KTP',
            'family_card' => 'Kartu Keluarga',
            'baptism_certificate' => 'Surat Baptis',
            'pastor_recommendation' => 'Surat Rekomendasi Pastor',
            'marriage_certificate' => 'Surat Nikah',
            'kip_certificate' => 'Kartu KIP',
            'report_card' => 'Raport',
            'achievement_certificate' => 'Sertifikat Prestasi',
            'achievement_recommendation' => 'Surat Rekomendasi Prestasi',
            'achievement_award' => 'Piagam Penghargaan',
            'achievement_documentation' => 'Dokumentasi Prestasi',
            'poverty_certificate' => 'Surat Keterangan Tidak Mampu',
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
     * Get file URL
     */
    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
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
}