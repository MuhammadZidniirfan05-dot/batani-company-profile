<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'technologies' => 'array',
            'started_at' => 'date',
            'completed_at' => 'date',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Relasi dengan kategori proyek.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(
            ProjectCategory::class,
            'category_id'
        );
    }

    /**
     * Relasi dengan administrator pengelola.
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
     * Mengambil proyek yang sudah dapat ditampilkan.
     */
    public function scopePublished(
        Builder $query
    ): Builder {
        return $query
            ->where('status', 'published')
            ->where(function (Builder $query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Mengambil proyek berstatus draft.
     */
    public function scopeDraft(
        Builder $query
    ): Builder {
        return $query->where('status', 'draft');
    }

    /**
     * Mengambil proyek yang dijadwalkan tayang.
     */
    public function scopeScheduled(
        Builder $query
    ): Builder {
        return $query
            ->where('status', 'published')
            ->where('published_at', '>', now());
    }
}
