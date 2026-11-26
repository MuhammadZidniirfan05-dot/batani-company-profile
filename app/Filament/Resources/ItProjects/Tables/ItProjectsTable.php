<?php

namespace App\Filament\Resources\ItProjects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ItProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('title')
                    ->label('Nama Proyek')
                    ->searchable()
                    ->sortable()
                    ->limit(45)
                    ->wrap(),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('client')
                    ->label('Klien / Instansi')
                    ->searchable()
                    ->placeholder('-'),

                ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->disk('public')
                    ->square(),

                TextColumn::make('technologies')
                    ->label('Teknologi')
                    ->formatStateUsing(
                        fn ($state): string => is_array($state)
                            ? implode(', ', $state)
                            : (string) ($state ?? '-')
                    )
                    ->limit(40)
                    ->toggleable(),

                TextColumn::make('started_at')
                    ->label('Tanggal Mulai')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('completed_at')
                    ->label('Tanggal Selesai')
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
                    ->label('Jadwal Tayang')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('Belum dijadwalkan')
                    ->toggleable(),

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
                    ->label('Kategori Proyek')
                    ->relationship('category', 'name'),

                SelectFilter::make('status')
                    ->label('Status Proyek')
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
