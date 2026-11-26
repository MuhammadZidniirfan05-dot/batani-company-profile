<?php

namespace App\Filament\Resources\ItProjects;

use App\Filament\Resources\ItProjects\Pages\CreateItProject;
use App\Filament\Resources\ItProjects\Pages\EditItProject;
use App\Filament\Resources\ItProjects\Pages\ListItProjects;
use App\Filament\Resources\ItProjects\Schemas\ItProjectForm;
use App\Filament\Resources\ItProjects\Tables\ItProjectsTable;
use App\Models\ItProject;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class ItProjectResource extends Resource
{
    protected static ?string $model = ItProject::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Project IT';

    protected static ?string $modelLabel = 'proyek IT';

    protected static ?string $pluralModelLabel = 'proyek IT';

    protected static string|UnitEnum|null $navigationGroup = 'Publikasi';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return ItProjectForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ItProjectsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListItProjects::route('/'),
            'create' => CreateItProject::route('/create'),
            'edit' => EditItProject::route('/{record}/edit'),
        ];
    }
}
