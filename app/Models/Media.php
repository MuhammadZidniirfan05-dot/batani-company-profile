<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class Media extends Model
{
    use HasFactory;

    public const MAX_FILE_SIZE = 20971520;

    public const ALLOWED_TYPES = [
        'image/jpeg' => ['jpg', 'jpeg'],
        'image/png' => ['png'],
        'image/webp' => ['webp'],
        'image/gif' => ['gif'],
        'application/pdf' => ['pdf'],
    ];

    protected $table = 'media';

    protected $fillable = [
        'name',
        'file_path',
        'alt_text',
        'description',
    ];

    protected $attributes = [
        'disk' => 'public',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Media $media): void {

            $media->disk = 'public';

            $media->uploaded_by = auth()->id();

        });

        static::saving(function (Media $media): void {

            // File media yang sudah terdaftar tidak
            // boleh diganti melalui edit metadata.

            if ($media->exists) {

                if (
                    $media->isDirty('file_path')
                    || $media->isDirty('disk')
                ) {
                    throw ValidationException::withMessages([
                        'data.file_path' =>
                            'File yang sudah terdaftar tidak dapat diganti. Unggah sebagai media baru.',
                    ]);
                }

                return;
            }

            $media->disk = 'public';

            $path = $media->file_path;

            // Validasi lokasi penyimpanan.

            if (
                ! is_string($path)
                || ! preg_match(
                    '/\Amedia\/[a-zA-Z0-9_-]+\.[a-zA-Z0-9]+\z/',
                    $path
                )
            ) {
                throw ValidationException::withMessages([
                    'data.file_path' =>
                        'Lokasi file tidak valid.',
                ]);
            }

            $storage = Storage::disk('public');

            // Memastikan file tersedia.

            if (! $storage->exists($path)) {
                throw ValidationException::withMessages([
                    'data.file_path' =>
                        'File tidak ditemukan pada penyimpanan.',
                ]);
            }

            // Mengambil metadata asli file.

            $mimeType = $storage->mimeType($path);

            $extension = strtolower(
                pathinfo($path, PATHINFO_EXTENSION)
            );

            $fileSize = $storage->size($path);

            // Validasi MIME type dan ekstensi.

            if (
                ! is_string($mimeType)
                || ! isset(self::ALLOWED_TYPES[$mimeType])
                || ! in_array(
                    $extension,
                    self::ALLOWED_TYPES[$mimeType],
                    true
                )
            ) {
                throw ValidationException::withMessages([
                    'data.file_path' =>
                        'Format file tidak diizinkan.',
                ]);
            }

            // Validasi ukuran file.

            if (
                $fileSize <= 0
                || $fileSize > self::MAX_FILE_SIZE
            ) {
                throw ValidationException::withMessages([
                    'data.file_path' =>
                        'Ukuran file tidak valid atau melebihi 20 MB.',
                ]);
            }

            // Menyimpan metadata otomatis.

            $media->file_name = basename($path);

            $media->mime_type = $mimeType;

            $media->file_size = $fileSize;

            $media->file_type =
                str_starts_with($mimeType, 'image/')
                    ? 'image'
                    : 'document';

        });
    }

    /**
     * Relasi administrator pengunggah.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }

    /**
     * Mengambil media gambar.
     */
    public function scopeImages(
        Builder $query
    ): Builder {
        return $query->where('file_type', 'image');
    }

    /**
     * Mengambil media dokumen.
     */
    public function scopeDocuments(
        Builder $query
    ): Builder {
        return $query->where('file_type', 'document');
    }
}
