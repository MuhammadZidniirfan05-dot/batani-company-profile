<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\CompanyValue;
use App\Models\ItProject;
use App\Models\News;
use App\Models\Page;
use App\Models\Publication;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        // Profil singkat perusahaan
        $about = Page::published()
            ->where('page_key', 'about')
            ->first([
                'title',
                'slug',
                'excerpt',
                'featured_image',
            ]);

        // Berita terbaru
        $latestNews = News::published()
            ->with('category:id,name,slug')
            ->latest('published_at')
            ->latest('created_at')
            ->limit(3)
            ->get([
                'id',
                'category_id',
                'title',
                'slug',
                'excerpt',
                'featured_image',
                'published_at',
            ]);

        // Publikasi dan kajian terbaru
        $latestPublications = Publication::published()
            ->with('category:id,name,slug')
            ->latest('published_at')
            ->latest('created_at')
            ->limit(3)
            ->get([
                'id',
                'category_id',
                'title',
                'slug',
                'abstract',
                'authors',
                'cover_image',
                'publication_date',
            ]);

        // Proyek IT terbaru
        $latestProjects = ItProject::published()
            ->with('category:id,name,slug')
            ->latest('published_at')
            ->latest('created_at')
            ->limit(3)
            ->get([
                'id',
                'category_id',
                'title',
                'slug',
                'excerpt',
                'thumbnail',
                'technologies',
                'client',
            ]);

        // Nilai perusahaan
        $companyValues = CompanyValue::active()
            ->orderBy('sort_order')
            ->limit(6)
            ->get([
                'title',
                'description',
                'icon',
            ]);

        // Pencapaian perusahaan
        $achievements = Achievement::active()
            ->orderBy('sort_order')
            ->limit(6)
            ->get([
                'title',
                'description',
                'issuer',
                'image',
                'achieved_at',
            ]);

        return Inertia::render('Home', [
            'about' => $about,
            'latestNews' => $latestNews,
            'latestPublications' => $latestPublications,
            'latestProjects' => $latestProjects,
            'companyValues' => $companyValues,
            'achievements' => $achievements,
        ]);
    }
}
