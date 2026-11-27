<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // INFORMASI PENGATURAN

            Section::make('Informasi Pengaturan')
                ->description(
                    'Identitas dan klasifikasi pengaturan website BATANI.'
                )
                ->schema([

                    Select::make('group')
                        ->label('Kelompok Pengaturan')
                        ->options([
                            'general' => 'Informasi Umum',
                            'branding' => 'Identitas Visual',
                            'contact' => 'Informasi Kontak',
                            'seo' => 'SEO Website',
                        ])
                        ->default('general')
                        ->required()
                        ->native(false),

                    TextInput::make('key')
                        ->label('Key Pengaturan')
                        ->required()
                        ->maxLength(255)
                        ->regex('/\A[a-z][a-z0-9_]*\z/')
                        ->unique(ignoreRecord: true)
                        ->disabledOn('edit')
                        ->helperText(
                            'Gunakan huruf kecil, angka, dan underscore. Key tidak dapat diubah setelah dibuat.'
                        ),

                    Select::make('type')
                        ->label('Tipe Data')
                        ->options([
                            'text' => 'Teks',
                            'textarea' => 'Teks Panjang',
                            'email' => 'Email',
                            'phone' => 'Nomor Telepon',
                            'url' => 'URL / Tautan',
                            'image' => 'Gambar',
                        ])
                        ->default('text')
                        ->required()
                        ->native(false)
                        ->disabledOn('edit')
                        ->live()
                        ->afterStateUpdated(
                            fn (Select $component) =>
                                $component
                                    ->getContainer()
                                    ->getComponent('dynamicValue')
                                    ->getChildSchema()
                                    ->fill()
                        ),

                    Grid::make(1)
                        ->key('dynamicValue')
                        ->schema(
                            fn (Get $get): array =>
                                match ($get('type')) {

                                    'text' => [
                                        TextInput::make('value')
                                            ->label('Nilai Pengaturan')
                                            ->maxLength(2000),
                                    ],

                                    'textarea' => [
                                        Textarea::make('value')
                                            ->label('Nilai Pengaturan')
                                            ->rows(5)
                                            ->maxLength(10000),
                                    ],

                                    'email' => [
                                        TextInput::make('value')
                                            ->label('Alamat Email')
                                            ->email()
                                            ->maxLength(255),
                                    ],

                                    'phone' => [
                                        TextInput::make('value')
                                            ->label('Nomor Telepon')
                                            ->tel()
                                            ->regex('/^[0-9+().\s-]+$/')
                                            ->maxLength(30),
                                    ],

                                    'url' => [
                                        TextInput::make('value')
                                            ->label('Alamat URL')
                                            ->url()
                                            ->rule('url:http,https')
                                            ->maxLength(2000),
                                    ],

                                    'image' => [
                                        FileUpload::make('value')
                                            ->label('Unggah Gambar')
                                            ->image()
                                            ->acceptedFileTypes([
                                                'image/jpeg',
                                                'image/png',
                                                'image/webp',
                                            ])
                                            ->disk('public')
                                            ->directory('site-settings')
                                            ->visibility('public')
                                            ->preventFilePathTampering()
                                            ->maxSize(5120)
                                            ->imageEditor()
                                            ->helperText(
                                                'Format JPG, PNG, atau WebP. Maksimal 5 MB.'
                                            ),
                                    ],

                                    default => [],

                                }
                        )
                        ->columnSpanFull(),

                ])
                ->columns(2),

            // PENGATURAN TAMPILAN

            Section::make('Pengaturan Tampilan')
                ->description(
                    'Pengaturan urutan dan visibilitas data.'
                )
                ->schema([

                    TextInput::make('sort_order')
                        ->label('Urutan Tampilan')
                        ->numeric()
                        ->integer()
                        ->minValue(0)
                        ->default(0)
                        ->required(),

                    Toggle::make('is_public')
                        ->label('Tampilkan pada Website Publik')
                        ->default(false)
                        ->helperText(
                            'Aktifkan hanya untuk informasi yang boleh diakses oleh pengunjung.'
                        ),

                ])
                ->columns(2),

        ]);
    }
}
