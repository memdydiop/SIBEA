<?php

use App\Http\Controllers\Public\ExpertiseController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\PostController;
use App\Http\Controllers\Public\ProgramController;
use App\Http\Controllers\Public\PublicProjectController;
use App\Http\Controllers\Public\QuoteRequestController;
use App\Http\Controllers\Public\ServiceController;
use App\Http\Controllers\Public\SitemapController;
use App\Http\Controllers\Public\TeamController;
use Illuminate\Support\Facades\Route;

// Public vitrine SIBEA — SEO, sitemap, slugs canoniques
Route::get('/', HomeController::class)->name('home');

Route::get('expertises', [ExpertiseController::class, 'index'])->name('public.expertises.index');
Route::get('expertises/{slug}', [ExpertiseController::class, 'show'])->name('public.expertises.show');
Route::get('services/{slug}', [ServiceController::class, 'show'])->name('public.services.show');

Route::get('realisations', [PublicProjectController::class, 'index'])->name('public.projects.index');
Route::get('realisations/{slug}', [PublicProjectController::class, 'show'])->name('public.projects.show');

Route::get('actualites', [PostController::class, 'index'])->name('public.posts.index');
Route::get('actualites/{slug}', [PostController::class, 'show'])->name('public.posts.show');

Route::get('programmes', [ProgramController::class, 'index'])->name('public.programs.index');
Route::get('programmes/{slug}', [ProgramController::class, 'show'])->name('public.programs.show');

Route::get('a-propos', [PageController::class, 'about'])->name('public.about');
Route::get('equipe', [TeamController::class, 'index'])->name('public.team.index');
Route::get('contact', [PageController::class, 'contact'])->name('public.contact');

Route::get('pages/{slug}', [PageController::class, 'show'])->name('public.pages.show');

Route::get('devis', [QuoteRequestController::class, 'create'])->name('public.quote.create');
Route::post('devis', [QuoteRequestController::class, 'store'])->middleware('throttle:5,1')->name('public.quote.store');
Route::get('devis/merci/{reference}', [QuoteRequestController::class, 'success'])->name('public.quote.success');

Route::get('sitemap.xml', SitemapController::class)->name('sitemap');

// BC : ancien /dashboard → nouveau /admin/dashboard
Route::redirect('dashboard', '/admin/dashboard')->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
