<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'published_at',
        'seo_title',
        'seo_description',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'views_count' => 'integer',
        ];
    }

    /**
     * Relasi kategori berita.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(
            NewsCategory::class,
            'category_id'
        );
    }

    /**
     * Relasi penulis berita.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /**
     * Menggunakan slug sebagai route key.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Mengambil berita yang sudah dapat ditampilkan
     * pada website publik.
     */
    public function scopePublished(
        Builder $query
    ): Builder {
        return $query
            ->where('status', 'published')
            ->where(function (Builder $query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Mengambil berita berstatus draft.
     */
    public function scopeDraft(
        Builder $query
    ): Builder {
        return $query->where('status', 'draft');
    }

    /**
     * Mengambil berita dengan jadwal publikasi
     * yang belum tercapai.
     */
    public function scopeScheduled(
        Builder $query
    ): Builder {
        return $query
            ->where('status', 'published')
            ->where('published_at', '>', now());
    }
}
