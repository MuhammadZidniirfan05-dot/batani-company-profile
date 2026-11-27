<?php

namespace App\Filament\Resources\Publications\Schemas;

use Filament\Forms\Components\DatePicker;
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

class PublicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // INFORMASI PUBLIKASI

            Section::make('Informasi Publikasi')
                ->description('Informasi dasar publikasi dan kajian.')
                ->schema([

                    TextInput::make('title')
                        ->label('Judul Publikasi')
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
                        ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                        ->unique(ignoreRecord: true)
                        ->helperText(
                            'Gunakan huruf kecil, angka, dan tanda hubung.'
                        )
                        ->columnSpanFull(),

                    Select::make('category_id')
                        ->label('Kategori Publikasi')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->required(),

                    TextInput::make('authors')
                        ->label('Penulis / Tim Penyusun')
                        ->maxLength(255),

                    TextInput::make('publisher')
                        ->label('Penerbit / Instansi')
                        ->maxLength(255),

                    DatePicker::make('publication_date')
                        ->label('Tanggal Terbit Dokumen')
                        ->native(false)
                        ->displayFormat('d/m/Y'),

                    Textarea::make('abstract')
                        ->label('Abstrak / Ringkasan')
                        ->rows(5)
                        ->columnSpanFull(),

                    RichEditor::make('content')
                        ->label('Isi Publikasi')
                        ->columnSpanFull(),

                ])
                ->columns(2),

            // DOKUMEN DAN MEDIA

            Section::make('Dokumen dan Media')
                ->description('Unggah cover dan dokumen publikasi.')
                ->schema([

                    FileUpload::make('cover_image')
                        ->label('Cover Publikasi')
                        ->image()
                        ->acceptedFileTypes([
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ])
                        ->disk('public')
                        ->directory('publications/covers')
                        ->visibility('public')
                        ->preventFilePathTampering()
                        ->maxSize(5120)
                        ->imageEditor()
                        ->helperText(
                            'Format JPG, PNG, atau WebP. Maksimal 5 MB.'
                        )
                        ->columnSpanFull(),

                    FileUpload::make('document_path')
                        ->label('Dokumen Publikasi (PDF)')
                        ->acceptedFileTypes([
                            'application/pdf',
                        ])
                        ->disk('local')
                        ->directory('publications/documents')
                        ->visibility('private')
                        ->preventFilePathTampering()
                        ->maxSize(20480)
                        ->downloadable()
                        ->openable()
                        ->helperText(
                            'PDF maksimal 20 MB. Dokumen disimpan secara privat dan hanya dapat diunduh pengunjung setelah dipublikasikan.'
                        )
                        ->columnSpanFull(),

                ])
                ->columns(2),

            // PENGATURAN PUBLIKASI

            Section::make('Pengaturan Publikasi')
                ->description('Atur status dan jadwal penayangan.')
                ->schema([

                    Select::make('status')
                        ->label('Status Publikasi')
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
                            'Kosongkan untuk publikasi langsung. Isi tanggal mendatang untuk penjadwalan.'
                        ),

                    Select::make('user_id')
                        ->label('Dikelola Oleh')
                        ->relationship('author', 'name')
                        ->default(fn () => auth()->id())
                        ->disabled()
                        ->dehydrated(false)
                        ->native(false)
                        ->helperText(
                            'Administrator yang pertama kali membuat publikasi.'
                        ),

                ])
                ->columns(2),

            // SEO

            Section::make('Optimasi Mesin Pencari (SEO)')
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
