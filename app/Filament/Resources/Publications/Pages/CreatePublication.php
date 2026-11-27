<?php

namespace App\Filament\Resources\Publications\Pages;

use App\Filament\Resources\Publications\PublicationResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePublication extends CreateRecord
{
    protected static string $resource = PublicationResource::class;

    /**
     * Menentukan administrator pembuat publikasi.
     */
    protected function mutateFormDataBeforeCreate(
        array $data
    ): array {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
