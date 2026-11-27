<?php

namespace App\Providers;

use App\Models\Achievement;
use App\Models\CompanyValue;
use App\Models\ContactMessage;
use App\Models\ItProject;
use App\Models\Media;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\OrganizationUnit;
use App\Models\Page;
use App\Models\ProjectCategory;
use App\Models\Publication;
use App\Models\PublicationCategory;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Models\TeamMember;
use App\Models\User;

use App\Observers\HtmlContentObserver;

use App\Policies\AdminOnlyPolicy;
use App\Policies\ContactMessagePolicy;
use App\Policies\ContentPolicy;
use App\Policies\UserPolicy;

use App\Services\HtmlSanitizerService;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registrasi application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            HtmlSanitizerService::class
        );
    }

    /**
     * Bootstrap application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        $this->registerObservers();

        $this->configureSecureUploads();
    }

    /**
     * Registrasi Laravel Policies.
     */
    private function registerPolicies(): void
    {
        // PROFIL PERUSAHAAN

        Gate::policy(
            Page::class,
            ContentPolicy::class
        );

        Gate::policy(
            TeamMember::class,
            ContentPolicy::class
        );

        Gate::policy(
            OrganizationUnit::class,
            ContentPolicy::class
        );

        Gate::policy(
            CompanyValue::class,
            ContentPolicy::class
        );

        Gate::policy(
            Achievement::class,
            ContentPolicy::class
        );

        // BERITA

        Gate::policy(
            NewsCategory::class,
            ContentPolicy::class
        );

        Gate::policy(
            News::class,
            ContentPolicy::class
        );

        // PUBLIKASI DAN KAJIAN

        Gate::policy(
            PublicationCategory::class,
            ContentPolicy::class
        );

        Gate::policy(
            Publication::class,
            ContentPolicy::class
        );

        // PROJECT IT

        Gate::policy(
            ProjectCategory::class,
            ContentPolicy::class
        );

        Gate::policy(
            ItProject::class,
            ContentPolicy::class
        );

        // MEDIA LIBRARY

        Gate::policy(
            Media::class,
            ContentPolicy::class
        );

        // PENGATURAN WEBSITE

        Gate::policy(
            SiteSetting::class,
            AdminOnlyPolicy::class
        );

        Gate::policy(
            SocialLink::class,
            AdminOnlyPolicy::class
        );

        // PESAN MASUK

        Gate::policy(
            ContactMessage::class,
            ContactMessagePolicy::class
        );

        // MANAJEMEN PENGGUNA

        Gate::policy(
            User::class,
            UserPolicy::class
        );
    }

    /**
     * Registrasi Observer sanitasi konten HTML.
     */
    private function registerObservers(): void
    {
        News::observe(
            HtmlContentObserver::class
        );

        Publication::observe(
            HtmlContentObserver::class
        );

        ItProject::observe(
            HtmlContentObserver::class
        );

        Page::observe(
            HtmlContentObserver::class
        );

        TeamMember::observe(
            HtmlContentObserver::class
        );

        OrganizationUnit::observe(
            HtmlContentObserver::class
        );

        Achievement::observe(
            HtmlContentObserver::class
        );
    }

    /**
     * Konfigurasi keamanan upload secara global.
     */
    private function configureSecureUploads(): void
    {
        // ==========================================
        // KEAMANAN FILE UPLOAD
        // ==========================================

        FileUpload::configureUsing(
            function (FileUpload $component): void {

                $component
                    ->preventFilePathTampering()
                    ->preserveFilenames(false);

            }
        );

        // ==========================================
        // KEAMANAN RICH TEXT EDITOR
        // ==========================================

        RichEditor::configureUsing(
            function (RichEditor $component): void {

                $component
                    ->preventFileAttachmentPathTampering()

                    ->fileAttachmentsAcceptedFileTypes([
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                        'image/gif',
                    ])

                    ->fileAttachmentsMaxSize(5120);

            }
        );
    }
}
