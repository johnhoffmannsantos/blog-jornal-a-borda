<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'logo',
        'website_url',
        'level',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function getLevelLabelAttribute()
    {
        return match($this->level) {
            'gold' => 'Ouro',
            'silver' => 'Prata',
            'bronze' => 'Bronze',
            default => 'Bronze',
        };
    }

    public function getLevelBadgeClassAttribute()
    {
        return match($this->level) {
            'gold' => 'bg-warning text-dark',
            'silver' => 'bg-secondary',
            'bronze' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
