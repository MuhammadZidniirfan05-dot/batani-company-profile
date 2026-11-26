<?php

namespace App\Filament\Resources\SiteSettings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SiteSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('group')
                    ->label('Kelompok')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('key')
                    ->label('Key Pengaturan')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('value')
                    ->label('Nilai')
                    ->limit(45)
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'image' => 'success',
                        'textarea' => 'info',
                        default => 'gray',
                    }),

                IconColumn::make('is_public')
                    ->label('Publik')
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

                SelectFilter::make('group')
                    ->label('Kelompok Pengaturan')
                    ->options([
                        'general' => 'Informasi Umum',
                        'contact' => 'Informasi Kontak',
                        'branding' => 'Identitas Visual',
                        'seo' => 'SEO Website',
                    ]),

                SelectFilter::make('type')
                    ->label('Tipe Data')
                    ->options([
                        'text' => 'Teks',
                        'textarea' => 'Teks Panjang',
                        'email' => 'Email',
                        'phone' => 'Nomor Telepon',
                        'url' => 'URL',
                        'image' => 'Gambar',
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

            ->defaultSort('sort_order', 'asc')

            ->paginated([10, 25, 50])

            ->defaultPaginationPageOption(10);
    }
}
