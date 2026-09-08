<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Expertise;
use App\Models\Partner;
use App\Models\Post;
use App\Models\PublicProject;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        // NOTE: on ne cache QUE des scalaires/tableaux primitifs.
        // Cacher des Collections/Models Eloquent avec CACHE_STORE=database et
        // cache.serializable_classes=false produit des __PHP_Incomplete_Class
        // et fait échouer la vue avec "Attempt to read property 'slug' on string"
        // (foreach sur Collection incomplète itère sur ses propriétés internes).
        $cacheKey = 'home:vitrine:settings:v2';

        $settings = Cache::remember($cacheKey, 300, fn (): array => [
            'heroTitle' => SiteSetting::get('hero_title', 'Bâtir l’avenir avec excellence'),
            'heroSubtitle' => SiteSetting::get('hero_subtitle', 'Entreprise BTP de référence — Bâtiment, Génie civil, VRD, Énergie depuis plus de 20 ans.'),
            'heroImage' => SiteSetting::get('hero_image'),
            'heroEyebrow' => SiteSetting::get('hero_eyebrow', 'BTP · Génie civil · VRD · Énergie'),
            'heroCtaLabel' => SiteSetting::get('hero_cta_label', 'Demander un devis'),
            'heroCtaUrl' => SiteSetting::get('hero_cta_url', '/devis'),
            'heroSlide2Eyebrow' => SiteSetting::get('hero_slide2_eyebrow', 'Programmes immobiliers'),
            'heroSlide2Title' => SiteSetting::get('hero_slide2_title', 'Lotissements viabilisés, titres sécurisés'),
            'heroSlide2Subtitle' => SiteSetting::get('hero_slide2_subtitle', 'Terrains et villas disponibles — viabilisation complète, voirie, assainissement, titres fonciers sécurisés.'),
            'heroSlide2Image' => SiteSetting::get('hero_slide2_image'),
            'heroSlide2CtaLabel' => SiteSetting::get('hero_slide2_cta_label', 'Découvrir les programmes'),
            'heroSlide2CtaUrl' => SiteSetting::get('hero_slide2_cta_url', '/programmes'),
            'heroSlide2SecondaryLabel' => SiteSetting::get('hero_slide2_secondary_label', 'Demander un devis'),
            'heroSlide2SecondaryUrl' => SiteSetting::get('hero_slide2_secondary_url', '/devis'),
            'heroSlide3Eyebrow' => SiteSetting::get('hero_slide3_eyebrow', 'Savoir-faire SIBEA'),
            'heroSlide3Title' => SiteSetting::get('hero_slide3_title', 'Infrastructures durables, chantiers maîtrisés'),
            'heroSlide3Subtitle' => SiteSetting::get('hero_slide3_subtitle', 'De l’étude à la réception : bâtiment, VRD, génie civil, énergie — qualité, sécurité, délais tenus.'),
            'heroSlide3Image' => SiteSetting::get('hero_slide3_image'),
            'heroSlide3CtaLabel' => SiteSetting::get('hero_slide3_cta_label', 'Nos réalisations'),
            'heroSlide3CtaUrl' => SiteSetting::get('hero_slide3_cta_url', '/realisations'),
            'heroSlide3SecondaryLabel' => SiteSetting::get('hero_slide3_secondary_label', 'Nous contacter'),
            'heroSlide3SecondaryUrl' => SiteSetting::get('hero_slide3_secondary_url', '/contact'),
            'statsProjectsLabel' => SiteSetting::get('stats_projects_label', 'Projets livrés'),
            'statsExpertisesLabel' => SiteSetting::get('stats_expertises_label', 'Expertises'),
            'statsPartnersLabel' => SiteSetting::get('stats_partners_label', 'Partenaires'),
            'aboutTitle' => SiteSetting::get('about_title', 'À propos de SIBEA'),
            'aboutContent' => SiteSetting::get('about_content'),
            'seoTitle' => SiteSetting::get('seo_title'),
            'seoDescription' => SiteSetting::get('seo_description'),
            'stats' => [
                'projects' => PublicProject::where('is_published', true)->count(),
                'expertises' => Expertise::where('is_active', true)->count(),
                'partners' => Partner::where('is_active', true)->count(),
            ],
        ]);

        // Collections Eloquent NON mises en cache — évite la désérialisation
        // avec allowed_classes=false (sécurité gadget chain).
        $expertises = Expertise::active()->withCount('services')->limit(6)->get();
        $featuredProjects = PublicProject::featured()->limit(6)->get();
        $latestPosts = Post::published()->limit(3)->get();
        $testimonials = Testimonial::where('is_active', true)->orderBy('order')->limit(3)->get();
        $partners = Partner::where('is_active', true)->orderBy('order')->get();

        // Nettoie l'ancien cache empoisonné v1 (contenait des objets sérialisés).
        if (Cache::has('home:vitrine:v1')) {
            Cache::forget('home:vitrine:v1');
        }

        // Hero slideshow — 3 slides CDC (100% CMS, fallback sur featuredProjects / heroImage)
        $heroSlides = [
            [
                'eyebrow' => $settings['heroEyebrow'] ?: 'BTP · Génie civil · VRD · Énergie',
                'title' => $settings['heroTitle'],
                'subtitle' => $settings['heroSubtitle'],
                'image' => $settings['heroImage'],
                'ctaLabel' => $settings['heroCtaLabel'] ?: 'Demander un devis',
                'ctaUrl' => $settings['heroCtaUrl'] ?: route('public.quote.create'),
                'secondaryLabel' => 'Voir nos réalisations',
                'secondaryUrl' => route('public.projects.index'),
            ],
            [
                'eyebrow' => $settings['heroSlide2Eyebrow'] ?: 'Programmes immobiliers',
                'title' => $settings['heroSlide2Title'] ?: 'Lotissements viabilisés, titres sécurisés',
                'subtitle' => $settings['heroSlide2Subtitle'] ?: 'Terrains et villas disponibles — viabilisation complète, voirie, assainissement, titres fonciers sécurisés.',
                'image' => $settings['heroSlide2Image'] ?: ($featuredProjects->get(0)?->cover_image ?? $settings['heroImage']),
                'ctaLabel' => $settings['heroSlide2CtaLabel'] ?: 'Découvrir les programmes',
                'ctaUrl' => $settings['heroSlide2CtaUrl'] ?: route('public.programs.index'),
                'secondaryLabel' => $settings['heroSlide2SecondaryLabel'] ?: 'Demander un devis',
                'secondaryUrl' => $settings['heroSlide2SecondaryUrl'] ?: route('public.quote.create'),
            ],
            [
                'eyebrow' => $settings['heroSlide3Eyebrow'] ?: 'Savoir-faire SIBEA',
                'title' => $settings['heroSlide3Title'] ?: 'Infrastructures durables, chantiers maîtrisés',
                'subtitle' => $settings['heroSlide3Subtitle'] ?: 'De l’étude à la réception : bâtiment, VRD, génie civil, énergie — qualité, sécurité, délais tenus.',
                'image' => $settings['heroSlide3Image'] ?: ($featuredProjects->get(1)?->cover_image ?? $featuredProjects->get(0)?->cover_image ?? $settings['heroImage']),
                'ctaLabel' => $settings['heroSlide3CtaLabel'] ?: 'Nos réalisations',
                'ctaUrl' => $settings['heroSlide3CtaUrl'] ?: route('public.projects.index'),
                'secondaryLabel' => $settings['heroSlide3SecondaryLabel'] ?: 'Nous contacter',
                'secondaryUrl' => $settings['heroSlide3SecondaryUrl'] ?: route('public.contact'),
            ],
        ];

        $projectCategories = PublicProject::published()->reorder()->select('category')->distinct()->pluck('category')->filter()->values();

        return view('public.home', array_merge($settings, compact('expertises', 'featuredProjects', 'latestPosts', 'testimonials', 'partners', 'heroSlides', 'projectCategories')));
    }
}
