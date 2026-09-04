<?php

use App\Enums\EmployeeStatus;
use App\Models\Employee;
use App\Models\Page;
use App\Models\Program;
use App\Models\ProgramLot;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

test('public page shows published page', function () {
    $page = Page::factory()->create(['is_published' => true, 'published_at' => now()]);

    $response = $this->get(route('public.pages.show', $page->slug));

    $response->assertOk()->assertSee($page->title);
});

test('unpublished page returns 404', function () {
    $page = Page::factory()->create(['is_published' => false]);

    $response = $this->get(route('public.pages.show', $page->slug));

    $response->assertNotFound();
});

test('programmes index lists published programs', function () {
    $pub = Program::factory()->create(['is_published' => true]);
    $draft = Program::factory()->create(['is_published' => false]);

    $response = $this->get(route('public.programs.index'));

    $response->assertOk()->assertSee($pub->title)->assertDontSee($draft->title);
});

test('programme detail shows lots', function () {
    $program = Program::factory()->create(['is_published' => true]);
    $lot = ProgramLot::factory()->create(['program_id' => $program->id]);

    $response = $this->get(route('public.programs.show', $program->slug));

    $response->assertOk()->assertSee($program->title)->assertSee($lot->reference);
});

test('equipe page lists active employees', function () {
    $active = Employee::factory()->create(['status' => EmployeeStatus::Active, 'is_public' => true]);
    $inactive = Employee::factory()->create(['status' => EmployeeStatus::Terminated, 'is_public' => true]);
    $private = Employee::factory()->create(['status' => EmployeeStatus::Active, 'is_public' => false]);

    $response = $this->get(route('public.team.index'));

    $response->assertOk()->assertSee($active->full_name)->assertDontSee($inactive->full_name)->assertDontSee($private->full_name);
});

test('sitemap includes pages and programmes', function () {
    Page::factory()->create(['is_published' => true, 'published_at' => now()]);
    Program::factory()->create(['is_published' => true]);

    $response = $this->get(route('sitemap'));

    $response->assertOk()->assertHeader('Content-Type', 'application/xml');
    $response->assertSee(route('public.pages.show', Page::first()->slug), false);
    $response->assertSee(route('public.programs.index'), false);
});

test('admin pages require cms.manage', function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $user = User::factory()->create();
    $user->assignRole('client');
    $this->actingAs($user);

    $this->get(route('admin.pages'))->assertForbidden();
    $this->get(route('admin.programs'))->assertForbidden();
    $this->get(route('admin.menus'))->assertForbidden();
});

test('super-admin can access new admin pages', function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    $this->actingAs($user);

    $this->get(route('admin.pages'))->assertOk();
    $this->get(route('admin.menus'))->assertOk();
    $this->get(route('admin.programs'))->assertOk();
    $this->get(route('admin.program-lots'))->assertOk();
});
