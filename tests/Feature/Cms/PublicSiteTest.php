<?php

use App\Models\Expertise;
use App\Models\Post;
use App\Models\PublicProject;
use App\Models\QuoteRequest;
use App\Models\Service;

test('home page loads with vitrine data', function () {
    Expertise::factory()->create(['is_active' => true]);
    PublicProject::factory()->create(['is_published' => true, 'is_featured' => true]);
    Post::factory()->create(['is_published' => true, 'published_at' => now()]);

    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('SIBEA')
        ->assertSee('Demander un devis');
});

test('expertises index lists active expertises', function () {
    $visible = Expertise::factory()->create(['is_active' => true, 'title' => 'Génie civil test']);
    $hidden = Expertise::factory()->create(['is_active' => false, 'title' => 'Hidden']);

    $response = $this->get(route('public.expertises.index'));

    $response->assertOk()
        ->assertSee($visible->title)
        ->assertDontSee($hidden->title);
});

test('expertise detail shows services', function () {
    $expertise = Expertise::factory()->create(['is_active' => true]);
    $service = Service::factory()->create(['expertise_id' => $expertise->id, 'is_active' => true]);

    $response = $this->get(route('public.expertises.show', $expertise->slug));

    $response->assertOk()
        ->assertSee($expertise->title)
        ->assertSee($service->title);
});

test('service detail is accessible', function () {
    $service = Service::factory()->create(['is_active' => true]);

    $response = $this->get(route('public.services.show', $service->slug));

    $response->assertOk()
        ->assertSee($service->title);
});

test('realisations index paginates published projects', function () {
    $published = PublicProject::factory()->create(['is_published' => true]);
    $draft = PublicProject::factory()->create(['is_published' => false]);

    $response = $this->get(route('public.projects.index'));

    $response->assertOk()
        ->assertSee($published->title)
        ->assertDontSee($draft->title);
});

test('realisation detail shows', function () {
    $project = PublicProject::factory()->create(['is_published' => true]);

    $response = $this->get(route('public.projects.show', $project->slug));

    $response->assertOk()
        ->assertSee($project->title);
});

test('actualites index lists published posts', function () {
    $published = Post::factory()->create(['is_published' => true, 'published_at' => now()->subDay()]);
    $draft = Post::factory()->create(['is_published' => false]);

    $response = $this->get(route('public.posts.index'));

    $response->assertOk()
        ->assertSee($published->title)
        ->assertDontSee($draft->title);
});

test('post detail shows', function () {
    $post = Post::factory()->create(['is_published' => true, 'published_at' => now()]);

    $response = $this->get(route('public.posts.show', $post->slug));

    $response->assertOk()
        ->assertSee($post->title);
});

test('a-propos and contact pages load', function () {
    $this->get(route('public.about'))->assertOk()->assertSee('SIBEA');
    $this->get(route('public.contact'))->assertOk()->assertSee('Contact');
});

test('sitemap contains public urls', function () {
    $response = $this->get(route('sitemap'));

    $response->assertOk()
        ->assertHeader('Content-Type', 'application/xml');
    $response->assertSee(route('home'), false);
});

test('quote request form can be submitted', function () {
    $payload = [
        'first_name' => 'Jean',
        'last_name' => 'Dupont',
        'email' => 'jean@example.com',
        'phone' => '+225 01 02 03 04 05',
        'service_type' => 'Bâtiment',
        'description' => 'Je souhaite construire une villa de 4 pièces avec étage, terrain 500m2 à Cocody.',
        'consent' => '1',
        'company' => 'SCI Test',
    ];

    $response = $this->post(route('public.quote.store'), $payload);

    $response->assertRedirect();
    $this->assertDatabaseHas('quote_requests', [
        'email' => 'jean@example.com',
        'service_type' => 'Bâtiment',
    ]);

    $qr = QuoteRequest::where('email', 'jean@example.com')->first();
    expect($qr->reference)->toMatch('/^QR-\d{4}-\d{4}$/');
    expect($qr->status->value)->toBe('nouveau');
});

test('quote request validation requires consent and description', function () {
    $response = $this->post(route('public.quote.store'), [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
        'phone' => '012345',
        'service_type' => 'VRD',
        'description' => 'short',
        // consent missing
    ]);

    $response->assertSessionHasErrors(['description', 'consent']);
});
