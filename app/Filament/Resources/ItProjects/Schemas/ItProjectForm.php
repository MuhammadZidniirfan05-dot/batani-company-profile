<?php

namespace App\Filament\Resources\ItProjects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ItProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // ==========================================
            // INFORMASI PROYEK
            // ==========================================

            Section::make('Informasi Proyek')
                ->description('Informasi dasar proyek teknologi informasi.')
                ->schema([

                    TextInput::make('title')
                        ->label('Nama Proyek')
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
                            'Slug otomatis dibuat berdasarkan nama proyek dan dapat diubah.'
                        )
                        ->columnSpanFull(),

                    Select::make('category_id')
                        ->label('Kategori Proyek')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->required()
                        ->placeholder('Pilih kategori proyek'),

                    TextInput::make('client')
                        ->label('Klien / Instansi')
                        ->maxLength(255)
                        ->placeholder('Nama klien atau instansi'),

                    Textarea::make('excerpt')
                        ->label('Ringkasan Proyek')
                        ->rows(3)
                        ->maxLength(1000)
                        ->columnSpanFull(),

                    RichEditor::make('description')
                        ->label('Deskripsi Lengkap Proyek')
                        ->columnSpanFull(),

                ])
                ->columns(2),

            // ==========================================
            // DOKUMENTASI PROYEK
            // ==========================================

            Section::make('Dokumentasi Proyek')
                ->description('Unggah gambar utama dan dokumentasi proyek.')
                ->schema([

                    FileUpload::make('thumbnail')
                        ->label('Thumbnail Proyek')
                        ->image()
                        ->disk('public')
                        ->directory('it-projects/thumbnails')
                        ->visibility('public')
                        ->maxSize(5120)
                        ->imageEditor()
                        ->helperText(
                            'Gambar utama proyek. Format JPG, PNG, atau WebP. Maksimal 5 MB.'
                        )
                        ->columnSpanFull(),

                    FileUpload::make('gallery')
                        ->label('Galeri Dokumentasi')
                        ->image()
                        ->multiple()
                        ->maxFiles(10)
                        ->disk('public')
                        ->directory('it-projects/gallery')
                        ->visibility('public')
                        ->maxSize(5120)
                        ->reorderable()
                        ->helperText(
                            'Unggah maksimal 10 gambar dokumentasi. Setiap gambar maksimal 5 MB.'
                        )
                        ->columnSpanFull(),

                ])
                ->columns(2),

            // ==========================================
            // TEKNOLOGI DAN TAUTAN
            // ==========================================

            Section::make('Teknologi dan Tautan Proyek')
                ->description('Informasi teknologi dan akses proyek.')
                ->schema([

                    TagsInput::make('technologies')
                        ->label('Teknologi yang Digunakan')
                        ->placeholder('Masukkan teknologi, lalu tekan Enter')
                        ->helperText(
                            'Contoh: Laravel, Vue.js, MySQL, React, dan lainnya.'
                        )
                        ->columnSpanFull(),

                    TextInput::make('project_url')
                        ->label('URL Proyek')
                        ->url()
                        ->maxLength(255)
                        ->placeholder('https://contoh.com'),

                    TextInput::make('demo_url')
                        ->label('URL Demo')
                        ->url()
                        ->maxLength(255)
                        ->placeholder('https://demo.contoh.com'),

                ])
                ->columns(2),

            // ==========================================
            // WAKTU PELAKSANAAN
            // ==========================================

            Section::make('Waktu Pelaksanaan')
                ->description('Tanggal mulai dan penyelesaian proyek.')
                ->schema([

                    DatePicker::make('started_at')
                        ->label('Tanggal Mulai')
                        ->native(false)
                        ->displayFormat('d/m/Y'),

                    DatePicker::make('completed_at')
                        ->label('Tanggal Selesai')
                        ->native(false)
                        ->displayFormat('d/m/Y'),

                ])
                ->columns(2),

            // ==========================================
            // PENGATURAN PUBLIKASI
            // ==========================================

            Section::make('Pengaturan Publikasi')
                ->description('Atur status dan jadwal penayangan proyek.')
                ->schema([

                    Select::make('status')
                        ->label('Status Proyek')
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
                            'Kosongkan untuk publikasi langsung. Isi tanggal dan waktu mendatang untuk menjadwalkan publikasi.'
                        ),

                    Select::make('user_id')
                        ->label('Dikelola Oleh')
                        ->relationship('author', 'name')
                        ->default(fn () => auth()->id())
                        ->disabled()
                        ->dehydrated()
                        ->required()
                        ->native(false)
                        ->helperText(
                            'Otomatis mengikuti administrator yang sedang login.'
                        ),

                ])
                ->columns(2),

            // ==========================================
            // SEO
            // ==========================================

            Section::make('Optimasi Mesin Pencari (SEO)')
                ->description('Metadata untuk mesin pencari.')
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
