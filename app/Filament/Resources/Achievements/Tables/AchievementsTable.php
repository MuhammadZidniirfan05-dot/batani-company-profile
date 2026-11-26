<?php

namespace App\Filament\Resources\Achievements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AchievementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('image')
                    ->label('Dokumentasi')
                    ->disk('public')
                    ->square(),

                TextColumn::make('title')
                    ->label('Nama Pencapaian')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('issuer')
                    ->label('Pemberi Penghargaan')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('achieved_at')
                    ->label('Tanggal Pencapaian')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            ->filters([

                TernaryFilter::make('is_active')
                    ->label('Status Pencapaian')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif')
                    ->placeholder('Semua Pencapaian'),

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

            ->defaultSort('sort_order', 'asc')

            ->paginated([10, 25, 50])

            ->defaultPaginationPageOption(10);
    }
}
