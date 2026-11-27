<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(
    basePath: dirname(__DIR__)
)
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    // ==========================================
    // KONFIGURASI MIDDLEWARE
    // ==========================================

    ->withMiddleware(function (
        Middleware $middleware
    ): void {

        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);

    })

    // ==========================================
    // PENANGANAN ERROR
    // ==========================================

    ->withExceptions(function (
        Exceptions $exceptions
    ): void {

        $exceptions->respond(function (
            Response $response,
            \Throwable $exception,
            Request $request
        ): Response {

            $status = $response->getStatusCode();

            // Mempertahankan debugging saat pengembangan.

            if (app()->environment(['local', 'testing'])) {
                return $response;
            }

            // Status error yang ditangani.

            if (! in_array($status, [
                403,
                404,
                419,
                429,
                500,
                503,
            ], true)) {
                return $response;
            }

            // Tidak mengubah error Filament,
            // Livewire, API dan penyimpanan.

            if (
                $request->is(
                    'admin',
                    'admin/*',
                    'filament',
                    'filament/*',
                    'livewire*',
                    'api',
                    'api/*',
                    '_inertia/*',
                    'storage',
                    'storage/*',
                    'build/*',
                    'up'
                )
                || $request->headers->has('X-Livewire')
                || $request->expectsJson()
            ) {
                return $response;
            }

            // Membatasi handler pada route publik.

            if (
                $request->route() !== null
                && ! $request->routeIs(
                    'home',
                    'company.*',
                    'news.*',
                    'publications.*',
                    'projects.*',
                    'contact.*',
                    'search.*'
                )
            ) {
                return $response;
            }

            // Error pada request Inertia.

            if ($request->header('X-Inertia')) {

                try {

                    $errorResponse = Inertia::render(
                        'ErrorPage',
                        [
                            'status' => $status,

                            'title' => 'Terjadi Kesalahan',

                            'siteSettings' => [],

                            'errors' => (object) [],
                        ]
                    )
                        ->toResponse($request)
                        ->setStatusCode($status);

                    $errorResponse->headers->set(
                        'Cache-Control',
                        'no-store'
                    );

                    $errorResponse->setVary(
                        'X-Inertia',
                        false
                    );

                    if ($response->headers->has('Retry-After')) {
                        $errorResponse->headers->set(
                            'Retry-After',
                            $response->headers->get('Retry-After')
                        );
                    }

                    return $errorResponse;

                } catch (\Throwable $renderException) {

                    // Menggunakan respons Blade apabila
                    // halaman Inertia gagal dirender.

                }
            }

            // Error page independen dari database
            // dan aset frontend.

            $errorResponse = response()->view(
                'errors.public',
                [
                    'status' => $status,
                ],
                $status
            );

            $errorResponse->headers->set(
                'Cache-Control',
                'no-store'
            );

            $errorResponse->setVary(
                'X-Inertia',
                false
            );

            if ($response->headers->has('Retry-After')) {
                $errorResponse->headers->set(
                    'Retry-After',
                    $response->headers->get('Retry-After')
                );
            }

            return $errorResponse;

        });

    })

    ->create();
