<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('Nama Pengguna')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('role')
                    ->label('Hak Akses')
                    ->badge()
                    ->color(
                        fn (string $state): string => match ($state) {
                            User::ROLE_SUPER_ADMIN => 'danger',
                            User::ROLE_EDITOR => 'info',
                            default => 'gray',
                        }
                    )
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            User::ROLE_SUPER_ADMIN => 'Super Admin',
                            User::ROLE_EDITOR => 'Editor',
                            User::ROLE_USER => 'User',
                            default => ucfirst($state),
                        }
                    )
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(
                        isToggledHiddenByDefault: true
                    ),

            ])

            ->filters([

                SelectFilter::make('role')
                    ->label('Hak Akses')
                    ->options([
                        User::ROLE_SUPER_ADMIN => 'Super Admin',
                        User::ROLE_EDITOR => 'Editor',
                        User::ROLE_USER => 'User',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Status Akun')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif')
                    ->placeholder('Semua Status'),

            ])

            ->recordActions([

                EditAction::make()
                    ->label('Kelola Akun'),

            ])

            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10);
    }
}
