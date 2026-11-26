<?php

namespace App\Filament\Resources\NewsCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NewsCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(
                        function (
                            Get $get,
                            Set $set,
                            ?string $old,
                            ?string $state
                        ): void {
                            $currentSlug = $get('slug') ?? '';

                            if (
                                blank($currentSlug) ||
                                $currentSlug === Str::slug($old ?? '')
                            ) {
                                $set('slug', Str::slug($state ?? ''));
                            }
                        }
                    ),

                TextInput::make('slug')
                    ->label('Slug URL')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->dehydrateStateUsing(
                        fn (?string $state): string =>
                            Str::slug($state ?? '')
                    )
                    ->helperText(
                        'Slug dibuat otomatis berdasarkan nama kategori. Anda tetap dapat mengubahnya.'
                    ),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('sort_order')
                    ->label('Urutan Tampilan')
                    ->numeric()
                    ->integer()
                    ->minValue(0)
                    ->default(0)
                    ->required(),

                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),

            ]);
    }
}
