<?php

namespace App\Filament\Resources\OrganizationUnits\Schemas;

use App\Models\OrganizationUnit;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class OrganizationUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // ==========================================
            // INFORMASI UNIT ORGANISASI
            // ==========================================

            Section::make('Informasi Unit Organisasi')
                ->description('Identitas dan kedudukan unit dalam organisasi BATANI.')
                ->schema([

                    TextInput::make('name')
                        ->label('Nama Unit Organisasi')
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
                            'Slug dibuat otomatis berdasarkan nama unit.'
                        ),

                    Select::make('parent_id')
                        ->label('Unit Induk')
                        ->options(
                            fn (?OrganizationUnit $record): array =>
                                self::getParentOptions($record)
                        )
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->placeholder('Tidak memiliki unit induk')
                        ->helperText(
                            'Kosongkan jika unit merupakan bagian tertinggi dalam struktur organisasi.'
                        )
                        ->nullable(),

                    TextInput::make('position')
                        ->label('Jabatan / Posisi')
                        ->maxLength(255)
                        ->placeholder('Contoh: Kepala Divisi'),

                    TextInput::make('leader_name')
                        ->label('Nama Pimpinan Unit')
                        ->maxLength(255)
                        ->placeholder('Nama pimpinan atau penanggung jawab'),

                ])
                ->columns(2),

            // ==========================================
            // INFORMASI DAN PROFIL UNIT
            // ==========================================

            Section::make('Profil Unit Organisasi')
                ->description('Informasi tugas, fungsi, dan dokumentasi unit.')
                ->schema([

                    RichEditor::make('description')
                        ->label('Deskripsi Unit')
                        ->columnSpanFull(),

                    FileUpload::make('photo')
                        ->label('Foto Unit / Pimpinan')
                        ->image()
                        ->disk('public')
                        ->directory('organization-units')
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
                ->description('Pengaturan urutan dan status unit organisasi.')
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
                            'Unit aktif dapat ditampilkan pada website publik.'
                        ),

                ])
                ->columns(2),

        ]);
    }

    /**
     * Mengambil pilihan unit induk.
     * Unit yang sedang diedit dan seluruh turunannya
     * tidak boleh dipilih sebagai induk.
     */
    private static function getParentOptions(
        ?OrganizationUnit $record
    ): array {
        $query = OrganizationUnit::query()
            ->orderBy('sort_order')
            ->orderBy('name');

        if ($record) {
            $excludedIds = [
                $record->getKey(),
                ...self::getDescendantIds($record),
            ];

            $query->whereNotIn('id', $excludedIds);
        }

        return $query->pluck('name', 'id')->all();
    }

    /**
     * Mengambil seluruh ID unit turunan secara bertingkat.
     */
    private static function getDescendantIds(
        OrganizationUnit $unit
    ): array {
        $excludedIds = [];
        $parentIds = [$unit->getKey()];

        while (! empty($parentIds)) {
            $children = OrganizationUnit::query()
                ->whereIn('parent_id', $parentIds)
                ->pluck('id')
                ->all();

            if (empty($children)) {
                break;
            }

            $excludedIds = array_merge(
                $excludedIds,
                $children
            );

            $parentIds = $children;
        }

        return $excludedIds;
    }
}
