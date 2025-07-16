<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationWave extends Model
{
    protected $fillable = [
        'name',
        'wave_number',
        'start_date',
        'end_date',
        'administration_fee',
        'registration_fee',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'administration_fee' => 'decimal:2',
        'registration_fee' => 'decimal:2',
        'is_active' => 'boolean'
    ];

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
}
