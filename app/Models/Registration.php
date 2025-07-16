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
     * Document uploads
     */
    public function documents(): HasMany
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

    /**
     * Scope for specific status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
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
}
