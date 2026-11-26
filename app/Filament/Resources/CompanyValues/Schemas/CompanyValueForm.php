<?php

namespace App\Filament\Resources\CompanyValues\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CompanyValueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informasi Nilai Perusahaan')
                ->description('Identitas nilai dan prinsip perusahaan BATANI.')
                ->schema([

                    TextInput::make('title')
                        ->label('Nama Nilai Perusahaan')
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
                                    $set(
                                        'slug',
                                        Str::slug($state ?? '')
                                    );
                                }
                            }
                        )
                        ->columnSpanFull(),

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
                            'Slug dibuat otomatis berdasarkan nama nilai perusahaan dan dapat diubah.'
                        )
                        ->columnSpanFull(),

                    Textarea::make('description')
                        ->label('Deskripsi Nilai Perusahaan')
                        ->required()
                        ->rows(5)
                        ->columnSpanFull()
                        ->helperText(
                            'Jelaskan makna dan penerapan nilai perusahaan.'
                        ),

                    TextInput::make('icon')
                        ->label('Nama Ikon')
                        ->maxLength(255)
                        ->placeholder('Contoh: lightbulb, shield-check')
                        ->helperText(
                            'Masukkan nama ikon yang nantinya digunakan pada website publik.'
                        )
                        ->columnSpanFull(),

                ])
                ->columns(2),

            Section::make('Pengaturan Tampilan')
                ->description('Atur urutan dan status nilai perusahaan.')
                ->schema([

                    TextInput::make('sort_order')
                        ->label('Urutan Tampilan')
                        ->numeric()
                        ->integer()
                        ->minValue(0)
                        ->default(0)
                        ->required(),

                    Toggle::make('is_active')
                        ->label('Status Aktif')
                        ->default(true)
                        ->helperText(
                            'Nilai yang aktif akan ditampilkan pada website publik.'
                        ),

                ])
                ->columns(2),

        ]);
    }
}
