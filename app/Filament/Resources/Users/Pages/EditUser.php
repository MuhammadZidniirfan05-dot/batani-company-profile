<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Services\UserManagementService;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    /**
     * Menghilangkan tindakan penghapusan akun.
     */
    protected function getHeaderActions(): array
    {
        return [];
    }

    /**
     * Hanya memuat atribut yang diperlukan formulir.
     */
    protected function mutateFormDataBeforeFill(
        array $data
    ): array {

        return [
            ...Arr::only($data, [
                'name',
                'email',
                'role',
                'is_active',
            ]),

            'password' => null,
            'password_confirmation' => null,
        ];
    }

    /**
     * Memperbarui akun melalui service.
     */
    protected function handleRecordUpdate(
        Model $record,
        array $data
    ): Model {

        app(UserManagementService::class)
            ->update(
                Filament::auth()->id(),
                $record,
                $data
            );

        return $record->refresh();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Akun pengguna berhasil diperbarui.';
    }
}
