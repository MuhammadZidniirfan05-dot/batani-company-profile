<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'issuer',
        'image',
        'achieved_at',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'achieved_at' => 'date',
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
}
