<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'abstract',
        'content',
        'authors',
        'publisher',
        'publication_date',
        'cover_image',
        'document_path',
        'status',
        'published_at',
        'seo_title',
        'seo_description',
        'views_count',
        'downloads_count',
    ];

    protected function casts(): array
    {
        return [
            'publication_date' => 'date',
            'published_at' => 'datetime',
            'views_count' => 'integer',
            'downloads_count' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            PublicationCategory::class,
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
