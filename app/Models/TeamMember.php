<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'member_type',
        'position',
        'expertise',
        'biography',
        'photo',
        'email',
        'linkedin_url',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeExecutiveDirector($query)
    {
        return $query->where('member_type', 'executive_director');
    }

    public function scopeExperts($query)
    {
        return $query->where('member_type', 'expert');
    }
}
