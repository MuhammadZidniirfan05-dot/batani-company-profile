<?php

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use Filament\Resources\Pages\EditRecord;

class EditContactMessage extends EditRecord
{
    protected static string $resource = ContactMessageResource::class;

    /**
     * Membatasi tindakan pada halaman pengelolaan pesan.
     */
    protected function getHeaderActions(): array
    {
        return [];
    }

    /**
     * Administrator hanya dapat memperbarui status
     * dan catatan tindak lanjut.
     */
    protected function mutateFormDataBeforeSave(
        array $data
    ): array {
        return [
            'status' => $data['status'],
            'admin_notes' => $data['admin_notes'] ?? null,
        ];
    }

    /**
     * Mengarahkan kembali ke daftar pesan setelah disimpan.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Penanganan pesan berhasil diperbarui.';
    }
}
