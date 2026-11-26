<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_name',
        'file_path',
        'disk',
        'mime_type',
        'file_type',
        'file_size',
        'alt_text',
        'description',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        // ==========================================
        // MENCATAT METADATA FILE SECARA OTOMATIS
        // ==========================================

        static::saving(function (Media $media): void {

            if (blank($media->disk)) {
                $media->disk = 'public';
            }

            if (blank($media->file_path)) {
                return;
            }

            $storage = Storage::disk($media->disk);

            if (! $storage->exists($media->file_path)) {
                return;
            }

            // Nama file yang tersimpan
            $media->file_name = basename($media->file_path);

            // MIME Type
            $media->mime_type = $storage->mimeType(
                $media->file_path
            );

            // Ukuran file dalam byte
            $media->file_size = $storage->size(
                $media->file_path
            );

            // Klasifikasi jenis file
            $mimeType = $media->mime_type ?? '';

            $media->file_type = match (true) {

                Str::startsWith($mimeType, 'image/')
                    => 'image',

                Str::startsWith($mimeType, 'video/')
                    => 'video',

                Str::startsWith($mimeType, 'audio/')
                    => 'audio',

                Str::startsWith($mimeType, 'application/')
                    => 'document',

                Str::startsWith($mimeType, 'text/')
                    => 'document',

                default => 'other',
            };

            // Membuat nama media jika belum diisi
            if (blank($media->name)) {
                $media->name = pathinfo(
                    $media->file_name,
                    PATHINFO_FILENAME
                );
            }
        });

        // ==========================================
        // MENCATAT ADMINISTRATOR PENGUNGGAH
        // ==========================================

        static::creating(function (Media $media): void {

            if (blank($media->uploaded_by)) {
                $media->uploaded_by = auth()->id();
            }

        });

        // ==========================================
        // MENGHAPUS FILE SAAT DATA MEDIA DIHAPUS
        // ==========================================

        static::deleting(function (Media $media): void {

            if (
                filled($media->file_path) &&
                filled($media->disk)
            ) {
                Storage::disk($media->disk)
                    ->delete($media->file_path);
            }

        });
    }

    // ==========================================
    // RELASI PENGUNGGAH
    // ==========================================

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }

    // ==========================================
    // SCOPE MEDIA
    // ==========================================

    public function scopeImages($query)
    {
        return $query->where('file_type', 'image');
    }

    public function scopeDocuments($query)
    {
        return $query->where('file_type', 'document');
    }
}
