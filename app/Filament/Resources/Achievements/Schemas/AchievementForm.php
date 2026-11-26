<?php

namespace App\Filament\Resources\Achievements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AchievementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // ==========================================
            // INFORMASI PENCAPAIAN
            // ==========================================

            Section::make('Informasi Pencapaian')
                ->description('Identitas dan informasi pencapaian perusahaan.')
                ->schema([

                    TextInput::make('title')
                        ->label('Nama Pencapaian')
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
                            'Slug dibuat otomatis berdasarkan nama pencapaian.'
                        )
                        ->columnSpanFull(),

                    TextInput::make('issuer')
                        ->label('Pemberi Penghargaan / Instansi')
                        ->maxLength(255)
                        ->placeholder('Nama instansi atau lembaga'),

                    DatePicker::make('achieved_at')
                        ->label('Tanggal Pencapaian')
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->placeholder('Pilih tanggal pencapaian'),

                    RichEditor::make('description')
                        ->label('Deskripsi Pencapaian')
                        ->columnSpanFull()
                        ->helperText(
                            'Jelaskan latar belakang, proses, dan makna pencapaian.'
                        ),

                ])
                ->columns(2),

            // ==========================================
            // DOKUMENTASI PENCAPAIAN
            // ==========================================

            Section::make('Dokumentasi Pencapaian')
                ->description('Unggah foto penghargaan atau sertifikat.')
                ->schema([

                    FileUpload::make('image')
                        ->label('Foto / Sertifikat')
                        ->image()
                        ->disk('public')
                        ->directory('achievements')
                        ->visibility('public')
                        ->maxSize(5120)
                        ->imageEditor()
                        ->helperText(
                            'Format JPG, PNG, atau WebP. Maksimal 5 MB.'
                        )
                        ->columnSpanFull(),

                ]),

            // ==========================================
            // PENGATURAN TAMPILAN
            // ==========================================

            Section::make('Pengaturan Tampilan')
                ->description('Atur urutan dan status pencapaian.')
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
                            'Pencapaian aktif akan ditampilkan pada website publik.'
                        ),

                ])
                ->columns(2),

        ]);
    }
}
