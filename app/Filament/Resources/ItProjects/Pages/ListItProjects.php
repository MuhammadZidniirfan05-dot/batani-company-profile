<?php

namespace App\Filament\Resources\ItProjects\Pages;

use App\Filament\Resources\ItProjects\ItProjectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListItProjects extends ListRecords
{
    protected static string $resource = ItProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
