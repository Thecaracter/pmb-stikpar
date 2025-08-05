<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegistrationPath extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get registrations for this path
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'path_id');
    }

    /**
     * Scope for active paths
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered by name
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }
}
