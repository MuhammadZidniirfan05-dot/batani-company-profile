<?php

namespace App\Filament\Resources\OrganizationUnits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class OrganizationUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('Nama Unit')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('parent.name')
                    ->label('Unit Induk')
                    ->placeholder('Unit Utama')
                    ->searchable(),

                TextColumn::make('position')
                    ->label('Jabatan / Posisi')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('leader_name')
                    ->label('Pimpinan Unit')
                    ->searchable()
                    ->placeholder('-'),

                ImageColumn::make('photo')
                    ->label('Foto')
                    ->disk('public')
                    ->circular(),

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
                    ->label('Status Unit')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif')
                    ->placeholder('Semua Unit'),

                TernaryFilter::make('parent_id')
                    ->label('Kedudukan Unit')
                    ->nullable()
                    ->trueLabel('Unit Turunan')
                    ->falseLabel('Unit Utama')
                    ->placeholder('Semua Kedudukan'),

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
