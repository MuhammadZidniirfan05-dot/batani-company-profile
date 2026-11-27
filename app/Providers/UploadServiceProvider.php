<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UploadServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureTemporaryUploads();
    }

    private function configureTemporaryUploads(): void
    {
        config([

            // Penyimpanan sementara privat
            'livewire.temporary_file_upload.disk' => 'local',

            // Direktori upload sementara
            'livewire.temporary_file_upload.directory' => 'livewire-tmp',

            // Validasi format dan ukuran file
            'livewire.temporary_file_upload.rules' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,gif,pdf',
                'extensions:jpg,jpeg,png,webp,gif,pdf',
                'max:20480',
            ],

        ]);
    }
}
