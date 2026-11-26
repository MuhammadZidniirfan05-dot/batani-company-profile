<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrganizationUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'position',
        'leader_name',
        'description',
        'photo',
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

    /**
     * Relasi ke unit organisasi induk.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            OrganizationUnit::class,
            'parent_id'
        );
    }

    /**
     * Relasi ke unit organisasi turunan.
     */
    public function children(): HasMany
    {
        return $this->hasMany(
            OrganizationUnit::class,
            'parent_id'
        )->orderBy('sort_order');
    }

    /**
     * Mengambil unit yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Mengambil unit utama yang tidak memiliki induk.
     */
    public function scopeRootUnits($query)
    {
        return $query->whereNull('parent_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
