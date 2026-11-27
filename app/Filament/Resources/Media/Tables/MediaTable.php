<?php

namespace App\Filament\Resources\Media\Tables;

use App\Models\Media;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('preview')
                    ->label('Preview')
                    ->disk('public')
                    ->square()
                    ->state(
                        fn (Media $record): ?string =>
                            $record->file_type === 'image'
                            && $record->disk === 'public'
                                ? $record->file_path
                                : null
                    ),

                TextColumn::make('name')
                    ->label('Nama Media')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('file_name')
                    ->label('Nama File')
                    ->searchable()
                    ->copyable()
                    ->limit(35),

                TextColumn::make('file_type')
                    ->label('Jenis File')
                    ->badge()
                    ->color(
                        fn (?string $state): string =>
                            match ($state) {
                                'image' => 'success',
                                'document' => 'info',
                                default => 'gray',
                            }
                    )
                    ->formatStateUsing(
                        fn (?string $state): string =>
                            match ($state) {
                                'image' => 'Gambar',
                                'document' => 'Dokumen',
                                'video' => 'Video',
                                'audio' => 'Audio',
                                default => 'Lainnya',
                            }
                    ),

                TextColumn::make('mime_type')
                    ->label('Format File')
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('file_size')
                    ->label('Ukuran File')
                    ->formatStateUsing(
                        function ($state): string {

                            $bytes = (float) $state;

                            if ($bytes >= 1048576) {
                                return number_format(
                                    $bytes / 1048576,
                                    2
                                ) . ' MB';
                            }

                            if ($bytes >= 1024) {
                                return number_format(
                                    $bytes / 1024,
                                    2
                                ) . ' KB';
                            }

                            return number_format($bytes)
                                . ' Byte';
                        }
                    )
                    ->sortable(),

                TextColumn::make('uploader.name')
                    ->label('Diunggah Oleh')
                    ->placeholder('Tidak diketahui'),

                TextColumn::make('created_at')
                    ->label('Tanggal Upload')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])

            ->filters([

                SelectFilter::make('file_type')
                    ->label('Jenis Media')
                    ->options([
                        'image' => 'Gambar',
                        'document' => 'Dokumen',
                        'video' => 'Video',
                        'audio' => 'Audio',
                        'other' => 'Lainnya',
                    ]),

            ])

            ->recordActions([

                EditAction::make()
                    ->label('Edit Metadata'),

                DeleteAction::make()
                    ->label('Hapus Entri')
                    ->modalHeading('Hapus entri media?')
                    ->modalDescription(
                        'Entri akan dihapus dari Media Library, tetapi file fisik tetap disimpan untuk menjaga konten yang mungkin masih menggunakannya.'
                    )
                    ->requiresConfirmation(),

            ])

            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10);
    }
}
