<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegistrationWave extends Model
{
    protected $fillable = [
        'name',
        'wave_number',
        'start_date',
        'end_date',
        'administration_fee',
        'registration_fee',
        'is_active',
        'available_paths'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'administration_fee' => 'decimal:2',
        'registration_fee' => 'decimal:2',
        'is_active' => 'boolean',
        'available_paths' => 'json'
    ];

    /**
     * Get formatted start date for HTML date input
     */
    public function getStartDateFormatAttribute()
    {
        return $this->start_date ? $this->start_date->format('Y-m-d') : '';
    }

    /**
     * Get formatted end date for HTML date input
     */
    public function getEndDateFormatAttribute()
    {
        return $this->end_date ? $this->end_date->format('Y-m-d') : '';
    }

    /**
     * Override toArray to include formatted dates
     */
    public function toArray()
    {
        $array = parent::toArray();

        // Format dates for HTML date inputs
        if ($this->start_date) {
            $array['start_date'] = $this->start_date->format('Y-m-d');
        }
        if ($this->end_date) {
            $array['end_date'] = $this->end_date->format('Y-m-d');
        }

        return $array;
    }

    /**
     * Get active waves
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get current active wave
     */
    public function scopeCurrent($query)
    {
        $today = now()->format('Y-m-d');
        return $query->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->where('is_active', true);
    }

    /**
     * Get waves ordered by wave number
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('wave_number');
    }

    /**
     * Check if wave is currently active
     */
    public function isCurrentlyActive()
    {
        $today = now()->format('Y-m-d');
        return $this->is_active &&
            $this->start_date <= $today &&
            $this->end_date >= $today;
    }

    /**
     * Get formatted administration fee
     */
    public function getFormattedAdministrationFeeAttribute()
    {
        return 'Rp ' . number_format((float) $this->administration_fee, 0, ',', '.');
    }

    /**
     * Get formatted registration fee
     */
    public function getFormattedRegistrationFeeAttribute()
    {
        return 'Rp ' . number_format((float) $this->registration_fee, 0, ',', '.');
    }

    /**
     * Get formatted registration fee (legacy)
     */
    public function getFormattedFeeAttribute()
    {
        return 'Rp ' . number_format((float) $this->registration_fee, 0, ',', '.');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        if (!$this->is_active) {
            return 'Nonaktif';
        }

        $today = now()->format('Y-m-d');

        if ($this->start_date > $today) {
            return 'Belum Dimulai';
        }

        if ($this->end_date < $today) {
            return 'Berakhir';
        }

        return 'Aktif';
    }

    /**
     * Get status color
     */
    public function getStatusColorAttribute()
    {
        if (!$this->is_active) {
            return 'gray';
        }

        $today = now()->format('Y-m-d');

        if ($this->start_date > $today) {
            return 'yellow';
        }

        if ($this->end_date < $today) {
            return 'red';
        }

        return 'green';
    }

    /**
     * Check if a registration path is available
     */
    public function isPathAvailable($path)
    {
        if (!$this->available_paths) {
            return true; // Default: all paths available
        }

        return $this->available_paths[$path] ?? false;
    }

    /**
     * Get available paths as array
     */
    public function getAvailablePathsArray()
    {
        if (!$this->available_paths) {
            return [
                'regular' => true,
                'prestasi' => true,
                'kip' => true,
            ];
        }

        return $this->available_paths;
    }

    /**
     * Get available paths labels
     */
    public function getAvailablePathsLabels()
    {
        $paths = $this->getAvailablePathsArray();
        $labels = [];

        if ($paths['regular']) {
            $labels[] = 'Reguler';
        }
        if ($paths['prestasi']) {
            $labels[] = 'Prestasi';
        }
        if ($paths['kip']) {
            $labels[] = 'KIP';
        }

        return $labels;
    }

    /**
     * Get registrations for this wave
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'wave_id');
    }
}
