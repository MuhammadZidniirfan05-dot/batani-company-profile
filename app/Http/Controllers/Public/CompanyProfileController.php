<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\CompanyValue;
use App\Models\OrganizationUnit;
use App\Models\Page;
use App\Models\TeamMember;
use Inertia\Inertia;
use Inertia\Response;

class CompanyProfileController extends Controller
{
    /**
     * Halaman Tentang Kami.
     */
    public function about(): Response
    {
        return $this->renderPage(
            'about',
            'Tentang Kami',
            'Company/About'
        );
    }

    /**
     * Halaman Visi dan Misi.
     */
    public function visionMission(): Response
    {
        return $this->renderPage(
            'vision-mission',
            'Visi dan Misi',
            'Company/VisionMission'
        );
    }

    /**
     * Halaman Direktur Eksekutif.
     */
    public function executiveDirector(): Response
    {
        $directors = TeamMember::active()
            ->executiveDirector()
            ->orderBy('sort_order')
            ->get([
                'id',
                'name',
                'slug',
                'position',
                'expertise',
                'biography',
                'photo',
                'linkedin_url',
            ]);

        return Inertia::render(
            'Company/ExecutiveDirector',
            [
                'title' => 'Direktur Eksekutif',
                'directors' => $directors,
            ]
        );
    }

    /**
     * Halaman Tim Ahli.
     */
    public function experts(): Response
    {
        $experts = TeamMember::active()
            ->experts()
            ->orderBy('sort_order')
            ->get([
                'id',
                'name',
                'slug',
                'position',
                'expertise',
                'biography',
                'photo',
                'linkedin_url',
            ]);

        return Inertia::render('Company/ExpertTeam', [
            'title' => 'Tim Ahli',
            'experts' => $experts,
        ]);
    }

    /**
     * Halaman Struktur Organisasi.
     */
    public function organizationStructure(): Response
    {
        $units = OrganizationUnit::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get([
                'id',
                'parent_id',
                'name',
                'slug',
                'position',
                'leader_name',
                'description',
                'photo',
                'sort_order',
            ]);

        return Inertia::render(
            'Company/OrganizationStructure',
            [
                'title' => 'Struktur Organisasi',
                'units' => $units,
            ]
        );
    }

    /**
     * Halaman Tujuan Perusahaan.
     */
    public function companyGoals(): Response
    {
        return $this->renderPage(
            'company-goals',
            'Tujuan Perusahaan',
            'Company/CompanyGoals'
        );
    }

    /**
     * Halaman Nilai Perusahaan.
     */
    public function companyValues(): Response
    {
        $values = CompanyValue::active()
            ->orderBy('sort_order')
            ->get([
                'id',
                'title',
                'slug',
                'description',
                'icon',
            ]);

        return Inertia::render('Company/CompanyValues', [
            'title' => 'Nilai Perusahaan',
            'values' => $values,
        ]);
    }

    /**
     * Halaman Pencapaian Perusahaan.
     */
    public function achievements(): Response
    {
        $achievements = Achievement::active()
            ->orderBy('sort_order')
            ->orderByDesc('achieved_at')
            ->get([
                'id',
                'title',
                'slug',
                'description',
                'issuer',
                'image',
                'achieved_at',
            ]);

        return Inertia::render('Company/Achievements', [
            'title' => 'Pencapaian',
            'achievements' => $achievements,
        ]);
    }

    /**
     * Mengambil konten halaman perusahaan
     * berdasarkan page_key.
     */
    private function renderPage(
        string $pageKey,
        string $title,
        string $component
    ): Response {
        $page = Page::published()
            ->where('page_key', $pageKey)
            ->first([
                'id',
                'page_key',
                'title',
                'slug',
                'excerpt',
                'content',
                'featured_image',
                'seo_title',
                'seo_description',
                'published_at',
            ]);

        return Inertia::render($component, [
            'title' => $page?->title ?? $title,
            'page' => $page,
        ]);
    }
}
