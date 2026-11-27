<?php

namespace App\Filament\Resources\Media\Schemas;

use App\Models\Media;
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

            Section::make('Informasi Media')
                ->description(
                    'Media Library hanya digunakan untuk file yang boleh diakses publik.'
                )
                ->schema([

                    TextInput::make('name')
                        ->label('Nama Media')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    FileUpload::make('file_path')
                        ->label('Unggah File')
                        ->required(
                            fn (string $operation): bool =>
                                $operation === 'create'
                        )
                        ->disabled(
                            fn (string $operation): bool =>
                                $operation === 'edit'
                        )
                        ->disk('public')
                        ->directory('media')
                        ->visibility('public')
                        ->acceptedFileTypes(
                            array_keys(Media::ALLOWED_TYPES)
                        )
                        ->preventFilePathTampering()
                        ->maxSize(20480)
                        ->downloadable()
                        ->openable()
                        ->helperText(
                            'Format JPG, JPEG, PNG, WebP, GIF, dan PDF. Maksimal 20 MB. Jangan mengunggah dokumen rahasia. Untuk mengganti file, buat media baru.'
                        )
                        ->columnSpanFull(),

                    TextInput::make('alt_text')
                        ->label('Alt Text')
                        ->maxLength(255)
                        ->helperText(
                            'Deskripsi alternatif gambar untuk aksesibilitas website.'
                        )
                        ->columnSpanFull(),

                    Textarea::make('description')
                        ->label('Deskripsi Media')
                        ->rows(4)
                        ->maxLength(2000)
                        ->columnSpanFull(),

                ])
                ->columns(2),

            Section::make('Informasi File')
                ->description(
                    'Metadata dicatat otomatis oleh sistem.'
                )
                ->schema([

                    TextInput::make('file_name')
                        ->label('Nama File')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('file_type')
                        ->label('Jenis File')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('mime_type')
                        ->label('MIME Type')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('file_size')
                        ->label('Ukuran File (Byte)')
                        ->disabled()
                        ->dehydrated(false),

                ])
                ->columns(2)
                ->collapsible()
                ->collapsed()
                ->visible(
                    fn (string $operation): bool =>
                        $operation === 'edit'
                ),

        ]);
    }
}
