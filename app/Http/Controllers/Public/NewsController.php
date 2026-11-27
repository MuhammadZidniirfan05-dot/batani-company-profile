<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    /**
     * Menampilkan daftar berita publik.
     */
    public function index(Request $request): Response
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:255'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $search = trim($validated['search'] ?? '');
        $category = $validated['category'] ?? '';

        // Mengambil berita yang sudah layak dipublikasikan.
        $query = News::published()
            ->with('category:id,name,slug');

        // Pencarian berita.
        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('excerpt', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan kategori aktif.
        if ($category !== '') {
            $query->whereHas(
                'category',
                function ($query) use ($category) {
                    $query->where('slug', $category)
                        ->where('is_active', true);
                }
            );
        }

        // Pagination berita.
        $news = $query
            ->orderByRaw(
                'COALESCE(published_at, created_at) DESC'
            )
            ->orderByDesc('id')
            ->paginate(9, [
                'id',
                'category_id',
                'title',
                'slug',
                'excerpt',
                'featured_image',
                'published_at',
                'views_count',
                'created_at',
            ])
            ->withQueryString();

        // Mengambil kategori berita yang aktif.
        $categories = NewsCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
            ]);

        return Inertia::render(
            'Publications/News/Index',
            [
                'title' => 'Berita',
                'news' => $news,
                'categories' => $categories,

                'filters' => [
                    'search' => $search,
                    'category' => $category,
                ],
            ]
        );
    }

    /**
     * Menampilkan detail berita berdasarkan slug.
     */
    public function show(string $slug): Response
    {
        // Hanya berita yang sudah boleh ditampilkan
        // kepada pengunjung yang dapat diakses.
        $news = News::published()
            ->with([
                'category:id,name,slug',
                'author:id,name',
            ])
            ->where('slug', $slug)
            ->firstOrFail([
                'id',
                'category_id',
                'user_id',
                'title',
                'slug',
                'excerpt',
                'content',
                'featured_image',
                'published_at',
                'seo_title',
                'seo_description',
                'views_count',
                'created_at',
                'updated_at',
            ]);

        // Menambah jumlah kunjungan berita.
        $news->increment('views_count');

        // Mengambil berita terkait.
        $relatedNews = News::published()
            ->with('category:id,name,slug')
            ->where('id', '!=', $news->id)
            ->when(
                $news->category_id !== null,
                function ($query) use ($news) {
                    $query->where(
                        'category_id',
                        $news->category_id
                    );
                }
            )
            ->orderByRaw(
                'COALESCE(published_at, created_at) DESC'
            )
            ->limit(3)
            ->get([
                'id',
                'category_id',
                'title',
                'slug',
                'excerpt',
                'featured_image',
                'published_at',
                'created_at',
            ]);

        return Inertia::render(
            'Publications/News/Show',
            [
                'title' => $news->title,
                'news' => $news,
                'relatedNews' => $relatedNews,

                'seo' => [
                    'title' => $news->seo_title
                        ?: $news->title,

                    'description' => $news->seo_description
                        ?: $news->excerpt,
                ],
            ]
        );
    }
}
