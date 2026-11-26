<?php

namespace App\Filament\Resources\Pages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('title')
                    ->label('Judul Halaman')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('page_key')
                    ->label('Jenis Halaman')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'about' => 'Tentang Kami',
                            'vision-mission' => 'Visi dan Misi',
                            'company-goals' => 'Tujuan Perusahaan',
                            default => ucfirst($state),
                        }
                    ),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable(),

                ImageColumn::make('featured_image')
                    ->label('Gambar')
                    ->disk('public')
                    ->square(),

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
                    ->placeholder('Belum dijadwalkan'),

                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            ->filters([

                SelectFilter::make('page_key')
                    ->label('Jenis Halaman')
                    ->options([
                        'about' => 'Tentang Kami',
                        'vision-mission' => 'Visi dan Misi',
                        'company-goals' => 'Tujuan Perusahaan',
                    ]),

                SelectFilter::make('status')
                    ->label('Status Halaman')
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
