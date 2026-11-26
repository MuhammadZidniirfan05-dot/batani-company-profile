<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // ==========================================
            // INFORMASI MEDIA
            // ==========================================

            Section::make('Informasi Media')
                ->description('Identitas dan informasi file yang diunggah.')
                ->schema([

                    TextInput::make('name')
                        ->label('Nama Media')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Contoh: Logo BATANI')
                        ->helperText(
                            'Nama yang digunakan untuk mengidentifikasi media di dalam Media Library.'
                        )
                        ->columnSpanFull(),

                    FileUpload::make('file_path')
                        ->label('Unggah File')
                        ->required()
                        ->disk('public')
                        ->directory('media')
                        ->visibility('public')
                        ->acceptedFileTypes([
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            'image/gif',
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        ])
                        ->maxSize(20480)
                        ->downloadable()
                        ->openable()
                        ->helperText(
                            'Format yang diperbolehkan: JPG, PNG, WebP, GIF, PDF, DOC, DOCX, XLS, XLSX, PPT, dan PPTX. Maksimal 20 MB.'
                        )
                        ->columnSpanFull(),

                    TextInput::make('alt_text')
                        ->label('Alt Text')
                        ->maxLength(255)
                        ->placeholder('Deskripsi singkat gambar')
                        ->helperText(
                            'Digunakan sebagai teks alternatif gambar untuk aksesibilitas dan SEO.'
                        )
                        ->columnSpanFull(),

                    Textarea::make('description')
                        ->label('Deskripsi Media')
                        ->rows(4)
                        ->maxLength(2000)
                        ->placeholder('Keterangan tambahan mengenai file.')
                        ->columnSpanFull(),

                ])
                ->columns(2),

            // ==========================================
            // INFORMASI OTOMATIS
            // ==========================================

            Section::make('Informasi File')
                ->description('Metadata file dicatat otomatis oleh sistem.')
                ->schema([

                    TextInput::make('file_name')
                        ->label('Nama File')
                        ->disabled()
                        ->dehydrated(false)
                        ->placeholder('Dibuat otomatis setelah upload'),

                    TextInput::make('file_type')
                        ->label('Jenis File')
                        ->disabled()
                        ->dehydrated(false)
                        ->placeholder('Dibuat otomatis setelah upload'),

                    TextInput::make('mime_type')
                        ->label('MIME Type')
                        ->disabled()
                        ->dehydrated(false)
                        ->placeholder('Dibuat otomatis setelah upload'),

                    TextInput::make('file_size')
                        ->label('Ukuran File (Byte)')
                        ->disabled()
                        ->dehydrated(false)
                        ->placeholder('Dibuat otomatis setelah upload'),

                ])
                ->columns(2)
                ->collapsible()
                ->collapsed(),

        ]);
    }
}
