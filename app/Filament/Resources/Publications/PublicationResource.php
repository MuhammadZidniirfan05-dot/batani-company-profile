<?php

namespace App\Filament\Resources\Publications;

use App\Filament\Resources\Publications\Pages\CreatePublication;
use App\Filament\Resources\Publications\Pages\EditPublication;
use App\Filament\Resources\Publications\Pages\ListPublications;
use App\Filament\Resources\Publications\Schemas\PublicationForm;
use App\Filament\Resources\Publications\Tables\PublicationsTable;
use App\Models\Publication;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class PublicationResource extends Resource
{
    protected static ?string $model = Publication::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Publikasi dan Kajian';

    protected static ?string $modelLabel = 'publikasi';

    protected static ?string $pluralModelLabel = 'publikasi';

    protected static string|UnitEnum|null $navigationGroup = 'Publikasi';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return PublicationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PublicationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPublications::route('/'),
            'create' => CreatePublication::route('/create'),
            'edit' => EditPublication::route('/{record}/edit'),
        ];
    }
}
