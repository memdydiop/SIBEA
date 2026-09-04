<?php

use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Expertise;
use App\Models\Media;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Program;
use App\Models\PublicProject;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Team;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::livewire('dashboard', 'pages::dashboard')->name('dashboard');

    Route::livewire('departments', 'pages::admin.departments')
        ->can('viewAny', Department::class)
        ->name('departments');

    Route::livewire('teams', 'pages::admin.teams')
        ->can('viewAny', Team::class)
        ->name('teams');

    Route::livewire('employees', 'pages::admin.employees')
        ->can('viewAny', Employee::class)
        ->name('employees');

    Route::livewire('users', 'pages::admin.users')
        ->can('viewAny', User::class)
        ->name('users');

    Route::livewire('audit', 'pages::admin.audit')
        ->can('viewAny', AuditLog::class)
        ->name('audit');

    // CMS — vitrine (cms.manage)
    Route::livewire('expertises', 'pages::admin.expertises')
        ->can('viewAny', Expertise::class)
        ->name('expertises');

    Route::livewire('services', 'pages::admin.services')
        ->can('viewAny', Service::class)
        ->name('services');

    Route::livewire('public-projects', 'pages::admin.public-projects')
        ->can('viewAny', PublicProject::class)
        ->name('public-projects');

    Route::livewire('posts', 'pages::admin.posts')
        ->can('viewAny', Post::class)
        ->name('posts');

    Route::livewire('pages', 'pages::admin.pages')
        ->can('viewAny', Page::class)
        ->name('pages');

    Route::livewire('programs', 'pages::admin.programs')
        ->can('viewAny', Program::class)
        ->name('programs');

    Route::livewire('program-lots', 'pages::admin.program-lots')
        ->can('viewAny', Program::class)
        ->name('program-lots');

    Route::livewire('partners', 'pages::admin.partners')
        ->can('viewAny', Partner::class)
        ->name('partners');

    Route::livewire('testimonials', 'pages::admin.testimonials')
        ->can('viewAny', Testimonial::class)
        ->name('testimonials');

    Route::livewire('quote-requests', 'pages::admin.quote-requests')
        ->can('viewAny', QuoteRequest::class)
        ->name('quote-requests');

    Route::livewire('site-settings', 'pages::admin.site-settings')
        ->can('viewAny', SiteSetting::class)
        ->name('site-settings');

    Route::livewire('homepage', 'pages::admin.homepage')
        ->can('viewAny', SiteSetting::class)
        ->name('homepage');

    Route::livewire('menus', 'pages::admin.menus')
        ->can('viewAny', Menu::class)
        ->name('menus');

    Route::livewire('menu-items', 'pages::admin.menu-items')
        ->can('viewAny', MenuItem::class)
        ->name('menu-items');

    Route::livewire('media', 'pages::admin.media')->can('viewAny', Media::class)->name('media');
});
