<?php

namespace App\Filament\Resources\TeamMembers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TeamMembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('photo')
                    ->label('Foto')
                    ->disk('public')
                    ->circular(),

                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('member_type')
                    ->label('Jenis Personel')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'executive_director' => 'success',
                        'expert' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'executive_director' => 'Direktur Eksekutif',
                            'expert' => 'Tim Ahli',
                            default => ucfirst($state),
                        }
                    ),

                TextColumn::make('position')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('expertise')
                    ->label('Bidang Keahlian')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            ->filters([

                SelectFilter::make('member_type')
                    ->label('Jenis Personel')
                    ->options([
                        'executive_director' => 'Direktur Eksekutif',
                        'expert' => 'Tim Ahli',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Status Personel')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif')
                    ->placeholder('Semua Personel'),

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
