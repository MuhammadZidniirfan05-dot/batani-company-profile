<?php

namespace App\Filament\Resources\Media\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
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

                ImageColumn::make('file_path')
                    ->label('Preview')
                    ->disk('public')
                    ->square()
                    ->visible(
                        fn ($record): bool =>
                            $record->file_type === 'image'
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
                    ->color(fn (string $state): string => match ($state) {
                        'image' => 'success',
                        'document' => 'info',
                        'video' => 'warning',
                        'audio' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'image' => 'Gambar',
                            'document' => 'Dokumen',
                            'video' => 'Video',
                            'audio' => 'Audio',
                            'other' => 'Lainnya',
                            default => ucfirst($state),
                        }
                    ),

                TextColumn::make('mime_type')
                    ->label('Format File')
                    ->toggleable(),

                TextColumn::make('file_size')
                    ->label('Ukuran')
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

                            return number_format($bytes) . ' Byte';
                        }
                    )
                    ->sortable(),

                TextColumn::make('uploader.name')
                    ->label('Diunggah Oleh')
                    ->placeholder('Tidak diketahui')
                    ->sortable(),

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
                    ->label('Edit'),

                DeleteAction::make()
                    ->label('Hapus')
                    ->requiresConfirmation(),

            ])

            ->toolbarActions([

                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus yang Dipilih')
                        ->requiresConfirmation(),
                ]),

            ])

            ->defaultSort('created_at', 'desc')

            ->paginated([10, 25, 50])

            ->defaultPaginationPageOption(10);
    }
}
