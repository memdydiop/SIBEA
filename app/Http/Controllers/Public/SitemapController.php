<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Expertise;
use App\Models\Page;
use App\Models\Post;
use App\Models\Program;
use App\Models\PublicProject;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [
            ['loc' => route('home'), 'lastmod' => now()->toDateString(), 'priority' => '1.0'],
            ['loc' => route('public.expertises.index'), 'lastmod' => now()->toDateString(), 'priority' => '0.8'],
            ['loc' => route('public.projects.index'), 'lastmod' => now()->toDateString(), 'priority' => '0.8'],
            ['loc' => route('public.programs.index'), 'lastmod' => now()->toDateString(), 'priority' => '0.8'],
            ['loc' => route('public.posts.index'), 'lastmod' => now()->toDateString(), 'priority' => '0.7'],
            ['loc' => route('public.team.index'), 'lastmod' => now()->toDateString(), 'priority' => '0.6'],
            ['loc' => route('public.quote.create'), 'lastmod' => now()->toDateString(), 'priority' => '0.6'],
            ['loc' => route('public.about'), 'lastmod' => now()->toDateString(), 'priority' => '0.6'],
            ['loc' => route('public.contact'), 'lastmod' => now()->toDateString(), 'priority' => '0.6'],
        ];

        foreach (Expertise::active()->get() as $e) {
            $urls[] = ['loc' => route('public.expertises.show', $e->slug), 'lastmod' => $e->updated_at->toDateString(), 'priority' => '0.7'];
        }

        foreach (Service::active()->get() as $s) {
            $urls[] = ['loc' => route('public.services.show', $s->slug), 'lastmod' => $s->updated_at->toDateString(), 'priority' => '0.6'];
        }

        foreach (PublicProject::published()->get() as $p) {
            $urls[] = ['loc' => route('public.projects.show', $p->slug), 'lastmod' => $p->updated_at->toDateString(), 'priority' => '0.7'];
        }

        foreach (Post::published()->get() as $post) {
            $urls[] = ['loc' => route('public.posts.show', $post->slug), 'lastmod' => $post->updated_at->toDateString(), 'priority' => '0.6'];
        }

        foreach (Page::published()->get() as $page) {
            $urls[] = ['loc' => route('public.pages.show', $page->slug), 'lastmod' => $page->updated_at->toDateString(), 'priority' => '0.6'];
        }

        foreach (Program::published()->get() as $program) {
            $urls[] = ['loc' => route('public.programs.show', $program->slug), 'lastmod' => $program->updated_at->toDateString(), 'priority' => '0.7'];
        }

        $xml = view('public.sitemap', ['urls' => $urls])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
