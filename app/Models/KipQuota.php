<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KipQuota extends Model
{
    protected $fillable = [
        'year',
        'total_quota',
        'remaining_quota',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'year' => 'integer',
        'total_quota' => 'integer',
        'remaining_quota' => 'integer'
    ];

    /**
     * Scope for active quota
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific year
     */
    public function scopeYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Get used quota
     */
    public function getUsedQuotaAttribute()
    {
        return $this->total_quota - $this->remaining_quota;
    }

    /**
     * Check if quota is available
     */
    public function hasAvailableQuota()
    {
        return $this->is_active && $this->remaining_quota > 0;
    }

    /**
     * Use quota
     */
    public function useQuota($amount = 1)
    {
        if ($this->remaining_quota >= $amount) {
            $this->remaining_quota -= $amount;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Release quota
     */
    public function releaseQuota($amount = 1)
    {
        if ($this->remaining_quota + $amount <= $this->total_quota) {
            $this->remaining_quota += $amount;
            $this->save();
            return true;
        }
        return false;
    }
}
