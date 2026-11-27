<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\CompanyValue;
use App\Models\ItProject;
use App\Models\News;
use App\Models\OrganizationUnit;
use App\Models\Page;
use App\Models\Publication;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    private const LIMIT_PER_TYPE = 6;

    /**
     * Pencarian global website BATANI.
     */
    public function index(Request $request): Response
    {
        $validator = Validator::make($request->query(), [
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        abort_if($validator->fails(), 422, 'Kata kunci tidak valid.');

        $keyword = trim(
            $validator->validated()['q'] ?? ''
        );

        $results = collect();

        if (mb_strlen($keyword) >= 2) {

            $results = $results
                ->concat($this->searchPages($keyword))
                ->concat($this->searchNews($keyword))
                ->concat($this->searchPublications($keyword))
                ->concat($this->searchProjects($keyword))
                ->concat($this->searchTeamMembers($keyword))
                ->concat($this->searchOrganizationUnits($keyword))
                ->concat($this->searchCompanyValues($keyword))
                ->concat($this->searchAchievements($keyword))
                ->values();
        }

        return Inertia::render('Search', [
            'title' => 'Pencarian',

            'keyword' => $keyword,

            'results' => $results,

            'totalShown' => $results->count(),

            'limitPerType' => self::LIMIT_PER_TYPE,

            'seo' => [
                'title' => 'Pencarian BATANI',
                'description' =>
                    'Temukan informasi, berita, publikasi, kajian, dan proyek BATANI.',
            ],
        ]);
    }

    /**
     * Pencarian halaman perusahaan.
     */
    private function searchPages(
        string $keyword
    ): Collection {
        $routes = [
            'about' => 'company.about',
            'vision-mission' => 'company.vision-mission',
            'company-goals' => 'company.company-goals',
        ];

        $query = Page::published()
            ->whereIn('page_key', array_keys($routes));

        return $this->search(
            $query,
            $keyword,
            ['title', 'excerpt', 'content']
        )->map(fn ($page) => [

            'type' => 'page',

            'typeLabel' => 'Halaman Perusahaan',

            'title' => $page->title,

            'description' => $this->excerpt(
                $page->excerpt ?: $page->content
            ),

            'url' => route(
                $routes[$page->page_key],
                [],
                false
            ),

        ]);
    }

    /**
     * Pencarian berita.
     */
    private function searchNews(
        string $keyword
    ): Collection {
        return $this->search(
            News::published(),
            $keyword,
            ['title', 'excerpt', 'content']
        )->map(fn ($news) => [

            'type' => 'news',

            'typeLabel' => 'Berita',

            'title' => $news->title,

            'description' => $this->excerpt(
                $news->excerpt ?: $news->content
            ),

            'url' => route(
                'news.show',
                ['slug' => $news->slug],
                false
            ),

        ]);
    }

    /**
     * Pencarian publikasi dan kajian.
     */
    private function searchPublications(
        string $keyword
    ): Collection {
        return $this->search(
            Publication::published(),
            $keyword,
            [
                'title',
                'abstract',
                'content',
                'authors',
                'publisher',
            ]
        )->map(fn ($publication) => [

            'type' => 'publication',

            'typeLabel' => 'Publikasi dan Kajian',

            'title' => $publication->title,

            'description' => $this->excerpt(
                $publication->abstract
                    ?: $publication->content
            ),

            'url' => route(
                'publications.show',
                ['slug' => $publication->slug],
                false
            ),

        ]);
    }

    /**
     * Pencarian proyek IT.
     */
    private function searchProjects(
        string $keyword
    ): Collection {
        return $this->search(
            ItProject::published(),
            $keyword,
            [
                'title',
                'excerpt',
                'description',
                'client',
            ],
            true
        )->map(fn ($project) => [

            'type' => 'project',

            'typeLabel' => 'Project IT',

            'title' => $project->title,

            'description' => $this->excerpt(
                $project->excerpt
                    ?: $project->description
            ),

            'url' => route(
                'projects.show',
                ['slug' => $project->slug],
                false
            ),

        ]);
    }

    /**
     * Pencarian Direktur Eksekutif dan Tim Ahli.
     */
    private function searchTeamMembers(
        string $keyword
    ): Collection {
        $query = TeamMember::active()
            ->whereIn('member_type', [
                'executive_director',
                'expert',
            ]);

        return $this->search(
            $query,
            $keyword,
            [
                'name',
                'position',
                'expertise',
                'biography',
            ]
        )->map(fn ($member) => [

            'type' => 'team',

            'typeLabel' =>
                $member->member_type === 'executive_director'
                    ? 'Direktur Eksekutif'
                    : 'Tim Ahli',

            'title' => $member->name,

            'description' => $this->excerpt(
                $member->expertise
                    ?: $member->position
                    ?: $member->biography
            ),

            'url' => route(
                $member->member_type === 'executive_director'
                    ? 'company.executive-director'
                    : 'company.experts',
                [],
                false
            ),

        ]);
    }

    /**
     * Pencarian struktur organisasi.
     */
    private function searchOrganizationUnits(
        string $keyword
    ): Collection {
        return $this->search(
            OrganizationUnit::active(),
            $keyword,
            [
                'name',
                'position',
                'leader_name',
                'description',
            ]
        )->map(fn ($unit) => [

            'type' => 'organization',

            'typeLabel' => 'Struktur Organisasi',

            'title' => $unit->name,

            'description' => $this->excerpt(
                $unit->description ?: $unit->position
            ),

            'url' => route(
                'company.organization-structure',
                [],
                false
            ),

        ]);
    }

    /**
     * Pencarian nilai perusahaan.
     */
    private function searchCompanyValues(
        string $keyword
    ): Collection {
        return $this->search(
            CompanyValue::active(),
            $keyword,
            ['title', 'description']
        )->map(fn ($value) => [

            'type' => 'company_value',

            'typeLabel' => 'Nilai Perusahaan',

            'title' => $value->title,

            'description' => $this->excerpt(
                $value->description
            ),

            'url' => route(
                'company.company-values',
                [],
                false
            ),

        ]);
    }

    /**
     * Pencarian pencapaian perusahaan.
     */
    private function searchAchievements(
        string $keyword
    ): Collection {
        return $this->search(
            Achievement::active(),
            $keyword,
            ['title', 'description', 'issuer']
        )->map(fn ($achievement) => [

            'type' => 'achievement',

            'typeLabel' => 'Pencapaian',

            'title' => $achievement->title,

            'description' => $this->excerpt(
                $achievement->description
                    ?: $achievement->issuer
            ),

            'url' => route(
                'company.achievements',
                [],
                false
            ),

        ]);
    }

    /**
     * Query pencarian yang dapat digunakan kembali.
     */
    private function search(
        Builder $query,
        string $keyword,
        array $columns,
        bool $searchTechnologies = false
    ): Collection {

        $pattern = '%' . str_replace(
            ['!', '%', '_'],
            ['!!', '!%', '!_'],
            $keyword
        ) . '%';

        return $query
            ->where(function (Builder $query) use (
                $columns,
                $pattern,
                $searchTechnologies
            ) {

                foreach ($columns as $column) {
                    $query->orWhereRaw(
                        "`{$column}` LIKE ? ESCAPE '!'",
                        [$pattern]
                    );
                }

                if ($searchTechnologies) {
                    $query->orWhereRaw(
                        "CAST(technologies AS CHAR) LIKE ? ESCAPE '!'",
                        [$pattern]
                    );
                }

            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(self::LIMIT_PER_TYPE)
            ->get();
    }

    /**
     * Menghasilkan ringkasan teks tanpa markup HTML.
     */
    private function excerpt(?string $content): string
    {
        if (blank($content)) {
            return '';
        }

        $text = strip_tags(
            html_entity_decode(
                $content,
                ENT_QUOTES | ENT_HTML5,
                'UTF-8'
            )
        );

        return Str::limit(
            Str::squish($text),
            180
        );
    }
}
