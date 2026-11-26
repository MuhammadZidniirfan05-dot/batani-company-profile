<?php

namespace App\Filament\Resources\SocialLinks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SocialLinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informasi Media Sosial')
                ->description('Masukkan informasi akun media sosial perusahaan.')
                ->schema([

                    Select::make('platform')
                        ->label('Platform Media Sosial')
                        ->options([
                            'instagram' => 'Instagram',
                            'facebook' => 'Facebook',
                            'linkedin' => 'LinkedIn',
                            'youtube' => 'YouTube',
                            'tiktok' => 'TikTok',
                            'x' => 'X (Twitter)',
                            'threads' => 'Threads',
                            'other' => 'Lainnya',
                        ])
                        ->required()
                        ->native(false)
                        ->searchable(),

                    TextInput::make('label')
                        ->label('Nama Akun / Label')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Contoh: Instagram BATANI'),

                    TextInput::make('url')
                        ->label('URL Media Sosial')
                        ->required()
                        ->url()
                        ->maxLength(255)
                        ->placeholder('https://instagram.com/batani')
                        ->helperText(
                            'Masukkan alamat lengkap akun media sosial.'
                        )
                        ->columnSpanFull(),

                    TextInput::make('icon')
                        ->label('Nama Ikon')
                        ->maxLength(255)
                        ->placeholder('instagram')
                        ->helperText(
                            'Identitas ikon untuk digunakan pada website publik.'
                        ),

                ])
                ->columns(2),

            Section::make('Pengaturan Tampilan')
                ->description('Atur urutan dan status tautan media sosial.')
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
                            'Tautan aktif dapat ditampilkan pada website publik.'
                        ),

                ])
                ->columns(2),

        ]);
    }
}
