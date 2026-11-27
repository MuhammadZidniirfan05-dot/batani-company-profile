<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ItProject;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ItProjectController extends Controller
{
    /**
     * Menampilkan daftar portofolio Project IT.
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

        // Mengambil proyek yang sudah boleh dipublikasikan.
        $query = ItProject::published()
            ->with('category:id,name,slug');

        // Pencarian proyek.
        if ($search !== '') {
            $keyword = '%' . $search . '%';

            $query->where(function ($query) use ($keyword) {
                $query
                    ->where('title', 'like', $keyword)
                    ->orWhere('excerpt', 'like', $keyword)
                    ->orWhere('description', 'like', $keyword)
                    ->orWhere('client', 'like', $keyword)
                    ->orWhereRaw(
                        'CAST(technologies AS CHAR) LIKE ?',
                        [$keyword]
                    );
            });
        }

        // Filter berdasarkan kategori aktif.
        if ($category !== '') {
            $query->whereHas(
                'category',
                function ($query) use ($category) {
                    $query
                        ->where('slug', $category)
                        ->where('is_active', true);
                }
            );
        }

        // Pagination proyek.
        $projects = $query
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
                'client',
                'thumbnail',
                'technologies',
                'project_url',
                'demo_url',
                'started_at',
                'completed_at',
                'published_at',
                'created_at',
            ])
            ->withQueryString();

        // Mengambil kategori proyek yang aktif.
        $categories = ProjectCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
            ]);

        return Inertia::render(
            'Publications/ITProjects/Index',
            [
                'title' => 'Project IT',

                'projects' => $projects,

                'categories' => $categories,

                'filters' => [
                    'search' => $search,
                    'category' => $category,
                ],
            ]
        );
    }

    /**
     * Menampilkan detail proyek berdasarkan slug.
     */
    public function show(string $slug): Response
    {
        $project = ItProject::published()
            ->with('category:id,name,slug')
            ->where('slug', $slug)
            ->firstOrFail([
                'id',
                'category_id',
                'title',
                'slug',
                'excerpt',
                'description',
                'client',
                'thumbnail',
                'gallery',
                'technologies',
                'project_url',
                'demo_url',
                'started_at',
                'completed_at',
                'published_at',
                'seo_title',
                'seo_description',
                'created_at',
            ]);

        // Mengambil proyek terkait.
        $relatedProjects = ItProject::published()
            ->with('category:id,name,slug')
            ->where('id', '!=', $project->id)
            ->when(
                $project->category_id !== null,
                function ($query) use ($project) {
                    $query->where(
                        'category_id',
                        $project->category_id
                    );
                }
            )
            ->orderByRaw(
                'COALESCE(published_at, created_at) DESC'
            )
            ->orderByDesc('id')
            ->limit(3)
            ->get([
                'id',
                'category_id',
                'title',
                'slug',
                'excerpt',
                'client',
                'thumbnail',
                'technologies',
                'completed_at',
            ]);

        return Inertia::render(
            'Publications/ITProjects/Show',
            [
                'title' => $project->title,

                'project' => $project,

                'relatedProjects' => $relatedProjects,

                'seo' => [
                    'title' => $project->seo_title
                        ?: $project->title,

                    'description' =>
                        $project->seo_description
                        ?: $project->excerpt,
                ],
            ]
        );
    }
}
