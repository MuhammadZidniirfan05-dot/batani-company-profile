<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informasi Pengaturan')
                ->description('Identitas dan klasifikasi pengaturan website.')
                ->schema([

                    Select::make('group')
                        ->label('Kelompok Pengaturan')
                        ->options([
                            'general' => 'Informasi Umum',
                            'contact' => 'Informasi Kontak',
                            'branding' => 'Identitas Visual',
                            'seo' => 'SEO Website',
                        ])
                        ->required()
                        ->native(false),

                    TextInput::make('key')
                        ->label('Key Pengaturan')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText(
                            'Contoh: company_name, company_email, company_logo.'
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
                        ->live()
                        ->native(false),

                ])
                ->columns(2),

            Section::make('Nilai Pengaturan')
                ->description('Isi nilai sesuai tipe data yang dipilih.')
                ->schema([

                    TextInput::make('value')
                        ->label('Nilai Pengaturan')
                        ->visible(
                            fn (Get $get): bool =>
                                in_array(
                                    $get('type'),
                                    ['text', 'email', 'phone', 'url'],
                                    true
                                )
                        )
                        ->required(
                            fn (Get $get): bool =>
                                in_array(
                                    $get('type'),
                                    ['text', 'email', 'phone', 'url'],
                                    true
                                )
                        )
                        ->email(
                            fn (Get $get): bool =>
                                $get('type') === 'email'
                        )
                        ->tel(
                            fn (Get $get): bool =>
                                $get('type') === 'phone'
                        )
                        ->url(
                            fn (Get $get): bool =>
                                $get('type') === 'url'
                        )
                        ->maxLength(65535)
                        ->columnSpanFull(),

                    Textarea::make('value')
                        ->label('Nilai Pengaturan')
                        ->visible(
                            fn (Get $get): bool =>
                                $get('type') === 'textarea'
                        )
                        ->required(
                            fn (Get $get): bool =>
                                $get('type') === 'textarea'
                        )
                        ->rows(5)
                        ->columnSpanFull(),

                    FileUpload::make('value')
                        ->label('Unggah Gambar')
                        ->visible(
                            fn (Get $get): bool =>
                                $get('type') === 'image'
                        )
                        ->image()
                        ->disk('public')
                        ->directory('site-settings')
                        ->visibility('public')
                        ->maxSize(5120)
                        ->imageEditor()
                        ->helperText(
                            'Format JPG, PNG, atau WebP. Maksimal 5 MB.'
                        )
                        ->columnSpanFull(),

                ]),

            Section::make('Pengaturan Tampilan')
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
                            'Aktifkan jika nilai pengaturan boleh digunakan pada halaman publik.'
                        ),

                ])
                ->columns(2),

        ]);
    }
}
