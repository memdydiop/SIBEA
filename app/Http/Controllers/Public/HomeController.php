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
            'heroCtaLabel' => SiteSetting::get('hero_cta_label', 'Demander un devis'),
            'heroCtaUrl' => SiteSetting::get('hero_cta_url', '/devis'),
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

        return view('public.home', array_merge($settings, compact('expertises', 'featuredProjects', 'latestPosts', 'testimonials', 'partners')));
    }
}
