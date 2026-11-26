<?php

namespace App\Filament\Resources\Publications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PublicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('title')
                    ->label('Judul Publikasi')
                    ->searchable()
                    ->sortable()
                    ->limit(45)
                    ->wrap(),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('authors')
                    ->label('Penulis / Tim Penyusun')
                    ->searchable()
                    ->placeholder('Tidak dicantumkan'),

                TextColumn::make('publisher')
                    ->label('Penerbit / Instansi')
                    ->searchable()
                    ->placeholder('Tidak dicantumkan')
                    ->toggleable(),

                ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->disk('public')
                    ->square(),

                TextColumn::make('publication_date')
                    ->label('Tanggal Terbit Dokumen')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'published' => 'Published',
                            'draft' => 'Draft',
                            default => ucfirst($state),
                        }
                    ),

                TextColumn::make('published_at')
                    ->label('Jadwal Tayang di Website')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('Belum dijadwalkan')
                    ->toggleable(),

                TextColumn::make('views_count')
                    ->label('Jumlah Dilihat')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('downloads_count')
                    ->label('Jumlah Diunduh')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('author.name')
                    ->label('Dikelola Oleh')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            ->filters([

                SelectFilter::make('category_id')
                    ->label('Kategori Publikasi')
                    ->relationship('category', 'name'),

                SelectFilter::make('status')
                    ->label('Status Publikasi')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
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
