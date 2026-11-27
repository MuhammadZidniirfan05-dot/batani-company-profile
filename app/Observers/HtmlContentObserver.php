<?php

namespace App\Observers;

use App\Models\Achievement;
use App\Models\ItProject;
use App\Models\News;
use App\Models\OrganizationUnit;
use App\Models\Page;
use App\Models\Publication;
use App\Models\TeamMember;
use App\Services\HtmlSanitizerService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class HtmlContentObserver
{
    private const HTML_FIELDS = [

        News::class => [
            'content',
        ],

        Publication::class => [
            'content',
        ],

        ItProject::class => [
            'description',
        ],

        Page::class => [
            'content',
        ],

        TeamMember::class => [
            'biography',
        ],

        OrganizationUnit::class => [
            'description',
        ],

        Achievement::class => [
            'description',
        ],

    ];

    /**
     * Melakukan sanitasi sebelum data disimpan.
     */
    public function saving(Model $model): void
    {
        $fields = self::HTML_FIELDS[
            get_class($model)
        ] ?? [];

        if (empty($fields)) {
            return;
        }

        $sanitizer = app(
            HtmlSanitizerService::class
        );

        foreach ($fields as $field) {

            if (! $model->isDirty($field)) {
                continue;
            }

            $value = $model->getAttribute($field);

            if ($value === null) {
                continue;
            }

            if (! is_string($value)) {
                throw ValidationException::withMessages([
                    "data.{$field}" =>
                        'Konten harus berupa teks HTML.',
                ]);
            }

            if (
                mb_strlen($value)
                > HtmlSanitizerService::MAX_LENGTH
            ) {
                throw ValidationException::withMessages([
                    "data.{$field}" =>
                        'Konten terlalu panjang.',
                ]);
            }

            $sanitized = $sanitizer->sanitize($value);

            // Menyesuaikan batas kolom TEXT MySQL.
            if (
                $model instanceof TeamMember
                || $model instanceof OrganizationUnit
                || $model instanceof Achievement
            ) {
                if (strlen($sanitized ?? '') > 65535) {
                    throw ValidationException::withMessages([
                        "data.{$field}" =>
                            'Ukuran konten melebihi kapasitas penyimpanan.',
                    ]);
                }
            }

            $model->setAttribute(
                $field,
                $sanitized
            );
        }
    }
}
