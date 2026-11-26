<?php

namespace App\Filament\Resources\PublicationCategories;

use App\Filament\Resources\PublicationCategories\Pages\CreatePublicationCategory;
use App\Filament\Resources\PublicationCategories\Pages\EditPublicationCategory;
use App\Filament\Resources\PublicationCategories\Pages\ListPublicationCategories;
use App\Filament\Resources\PublicationCategories\Schemas\PublicationCategoryForm;
use App\Filament\Resources\PublicationCategories\Tables\PublicationCategoriesTable;
use App\Models\PublicationCategory;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class PublicationCategoryResource extends Resource
{
    protected static ?string $model = PublicationCategory::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Kategori Publikasi';

    protected static ?string $modelLabel = 'kategori publikasi';

    protected static ?string $pluralModelLabel = 'kategori publikasi';

    protected static string|UnitEnum|null $navigationGroup = 'Publikasi';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return PublicationCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PublicationCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPublicationCategories::route('/'),
            'create' => CreatePublicationCategory::route('/create'),
            'edit' => EditPublicationCategory::route('/{record}/edit'),
        ];
    }
}
