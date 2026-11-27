<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Services\UserManagementService;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * Membuat akun melalui service.
     */
    protected function handleRecordCreation(
        array $data
    ): Model {
        return app(UserManagementService::class)
            ->create(
                Filament::auth()->id(),
                $data
            );
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Akun pengguna berhasil dibuat.';
    }
}
