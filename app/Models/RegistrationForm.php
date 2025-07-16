<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationForm extends Model
{
    protected $fillable = [
        'registration_id',
        'full_name',
        'email',
        'phone_number',
        'birth_date',
        'birth_place',
        'gender',
        'address',
        'school_origin',
        'major_choice',
        'nisn',
        'parent_name',
        'parent_phone',
        'parent_job',
        'parent_income',
        'achievement_type',
        'achievement_level',
        'achievement_rank',
        'achievement_organizer',
        'achievement_date'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'achievement_date' => 'date',
        'parent_income' => 'decimal:2',
        'achievement_rank' => 'integer'
    ];

    /**
     * Registration that owns this form
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }
}
