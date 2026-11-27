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
use App\Policies\AdminOnlyPolicy;
use App\Policies\ContactMessagePolicy;
use App\Policies\ContentPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // MODUL PROFIL PERUSAHAAN

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

        // MODUL BERITA

        Gate::policy(
            NewsCategory::class,
            ContentPolicy::class
        );

        Gate::policy(
            News::class,
            ContentPolicy::class
        );

        // MODUL PUBLIKASI DAN KAJIAN

        Gate::policy(
            PublicationCategory::class,
            ContentPolicy::class
        );

        Gate::policy(
            Publication::class,
            ContentPolicy::class
        );

        // MODUL PROJECT IT

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
}
