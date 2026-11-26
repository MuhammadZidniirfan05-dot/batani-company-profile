<?php

namespace App\Filament\Resources\News\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Berita')
                    ->description('Masukkan informasi utama berita.')
                    ->schema([

                        TextInput::make('title')
                            ->label('Judul Berita')
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
                                'Slug otomatis dibuat dari judul dan dapat diubah secara manual.'
                            )
                            ->columnSpanFull(),

                        Select::make('category_id')
                            ->label('Kategori Berita')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->placeholder('Pilih kategori berita')
                            ->required(),

                        Textarea::make('excerpt')
                            ->label('Ringkasan Berita')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull()
                            ->helperText(
                                'Ringkasan singkat yang digunakan pada daftar berita.'
                            ),

                        RichEditor::make('content')
                            ->label('Isi Berita')
                            ->required()
                            ->columnSpanFull(),

                        FileUpload::make('featured_image')
                            ->label('Gambar Utama')
                            ->image()
                            ->disk('public')
                            ->directory('news')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->imageEditor()
                            ->helperText(
                                'Format gambar JPG, PNG, atau WebP. Maksimal 5 MB.'
                            )
                            ->columnSpanFull(),

                    ])
                    ->columns(2),

                Section::make('Publikasi')
                    ->description('Atur status dan jadwal penayangan berita.')
                    ->schema([

                        Select::make('status')
                            ->label('Status Berita')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('draft')
                            ->required()
                            ->native(false),

                        DateTimePicker::make('published_at')
                            ->label('Tanggal dan Waktu Publikasi')
                            ->native(false)
                            ->seconds(false)
                            ->displayFormat('d/m/Y H:i')
                            ->helperText(
                                'Kosongkan untuk menampilkan segera setelah status diubah menjadi Published. Isi tanggal mendatang untuk menjadwalkan publikasi.'
                            ),

                    ])
                    ->columns(2),

                Section::make('Optimasi Mesin Pencari (SEO)')
                    ->description('Informasi untuk mesin pencari.')
                    ->schema([

                        TextInput::make('seo_title')
                            ->label('SEO Title')
                            ->maxLength(255)
                            ->helperText(
                                'Judul yang digunakan pada hasil pencarian Google.'
                            ),

                        Textarea::make('seo_description')
                            ->label('SEO Description')
                            ->rows(3)
                            ->maxLength(320)
                            ->columnSpanFull()
                            ->helperText(
                                'Deskripsi singkat untuk mesin pencari.'
                            ),

                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),

                
Select::make('user_id')
    ->label('Penulis')
    ->relationship('author', 'name')
    ->default(fn () => auth()->id())
    ->disabled()
    ->dehydrated()
    ->required()
    ->native(false)
    ->helperText('Penulis otomatis mengikuti akun administrator yang sedang login.'),

            ]);
    }
}
