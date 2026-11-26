<?php

namespace App\Filament\Resources\Achievements;

use App\Filament\Resources\Achievements\Pages\CreateAchievement;
use App\Filament\Resources\Achievements\Pages\EditAchievement;
use App\Filament\Resources\Achievements\Pages\ListAchievements;
use App\Filament\Resources\Achievements\Schemas\AchievementForm;
use App\Filament\Resources\Achievements\Tables\AchievementsTable;
use App\Models\Achievement;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class AchievementResource extends Resource
{
    protected static ?string $model = Achievement::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Pencapaian';

    protected static ?string $modelLabel = 'pencapaian';

    protected static ?string $pluralModelLabel = 'pencapaian';

    protected static string|UnitEnum|null $navigationGroup = 'Tentang Kami';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return AchievementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AchievementsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAchievements::route('/'),
            'create' => CreateAchievement::route('/create'),
            'edit' => EditAchievement::route('/{record}/edit'),
        ];
    }
}
