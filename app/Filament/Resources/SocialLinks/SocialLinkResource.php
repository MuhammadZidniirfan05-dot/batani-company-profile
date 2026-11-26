<?php

namespace App\Filament\Resources\SocialLinks;

use App\Filament\Resources\SocialLinks\Pages\CreateSocialLink;
use App\Filament\Resources\SocialLinks\Pages\EditSocialLink;
use App\Filament\Resources\SocialLinks\Pages\ListSocialLinks;
use App\Filament\Resources\SocialLinks\Schemas\SocialLinkForm;
use App\Filament\Resources\SocialLinks\Tables\SocialLinksTable;
use App\Models\SocialLink;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class SocialLinkResource extends Resource
{
    protected static ?string $model = SocialLink::class;

    protected static ?string $recordTitleAttribute = 'label';

    protected static ?string $navigationLabel = 'Media Sosial';

    protected static ?string $modelLabel = 'media sosial';

    protected static ?string $pluralModelLabel = 'media sosial';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan Website';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return SocialLinkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SocialLinksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSocialLinks::route('/'),
            'create' => CreateSocialLink::route('/create'),
            'edit' => EditSocialLink::route('/{record}/edit'),
        ];
    }
}
