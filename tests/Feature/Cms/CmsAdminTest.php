<?php

use App\Models\Expertise;
use App\Models\QuoteRequest;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('cms admin requires cms.manage permission', function () {
    $user = User::factory()->create();
    $user->assignRole('client'); // no cms.manage
    $this->actingAs($user);

    $this->get(route('admin.expertises'))->assertForbidden();
    $this->get(route('admin.services'))->assertForbidden();
    $this->get(route('admin.public-projects'))->assertForbidden();
    $this->get(route('admin.posts'))->assertForbidden();
    $this->get(route('admin.quote-requests'))->assertForbidden();
});

test('super-admin can access cms admin pages', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    $this->actingAs($user);

    $this->get(route('admin.expertises'))->assertOk();
    $this->get(route('admin.services'))->assertOk();
    $this->get(route('admin.public-projects'))->assertOk();
    $this->get(route('admin.posts'))->assertOk();
    $this->get(route('admin.partners'))->assertOk();
    $this->get(route('admin.testimonials'))->assertOk();
    $this->get(route('admin.quote-requests'))->assertOk();
    $this->get(route('admin.site-settings'))->assertOk();
});

test('commercial-foncier can manage cms', function () {
    $user = User::factory()->create();
    $user->assignRole('commercial-foncier');
    $this->actingAs($user);

    $this->get(route('admin.expertises'))->assertOk();
    $this->get(route('admin.quote-requests'))->assertOk();
});

test('can create expertise via livewire', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    $this->actingAs($user);

    Livewire::test('pages::admin.expertises')
        ->call('openCreate')
        ->set('title', 'Nouvelle expertise')
        ->set('excerpt', 'Extrait test')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('expertises', ['title' => 'Nouvelle expertise']);
    $this->assertDatabaseHas('audit_logs', ['action' => 'EXPERTISE_CREATED']);
});

test('can create public project via livewire', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    $this->actingAs($user);

    Livewire::test('pages::admin.public-projects')
        ->call('openCreate')
        ->set('title', 'Projet test')
        ->set('category', 'Bâtiment')
        ->set('year', 2024)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('public_projects', ['title' => 'Projet test']);
});

test('can update quote request status via livewire', function () {
    $qr = QuoteRequest::factory()->create(['status' => 'nouveau']);
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    $this->actingAs($user);

    Livewire::test('pages::admin.quote-requests')
        ->call('openEdit', $qr->id)
        ->set('status', 'qualifie')
        ->set('internal_notes', 'Appel client OK')
        ->call('save')
        ->assertHasNoErrors();

    expect($qr->fresh()->status->value)->toBe('qualifie');
    $this->assertDatabaseHas('audit_logs', ['action' => 'QUOTE_REQUEST_UPDATED']);
});

test('expertise slug is auto generated if empty', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    $this->actingAs($user);

    Livewire::test('pages::admin.expertises')
        ->call('openCreate')
        ->set('title', 'Expertise Sans Slug')
        ->set('slug', '')
        ->call('save')
        ->assertHasNoErrors();

    $exp = Expertise::where('title', 'Expertise Sans Slug')->first();
    expect($exp->slug)->toBe('expertise-sans-slug');
});

test('site settings can be managed', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    $this->actingAs($user);

    Livewire::test('pages::admin.site-settings')
        ->call('openCreate')
        ->set('key', 'test_key')
        ->set('value', 'test value')
        ->set('group', 'general')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('site_settings', ['key' => 'test_key', 'value' => 'test value']);
});
