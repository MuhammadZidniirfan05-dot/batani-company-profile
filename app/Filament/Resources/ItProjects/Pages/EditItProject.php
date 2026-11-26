<?php

namespace App\Filament\Resources\ItProjects\Pages;

use App\Filament\Resources\ItProjects\ItProjectResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditItProject extends EditRecord
{
    protected static string $resource = ItProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
