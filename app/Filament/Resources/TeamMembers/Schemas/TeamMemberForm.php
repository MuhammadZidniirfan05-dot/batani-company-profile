<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // ==========================================
            // INFORMASI PERSONEL
            // ==========================================

            Section::make('Informasi Personel')
                ->description('Identitas dan jabatan personel BATANI.')
                ->schema([

                    TextInput::make('name')
                        ->label('Nama Lengkap')
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
                            'Slug profil dibuat otomatis berdasarkan nama personel.'
                        ),

                    Select::make('member_type')
                        ->label('Jenis Personel')
                        ->options([
                            'executive_director' => 'Direktur Eksekutif',
                            'expert' => 'Tim Ahli',
                        ])
                        ->required()
                        ->native(false)
                        ->live(),

                    TextInput::make('position')
                        ->label('Jabatan')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Contoh: Direktur Eksekutif'),

                    TextInput::make('expertise')
                        ->label('Bidang Keahlian')
                        ->maxLength(255)
                        ->placeholder('Contoh: Perencanaan Wilayah dan Kota')
                        ->helperText(
                            'Isi bidang keahlian atau spesialisasi personel.'
                        )
                        ->visible(
                            fn (Get $get): bool =>
                                $get('member_type') === 'expert'
                        ),

                ])
                ->columns(2),

            // ==========================================
            // PROFIL DAN BIOGRAFI
            // ==========================================

            Section::make('Profil Personel')
                ->description('Informasi profil dan riwayat profesional.')
                ->schema([

                    FileUpload::make('photo')
                        ->label('Foto Personel')
                        ->image()
                        ->disk('public')
                        ->directory('team-members')
                        ->visibility('public')
                        ->maxSize(5120)
                        ->imageEditor()
                        ->helperText(
                            'Format JPG, PNG, atau WebP. Maksimal 5 MB.'
                        )
                        ->columnSpanFull(),

                    RichEditor::make('biography')
                        ->label('Biografi')
                        ->columnSpanFull()
                        ->helperText(
                            'Masukkan riwayat pendidikan, pengalaman, dan profil profesional personel.'
                        ),

                ]),

            // ==========================================
            // INFORMASI KONTAK
            // ==========================================

            Section::make('Informasi Kontak')
                ->schema([

                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->maxLength(255)
                        ->placeholder('nama@example.com'),

                    TextInput::make('linkedin_url')
                        ->label('LinkedIn')
                        ->url()
                        ->maxLength(255)
                        ->placeholder('https://linkedin.com/in/...'),

                ])
                ->columns(2)
                ->collapsible(),

            // ==========================================
            // PENGATURAN TAMPILAN
            // ==========================================

            Section::make('Pengaturan Tampilan')
                ->schema([

                    TextInput::make('sort_order')
                        ->label('Urutan Tampilan')
                        ->numeric()
                        ->integer()
                        ->minValue(0)
                        ->default(0)
                        ->required(),

                    \Filament\Forms\Components\Toggle::make('is_active')
                        ->label('Status Aktif')
                        ->default(true)
                        ->helperText(
                            'Personel aktif akan ditampilkan pada website publik.'
                        ),

                ])
                ->columns(2),

        ]);
    }
}
