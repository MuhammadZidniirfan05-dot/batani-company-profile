<?php

namespace App\Filament\Resources\SocialLinks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SocialLinksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('platform')
                    ->label('Platform')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'instagram' => 'Instagram',
                            'facebook' => 'Facebook',
                            'linkedin' => 'LinkedIn',
                            'youtube' => 'YouTube',
                            'tiktok' => 'TikTok',
                            'x' => 'X (Twitter)',
                            'threads' => 'Threads',
                            'other' => 'Lainnya',
                            default => ucfirst($state),
                        }
                    ),

                TextColumn::make('label')
                    ->label('Nama Akun')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('url')
                    ->label('URL')
                    ->searchable()
                    ->copyable()
                    ->limit(40),

                TextColumn::make('icon')
                    ->label('Ikon')
                    ->placeholder('-')
                    ->toggleable(),

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

                SelectFilter::make('platform')
                    ->label('Platform')
                    ->options([
                        'instagram' => 'Instagram',
                        'facebook' => 'Facebook',
                        'linkedin' => 'LinkedIn',
                        'youtube' => 'YouTube',
                        'tiktok' => 'TikTok',
                        'x' => 'X (Twitter)',
                        'threads' => 'Threads',
                        'other' => 'Lainnya',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif')
                    ->placeholder('Semua Status'),

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
