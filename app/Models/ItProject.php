<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'excerpt',
        'description',
        'client',
        'thumbnail',
        'gallery',
        'technologies',
        'project_url',
        'demo_url',
        'started_at',
        'completed_at',
        'status',
        'published_at',
        'seo_title',
        'seo_description',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'technologies' => 'array',
            'started_at' => 'date',
            'completed_at' => 'date',
            'published_at' => 'datetime',
            'views_count' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            ProjectCategory::class,
            'category_id'
        );
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished($query)
    {
        return $query
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }
}
