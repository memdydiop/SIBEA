<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $page = Page::where('slug', 'a-propos')->where('is_published', true)->where('is_archived', false)->first();

        if ($page) {
            return view('public.pages.show', ['page' => $page]);
        }

        return view('public.pages.about', [
            'aboutContent' => SiteSetting::get('about_content'),
            'aboutTitle' => SiteSetting::get('about_title', 'À propos de SIBEA'),
        ]);
    }

    public function contact(): View
    {
        $page = Page::where('slug', 'contact')->where('is_published', true)->where('is_archived', false)->first();

        if ($page) {
            return view('public.pages.show', ['page' => $page]);
        }

        return view('public.pages.contact', [
            'contactEmail' => SiteSetting::get('contact_email', 'contact@sibea.com'),
            'contactPhone' => SiteSetting::get('contact_phone', '+225 00 00 00 00'),
            'contactAddress' => SiteSetting::get('contact_address'),
        ]);
    }

    public function show(string $slug): View
    {
        $page = Page::where('slug', $slug)->where('is_published', true)->where('is_archived', false)->firstOrFail();

        return view('public.pages.show', [
            'page' => $page,
        ]);
    }
}
