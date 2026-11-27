<?php

use App\Http\Controllers\Public\CompanyProfileController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ItProjectController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\PublicationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| BERANDA
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [HomeController::class, 'index']
)->name('home');

/*
|--------------------------------------------------------------------------
| TENTANG KAMI
|--------------------------------------------------------------------------
*/

Route::get(
    '/tentang-kami',
    [CompanyProfileController::class, 'about']
)->name('company.about');

/*
|--------------------------------------------------------------------------
| LAYANAN
|--------------------------------------------------------------------------
*/

Route::name('company.')->group(function () {

    Route::get(
        '/visi-misi',
        [CompanyProfileController::class, 'visionMission']
    )->name('vision-mission');

    Route::get(
        '/direktur-eksekutif',
        [CompanyProfileController::class, 'executiveDirector']
    )->name('executive-director');

    Route::get(
        '/tim-ahli',
        [CompanyProfileController::class, 'experts']
    )->name('experts');

    Route::get(
        '/struktur-organisasi',
        [CompanyProfileController::class, 'organizationStructure']
    )->name('organization-structure');

    Route::get(
        '/tujuan-perusahaan',
        [CompanyProfileController::class, 'companyGoals']
    )->name('company-goals');

    Route::get(
        '/nilai-perusahaan',
        [CompanyProfileController::class, 'companyValues']
    )->name('company-values');

    Route::get(
        '/pencapaian',
        [CompanyProfileController::class, 'achievements']
    )->name('achievements');

});

/*
|--------------------------------------------------------------------------
| PUBLIKASI - BERITA
|--------------------------------------------------------------------------
*/

Route::prefix('berita')
    ->name('news.')
    ->group(function () {

        Route::get(
            '/',
            [NewsController::class, 'index']
        )->name('index');

        Route::get(
            '/{slug}',
            [NewsController::class, 'show']
        )->name('show');

    });

/*
|--------------------------------------------------------------------------
| PUBLIKASI DAN KAJIAN
|--------------------------------------------------------------------------
*/

Route::prefix('publikasi')
    ->name('publications.')
    ->group(function () {

        Route::get(
            '/',
            [PublicationController::class, 'index']
        )->name('index');

        Route::get(
            '/{slug}/unduh',
            [PublicationController::class, 'download']
        )->name('download');

        Route::get(
            '/{slug}',
            [PublicationController::class, 'show']
        )->name('show');

    });

/*
|--------------------------------------------------------------------------
| PROJECT IT
|--------------------------------------------------------------------------
*/

Route::prefix('project-it')
    ->name('projects.')
    ->group(function () {

        Route::get(
            '/',
            [ItProjectController::class, 'index']
        )->name('index');

        Route::get(
            '/{slug}',
            [ItProjectController::class, 'show']
        )->name('show');

    });

/*
|--------------------------------------------------------------------------
| KONTAK
|--------------------------------------------------------------------------
|
| Akan ditambahkan setelah ContactController selesai.
|
*/

/*
|--------------------------------------------------------------------------
| PENCARIAN GLOBAL
|--------------------------------------------------------------------------
|
| Akan ditambahkan setelah SearchController selesai.
|
*/
