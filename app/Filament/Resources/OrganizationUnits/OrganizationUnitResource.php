<?php

namespace App\Filament\Resources\OrganizationUnits;

use App\Filament\Resources\OrganizationUnits\Pages\CreateOrganizationUnit;
use App\Filament\Resources\OrganizationUnits\Pages\EditOrganizationUnit;
use App\Filament\Resources\OrganizationUnits\Pages\ListOrganizationUnits;
use App\Filament\Resources\OrganizationUnits\Schemas\OrganizationUnitForm;
use App\Filament\Resources\OrganizationUnits\Tables\OrganizationUnitsTable;
use App\Models\OrganizationUnit;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class OrganizationUnitResource extends Resource
{
    protected static ?string $model = OrganizationUnit::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Struktur Organisasi';

    protected static ?string $modelLabel = 'unit organisasi';

    protected static ?string $pluralModelLabel = 'unit organisasi';

    protected static string|UnitEnum|null $navigationGroup = 'Tentang Kami';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return OrganizationUnitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrganizationUnitsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrganizationUnits::route('/'),
            'create' => CreateOrganizationUnit::route('/create'),
            'edit' => EditOrganizationUnit::route('/{record}/edit'),
        ];
    }
}
