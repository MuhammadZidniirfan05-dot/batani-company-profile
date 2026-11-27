<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Models\PublicationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PublicationController extends Controller
{
    /**
     * Menampilkan daftar publikasi dan kajian.
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

        $query = Publication::published()
            ->with('category:id,name,slug');

        // Pencarian publikasi.
        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query
                    ->where('title', 'like', '%' . $search . '%')
                    ->orWhere('abstract', 'like', '%' . $search . '%')
                    ->orWhere('authors', 'like', '%' . $search . '%')
                    ->orWhere('publisher', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        // Filter kategori publikasi.
        if ($category !== '') {
            $query->whereHas(
                'category',
                function ($query) use ($category) {
                    $query->where('slug', $category)
                        ->where('is_active', true);
                }
            );
        }

        // Pagination.
        $publications = $query
            ->orderByRaw(
                'COALESCE(published_at, created_at) DESC'
            )
            ->orderByDesc('id')
            ->paginate(9, [
                'id',
                'category_id',
                'title',
                'slug',
                'abstract',
                'authors',
                'publisher',
                'cover_image',
                'publication_date',
                'published_at',
                'views_count',
                'downloads_count',
            ])
            ->withQueryString();

        // Kategori publikasi yang aktif.
        $categories = PublicationCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
            ]);

        return Inertia::render(
            'Publications/Research/Index',
            [
                'title' => 'Publikasi dan Kajian',
                'publications' => $publications,
                'categories' => $categories,

                'filters' => [
                    'search' => $search,
                    'category' => $category,
                ],
            ]
        );
    }

    /**
     * Menampilkan detail publikasi.
     */
    public function show(string $slug): Response
    {
        $publication = Publication::published()
            ->with('category:id,name,slug')
            ->where('slug', $slug)
            ->firstOrFail([
                'id',
                'category_id',
                'title',
                'slug',
                'abstract',
                'content',
                'authors',
                'publisher',
                'publication_date',
                'cover_image',
                'document_path',
                'published_at',
                'seo_title',
                'seo_description',
                'views_count',
                'downloads_count',
                'created_at',
            ]);

        // Menambah jumlah kunjungan.
        $publication->increment('views_count');

        // Memeriksa ketersediaan dokumen.
        $hasDocument = $this->documentAvailable(
            $publication->document_path
        );

        // Tidak mengirim lokasi penyimpanan privat ke frontend.
        $publication->makeHidden('document_path');

        // Mengambil publikasi terkait.
        $relatedPublications = Publication::published()
            ->with('category:id,name,slug')
            ->where('id', '!=', $publication->id)
            ->when(
                $publication->category_id !== null,
                function ($query) use ($publication) {
                    $query->where(
                        'category_id',
                        $publication->category_id
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
                'abstract',
                'authors',
                'cover_image',
                'publication_date',
            ]);

        return Inertia::render(
            'Publications/Research/Show',
            [
                'title' => $publication->title,

                'publication' => $publication,

                'relatedPublications' => $relatedPublications,

                'hasDocument' => $hasDocument,

                'downloadUrl' => $hasDocument
                    ? route(
                        'publications.download',
                        ['slug' => $publication->slug]
                    )
                    : null,

                'seo' => [
                    'title' => $publication->seo_title
                        ?: $publication->title,

                    'description' =>
                        $publication->seo_description
                        ?: $publication->abstract,
                ],
            ]
        );
    }

    /**
     * Mengunduh dokumen publikasi yang sudah tayang.
     */
    public function download(string $slug): StreamedResponse
    {
        $publication = Publication::published()
            ->where('slug', $slug)
            ->firstOrFail();

        abort_unless(
            $this->documentAvailable(
                $publication->document_path
            ),
            404,
            'Dokumen publikasi tidak tersedia.'
        );

        $fileName = (
            Str::slug($publication->title)
            ?: 'publikasi-' . $publication->id
        ) . '.pdf';

        // Menyiapkan respons unduhan.
        $response = Storage::disk('local')->download(
            $publication->document_path,
            $fileName,
            [
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'private, no-store',
                'X-Content-Type-Options' => 'nosniff',
            ]
        );

        // Menghitung permintaan unduhan.
        $publication->increment('downloads_count');

        return $response;
    }

    /**
     * Memastikan dokumen berada pada direktori yang benar.
     */
    private function documentAvailable(?string $path): bool
    {
        if (blank($path)) {
            return false;
        }

        if (
            str_contains($path, '\\') ||
            $path !== 'publications/documents/' . basename($path) ||
            strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== 'pdf'
        ) {
            return false;
        }

        return Storage::disk('local')->exists($path);
    }
}
