<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // ==========================================
            // INFORMASI HALAMAN
            // ==========================================

            Section::make('Informasi Halaman')
                ->description('Identitas dan informasi dasar halaman perusahaan.')
                ->schema([

                    Select::make('page_key')
                        ->label('Jenis Halaman')
                        ->options([
                            'about' => 'Tentang Kami',
                            'vision-mission' => 'Visi dan Misi',
                            'company-goals' => 'Tujuan Perusahaan',
                        ])
                        ->required()
                        ->native(false)
                        ->unique(ignoreRecord: true)
                        ->helperText(
                            'Pilih jenis halaman yang akan dikelola.'
                        ),

                    TextInput::make('title')
                        ->label('Judul Halaman')
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
                            'Alamat URL halaman. Dibuat otomatis berdasarkan judul.'
                        )
                        ->columnSpanFull(),

                    Textarea::make('excerpt')
                        ->label('Ringkasan Halaman')
                        ->rows(3)
                        ->maxLength(1000)
                        ->columnSpanFull()
                        ->helperText(
                            'Deskripsi singkat yang mewakili isi halaman.'
                        ),

                ])
                ->columns(2),

            // ==========================================
            // KONTEN HALAMAN
            // ==========================================

            Section::make('Konten Halaman')
                ->description('Kelola isi informasi perusahaan.')
                ->schema([

                    RichEditor::make('content')
                        ->label('Isi Halaman')
                        ->required()
                        ->columnSpanFull(),

                    FileUpload::make('featured_image')
                        ->label('Gambar Utama')
                        ->image()
                        ->disk('public')
                        ->directory('pages')
                        ->visibility('public')
                        ->maxSize(5120)
                        ->imageEditor()
                        ->helperText(
                            'Format JPG, PNG, atau WebP. Maksimal 5 MB.'
                        )
                        ->columnSpanFull(),

                ]),

            // ==========================================
            // PENGATURAN PUBLIKASI
            // ==========================================

            Section::make('Pengaturan Publikasi')
                ->description('Atur status dan jadwal penayangan halaman.')
                ->schema([

                    Select::make('status')
                        ->label('Status Halaman')
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                        ])
                        ->default('draft')
                        ->required()
                        ->native(false),

                    DateTimePicker::make('published_at')
                        ->label('Jadwal Tayang di Website')
                        ->native(false)
                        ->seconds(false)
                        ->displayFormat('d/m/Y H:i')
                        ->default(null)
                        ->helperText(
                            'Kosongkan untuk publikasi langsung. Isi tanggal mendatang untuk menjadwalkan publikasi.'
                        ),

                ])
                ->columns(2),

            // ==========================================
            // SEO
            // ==========================================

            Section::make('Optimasi Mesin Pencari (SEO)')
                ->description('Pengaturan metadata halaman.')
                ->schema([

                    TextInput::make('seo_title')
                        ->label('SEO Title')
                        ->maxLength(255),

                    Textarea::make('seo_description')
                        ->label('SEO Description')
                        ->rows(3)
                        ->maxLength(320)
                        ->columnSpanFull(),

                ])
                ->columns(2)
                ->collapsible()
                ->collapsed(),

        ]);
    }
}
