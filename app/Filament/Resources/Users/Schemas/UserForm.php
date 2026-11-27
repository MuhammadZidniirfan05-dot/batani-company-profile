<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informasi Pengguna')
                ->description('Identitas akun pengguna BATANI.')
                ->schema([

                    TextInput::make('name')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('Alamat Email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                ])
                ->columns(2),

            Section::make('Keamanan Akun')
                ->description('Pengaturan password pengguna.')
                ->schema([

                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->revealable()
                        ->required(
                            fn (string $operation): bool =>
                                $operation === 'create'
                        )
                        ->minLength(12)
                        ->maxLength(72)
                        ->dehydrated(
                            fn ($state): bool => filled($state)
                        )
                        ->live(onBlur: true)
                        ->helperText(
                            'Minimal 12 karakter. Kosongkan saat mengedit jika password tidak ingin diubah.'
                        ),

                    TextInput::make('password_confirmation')
                        ->label('Konfirmasi Password')
                        ->password()
                        ->revealable()
                        ->required(
                            fn (Get $get): bool =>
                                filled($get('password'))
                        )
                        ->same('password')
                        ->dehydrated(false),

                ])
                ->columns(2),

            Section::make('Hak Akses Pengguna')
                ->description('Pengaturan role dan status akun.')
                ->schema([

                    Select::make('role')
                        ->label('Hak Akses')
                        ->options([
                            User::ROLE_SUPER_ADMIN => 'Super Admin',
                            User::ROLE_EDITOR => 'Editor',
                            User::ROLE_USER => 'User',
                        ])
                        ->default(User::ROLE_USER)
                        ->required()
                        ->native(false)
                        ->disabled(
                            fn (?User $record): bool =>
                                self::isCurrentUser($record)
                        )
                        ->helperText(
                            'Anda tidak dapat mengubah hak akses akun sendiri.'
                        ),

                    Toggle::make('is_active')
                        ->label('Status Akun Aktif')
                        ->default(true)
                        ->disabled(
                            fn (?User $record): bool =>
                                self::isCurrentUser($record)
                        )
                        ->helperText(
                            'Akun nonaktif tidak dapat mengakses dashboard.'
                        ),

                ])
                ->columns(2),

        ]);
    }

    private static function isCurrentUser(
        ?User $record
    ): bool {
        return $record !== null
            && (string) $record->getKey()
                === (string) Filament::auth()->id();
    }
}
