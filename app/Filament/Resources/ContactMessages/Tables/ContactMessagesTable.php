<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('Nama Pengirim')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('subject')
                    ->label('Subjek Pesan')
                    ->searchable()
                    ->limit(45)
                    ->wrap(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'danger',
                        'read' => 'info',
                        'processing' => 'warning',
                        'completed' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'new' => 'Baru',
                            'read' => 'Dibaca',
                            'processing' => 'Diproses',
                            'completed' => 'Selesai',
                            default => ucfirst($state),
                        }
                    )
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Masuk')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('read_at')
                    ->label('Waktu Dibaca')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum dibaca')
                    ->toggleable(),

            ])

            ->filters([

                SelectFilter::make('status')
                    ->label('Status Pesan')
                    ->options([
                        'new' => 'Baru',
                        'read' => 'Dibaca',
                        'processing' => 'Sedang Diproses',
                        'completed' => 'Selesai',
                    ]),

            ])

            ->recordActions([

                EditAction::make()
                    ->label('Kelola Pesan'),

            ])

            ->defaultSort('created_at', 'desc')

            ->paginated([10, 25, 50])

            ->defaultPaginationPageOption(10);
    }
}
