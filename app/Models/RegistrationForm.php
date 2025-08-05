<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationForm extends Model
{
    protected $fillable = [
        'registration_id',
        // Data asli yang sudah ada
        'full_name',
        'email',
        'phone_number',
        'birth_date',
        'birth_place',
        'gender',
        'address', // FIELD YANG DIPERLUKAN
        'school_origin',
        'nisn',
        'parent_name',
        'parent_phone',
        'parent_job',
        'parent_income',
        'achievement_type',
        'achievement_level',
        'achievement_rank',
        'achievement_organizer',
        'achievement_date',

        // Field baru yang ditambahkan
        'religion',
        'parish_name',
        'mother_name',
        'mother_job',
        'grade_8_sem2',
        'grade_9_sem1',
        'is_completed',
        'completed_at'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'achievement_date' => 'date',
        'parent_income' => 'decimal:2',
        'achievement_rank' => 'integer',
        'grade_8_sem2' => 'decimal:2',
        'grade_9_sem1' => 'decimal:2',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime'
    ];

    /**
     * Registration that owns this form
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Get NIS (using nisn field for backward compatibility)
     */
    public function getNisAttribute()
    {
        return $this->nisn;
    }

    /**
     * Set NIS (using nisn field for backward compatibility)
     */
    public function setNisAttribute($value)
    {
        $this->attributes['nisn'] = $value;
    }

    /**
     * Get father name (using parent_name field for backward compatibility)
     */
    public function getFatherNameAttribute()
    {
        return $this->parent_name;
    }

    /**
     * Set father name (using parent_name field for backward compatibility)
     */
    public function setFatherNameAttribute($value)
    {
        $this->attributes['parent_name'] = $value;
    }

    /**
     * Get formatted gender
     */
    public function getFormattedGenderAttribute()
    {
        return $this->gender === 'male' ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Get formatted birth date
     */
    public function getFormattedBirthDateAttribute()
    {
        return $this->birth_date ? $this->birth_date->format('d F Y') : '-';
    }

    /**
     * Get full birth info (place, date)
     */
    public function getFullBirthInfoAttribute()
    {
        if ($this->birth_place && $this->birth_date) {
            return $this->birth_place . ', ' . $this->birth_date->format('d F Y');
        }
        return '-';
    }

    /**
     * Check if form is for achievement path
     */
    public function isAchievementPath()
    {
        return $this->registration &&
            (strtoupper($this->registration->path->code) === 'PRE' ||
                strtoupper($this->registration->path->code) === 'PRESTASI');
    }

    /**
     * Check if form is for KIP path
     */
    public function isKipPath()
    {
        return $this->registration &&
            strtoupper($this->registration->path->code) === 'KIP';
    }

    /**
     * Get completion percentage
     */
    public function getCompletionPercentageAttribute()
    {
        $requiredFields = [
            'full_name',
            'email',
            'phone_number',
            'birth_date',
            'birth_place',
            'gender',
            'address', // FIELD YANG DIPERLUKAN
            'school_origin',
            'major_choice',
            'religion',
            'parish_name',
            'parent_name',
            'parent_phone',
            'parent_job',
            'mother_name',
            'mother_job'
        ];

        // Add achievement fields if it's achievement path
        if ($this->isAchievementPath()) {
            $requiredFields = array_merge($requiredFields, ['grade_8_sem2', 'grade_9_sem1']);
        }

        $filledFields = 0;
        foreach ($requiredFields as $field) {
            if (!empty($this->$field)) {
                $filledFields++;
            }
        }

        return round(($filledFields / count($requiredFields)) * 100);
    }

    /**
     * Check if all required fields are filled
     */
    public function isComplete()
    {
        $requiredFields = [
            'full_name',
            'email',
            'phone_number',
            'birth_date',
            'birth_place',
            'gender',
            'address', // FIELD YANG DIPERLUKAN
            'school_origin',
            'major_choice',
            'religion',
            'parish_name',
            'parent_name',
            'parent_phone',
            'parent_job',
            'mother_name',
            'mother_job'
        ];

        // Add achievement fields if it's achievement path
        if ($this->isAchievementPath()) {
            $requiredFields = array_merge($requiredFields, ['grade_8_sem2', 'grade_9_sem1']);
        }

        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Scope for completed forms
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope for incomplete forms
     */
    public function scopeIncomplete($query)
    {
        return $query->where('is_completed', false);
    }

    /**
     * Mark form as completed
     */
    public function markAsCompleted()
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now()
        ]);
    }

    /**
     * Mark form as incomplete
     */
    public function markAsIncomplete()
    {
        $this->update([
            'is_completed' => false,
            'completed_at' => null
        ]);
    }

    /**
     * Get missing required fields
     */
    public function getMissingRequiredFields()
    {
        $requiredFields = [
            'full_name' => 'Nama Lengkap',
            'email' => 'Email',
            'phone_number' => 'Nomor Telepon',
            'birth_date' => 'Tanggal Lahir',
            'birth_place' => 'Tempat Lahir',
            'gender' => 'Jenis Kelamin',
            'address' => 'Alamat', // FIELD YANG DIPERLUKAN
            'school_origin' => 'Asal Sekolah',
            'major_choice' => 'Pilihan Jurusan',
            'religion' => 'Agama',
            'parish_name' => 'Nama Paroki',
            'parent_name' => 'Nama Ayah',
            'parent_phone' => 'Nomor Telepon Ayah',
            'parent_job' => 'Pekerjaan Ayah',
            'mother_name' => 'Nama Ibu',
            'mother_job' => 'Pekerjaan Ibu'
        ];

        // Add achievement fields if it's achievement path
        if ($this->isAchievementPath()) {
            $requiredFields['grade_8_sem2'] = 'Nilai Rapor Kelas 8 Semester 2';
            $requiredFields['grade_9_sem1'] = 'Nilai Rapor Kelas 9 Semester 1';
        }

        $missingFields = [];
        foreach ($requiredFields as $field => $label) {
            if (empty($this->$field)) {
                $missingFields[] = $label;
            }
        }

        return $missingFields;
    }

    /**
     * Auto-complete form if all required fields are filled
     */
    public function autoComplete()
    {
        if ($this->isComplete() && !$this->is_completed) {
            $this->markAsCompleted();
        } elseif (!$this->isComplete() && $this->is_completed) {
            $this->markAsIncomplete();
        }
    }

    /**
     * Boot method to auto-complete form on save
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($form) {
            $form->autoComplete();
        });
    }
}