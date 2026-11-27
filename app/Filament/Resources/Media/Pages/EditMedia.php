<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use Filament\Resources\Pages\EditRecord;

class EditMedia extends EditRecord
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    /**
     * Hanya mengizinkan perubahan metadata.
     */
    protected function mutateFormDataBeforeSave(
        array $data
    ): array {
        return [
            'name' => $data['name'],
            'alt_text' => $data['alt_text'] ?? null,
            'description' => $data['description'] ?? null,
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Metadata media berhasil diperbarui.';
    }
}
