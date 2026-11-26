<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // ==========================================
            // INFORMASI PENGIRIM
            // ==========================================

            Section::make('Informasi Pengirim')
                ->description('Informasi pengunjung yang mengirimkan pesan.')
                ->schema([

                    TextInput::make('name')
                        ->label('Nama Pengirim')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('email')
                        ->label('Email Pengirim')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('phone')
                        ->label('Nomor Telepon')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('subject')
                        ->label('Subjek Pesan')
                        ->disabled()
                        ->dehydrated(false),

                    Textarea::make('message')
                        ->label('Isi Pesan')
                        ->rows(8)
                        ->disabled()
                        ->dehydrated(false)
                        ->columnSpanFull(),

                ])
                ->columns(2),

            // ==========================================
            // PENANGANAN PESAN
            // ==========================================

            Section::make('Penanganan Pesan')
                ->description('Pengaturan status dan tindak lanjut pesan.')
                ->schema([

                    Select::make('status')
                        ->label('Status Penanganan')
                        ->options([
                            'new' => 'Baru',
                            'read' => 'Dibaca',
                            'processing' => 'Sedang Diproses',
                            'completed' => 'Selesai',
                        ])
                        ->required()
                        ->native(false)
                        ->live()
                        ->afterStateUpdated(
                            function (
                                $state,
                                Get $get,
                                Set $set
                            ): void {
                                if (
                                    $state !== 'new' &&
                                    blank($get('read_at'))
                                ) {
                                    $set(
                                        'read_at',
                                        now()->toDateTimeString()
                                    );
                                }
                            }
                        ),

                    DateTimePicker::make('read_at')
                        ->label('Waktu Pertama Dibaca')
                        ->native(false)
                        ->seconds(false)
                        ->displayFormat('d/m/Y H:i')
                        ->disabled()
                        ->dehydrated()
                        ->helperText(
                            'Waktu ketika pesan pertama kali ditandai telah dibaca.'
                        ),

                    Textarea::make('admin_notes')
                        ->label('Catatan Administrator')
                        ->rows(5)
                        ->maxLength(5000)
                        ->placeholder(
                            'Tuliskan catatan tindak lanjut pesan...'
                        )
                        ->columnSpanFull()
                        ->helperText(
                            'Catatan ini hanya digunakan secara internal dan tidak ditampilkan kepada pengunjung.'
                        ),

                ])
                ->columns(2),

        ]);
    }
}
