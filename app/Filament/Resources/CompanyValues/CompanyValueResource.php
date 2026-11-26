<?php

namespace App\Filament\Resources\CompanyValues;

use App\Filament\Resources\CompanyValues\Pages\CreateCompanyValue;
use App\Filament\Resources\CompanyValues\Pages\EditCompanyValue;
use App\Filament\Resources\CompanyValues\Pages\ListCompanyValues;
use App\Filament\Resources\CompanyValues\Schemas\CompanyValueForm;
use App\Filament\Resources\CompanyValues\Tables\CompanyValuesTable;
use App\Models\CompanyValue;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class CompanyValueResource extends Resource
{
    protected static ?string $model = CompanyValue::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Nilai Perusahaan';

    protected static ?string $modelLabel = 'nilai perusahaan';

    protected static ?string $pluralModelLabel = 'nilai perusahaan';

    protected static string|UnitEnum|null $navigationGroup = 'Tentang Kami';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return CompanyValueForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompanyValuesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanyValues::route('/'),
            'create' => CreateCompanyValue::route('/create'),
            'edit' => EditCompanyValue::route('/{record}/edit'),
        ];
    }
}
