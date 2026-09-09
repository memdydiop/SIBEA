<?php

namespace App\Http\Controllers\Public;

use App\Data\HomepageData;
use App\Http\Controllers\Controller;
use App\Models\Expertise;
use App\Models\Partner;
use App\Models\Post;
use App\Models\PublicProject;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $cacheKey = 'home:vitrine:settings:v2';

        $settings = Cache::remember($cacheKey, 300, function (): array {
            $data = HomepageData::fromSettings();

            $data['stats'] = [
                'projects' => PublicProject::where('is_published', true)->count(),
                'expertises' => Expertise::where('is_active', true)->count(),
                'partners' => Partner::where('is_active', true)->count(),
            ];

            return $data;
        });

        // Mappage HomepageData (snake_case) -> variables camelCase pour la vue
        $viewSettings = [
            'heroTitle' => $settings['hero_title'],
            'heroSubtitle' => $settings['hero_subtitle'],
            'heroEyebrow' => $settings['hero_eyebrow'],
            'heroImage' => $settings['hero_image'],
            'heroCtaLabel' => $settings['hero_cta_label'],
            'heroCtaUrl' => $settings['hero_cta_url'],
            'heroSecondaryLabel' => $settings['hero_secondary_label'],
            'heroSecondaryUrl' => $settings['hero_secondary_url'],
            'heroSlide2Eyebrow' => $settings['hero_slide2_eyebrow'],
            'heroSlide2Title' => $settings['hero_slide2_title'],
            'heroSlide2Subtitle' => $settings['hero_slide2_subtitle'],
            'heroSlide2Image' => $settings['hero_slide2_image'],
            'heroSlide2CtaLabel' => $settings['hero_slide2_cta_label'],
            'heroSlide2CtaUrl' => $settings['hero_slide2_cta_url'],
            'heroSlide2SecondaryLabel' => $settings['hero_slide2_secondary_label'],
            'heroSlide2SecondaryUrl' => $settings['hero_slide2_secondary_url'],
            'heroSlide3Eyebrow' => $settings['hero_slide3_eyebrow'],
            'heroSlide3Title' => $settings['hero_slide3_title'],
            'heroSlide3Subtitle' => $settings['hero_slide3_subtitle'],
            'heroSlide3Image' => $settings['hero_slide3_image'],
            'heroSlide3CtaLabel' => $settings['hero_slide3_cta_label'],
            'heroSlide3CtaUrl' => $settings['hero_slide3_cta_url'],
            'heroSlide3SecondaryLabel' => $settings['hero_slide3_secondary_label'],
            'heroSlide3SecondaryUrl' => $settings['hero_slide3_secondary_url'],
            'siteName' => $settings['site_name'],
            'siteLogo' => $settings['site_logo'],
            'statsProjectsLabel' => $settings['stats_projects_label'],
            'statsExpertisesLabel' => $settings['stats_expertises_label'],
            'statsPartnersLabel' => $settings['stats_partners_label'],
            'aboutTitle' => $settings['about_title'],
            'aboutContent' => $settings['about_content'],
            'seoTitle' => $settings['seo_title'],
            'seoDescription' => $settings['seo_description'],
            'sectorsTitle' => $settings['sectors_title'],
            'sectorsSubtitle' => $settings['sectors_subtitle'],
            'sectorBtpBadge' => $settings['sector_btp_badge'],
            'sectorBtpTitle' => $settings['sector_btp_title'],
            'sectorBtpDesc' => $settings['sector_btp_desc'],
            'sectorBtpArg' => $settings['sector_btp_arg'],
            'sectorLotBadge' => $settings['sector_lot_badge'],
            'sectorLotTitle' => $settings['sector_lot_title'],
            'sectorLotDesc' => $settings['sector_lot_desc'],
            'sectorLotArg' => $settings['sector_lot_arg'],
            'sectorAgroBadge' => $settings['sector_agro_badge'],
            'sectorAgroTitle' => $settings['sector_agro_title'],
            'sectorAgroDesc' => $settings['sector_agro_desc'],
            'sectorAgroArg' => $settings['sector_agro_arg'],
            'portfolioTitle' => $settings['portfolio_title'],
            'portfolioSubtitle' => $settings['portfolio_subtitle'],
            'guaranteesTitle' => $settings['guarantees_title'],
            'guaranteesSubtitle' => $settings['guarantees_subtitle'],
            'guarantee1Title' => $settings['guarantee1_title'],
            'guarantee1Desc' => $settings['guarantee1_desc'],
            'guarantee2Title' => $settings['guarantee2_title'],
            'guarantee2Desc' => $settings['guarantee2_desc'],
            'guarantee3Title' => $settings['guarantee3_title'],
            'guarantee3Desc' => $settings['guarantee3_desc'],
            'guarantee4Title' => $settings['guarantee4_title'],
            'guarantee4Desc' => $settings['guarantee4_desc'],
            'processTitle' => $settings['process_title'],
            'processSubtitle' => $settings['process_subtitle'],
            'process1Title' => $settings['process1_title'],
            'process1Subtitle' => $settings['process1_subtitle'],
            'process1Desc' => $settings['process1_desc'],
            'process2Title' => $settings['process2_title'],
            'process2Subtitle' => $settings['process2_subtitle'],
            'process2Desc' => $settings['process2_desc'],
            'process3Title' => $settings['process3_title'],
            'process3Subtitle' => $settings['process3_subtitle'],
            'process3Desc' => $settings['process3_desc'],
            'process4Title' => $settings['process4_title'],
            'process4Subtitle' => $settings['process4_subtitle'],
            'process4Desc' => $settings['process4_desc'],
            'formTitle' => $settings['form_title'],
            'formSubtitle' => $settings['form_subtitle'],
            'formNote' => $settings['form_note'],
            'stats' => $settings['stats'],
        ];

        $expertises = Expertise::active()->withCount('services')->limit(6)->get();
        $featuredProjects = PublicProject::featured()->limit(6)->get();
        $latestPosts = Post::published()->limit(3)->get();
        $testimonials = Testimonial::where('is_active', true)->orderBy('order')->limit(3)->get();
        $partners = Partner::where('is_active', true)->orderBy('order')->get();

        $heroSlides = [
            [
                'eyebrow' => $viewSettings['heroEyebrow'] ?: 'BTP · Génie civil · VRD · Énergie',
                'title' => $viewSettings['heroTitle'],
                'subtitle' => $viewSettings['heroSubtitle'],
                'image' => $viewSettings['heroImage'],
                'ctaLabel' => $viewSettings['heroCtaLabel'] ?: 'Demander un devis',
                'ctaUrl' => $viewSettings['heroCtaUrl'] ?: route('public.quote.create'),
                'secondaryLabel' => $viewSettings['heroSecondaryLabel'] ?: 'Voir nos réalisations',
                'secondaryUrl' => $viewSettings['heroSecondaryUrl'] ?: route('public.projects.index'),
            ],
            [
                'eyebrow' => $viewSettings['heroSlide2Eyebrow'] ?: 'Programmes immobiliers',
                'title' => $viewSettings['heroSlide2Title'] ?: 'Lotissements viabilisés, titres sécurisés',
                'subtitle' => $viewSettings['heroSlide2Subtitle'] ?: 'Terrains et villas disponibles — viabilisation complète, voirie, assainissement, titres fonciers sécurisés.',
                'image' => $viewSettings['heroSlide2Image'] ?: ($featuredProjects->get(0)?->cover_image ?? $viewSettings['heroImage']),
                'ctaLabel' => $viewSettings['heroSlide2CtaLabel'] ?: 'Découvrir les programmes',
                'ctaUrl' => $viewSettings['heroSlide2CtaUrl'] ?: route('public.programs.index'),
                'secondaryLabel' => $viewSettings['heroSlide2SecondaryLabel'] ?: 'Demander un devis',
                'secondaryUrl' => $viewSettings['heroSlide2SecondaryUrl'] ?: route('public.quote.create'),
            ],
            [
                'eyebrow' => $viewSettings['heroSlide3Eyebrow'] ?: 'Savoir-faire SIBEA',
                'title' => $viewSettings['heroSlide3Title'] ?: 'Infrastructures durables, chantiers maîtrisés',
                'subtitle' => $viewSettings['heroSlide3Subtitle'] ?: 'De l’étude à la réception : bâtiment, VRD, génie civil, énergie — qualité, sécurité, délais tenus.',
                'image' => $viewSettings['heroSlide3Image'] ?: ($featuredProjects->get(1)?->cover_image ?? $featuredProjects->get(0)?->cover_image ?? $viewSettings['heroImage']),
                'ctaLabel' => $viewSettings['heroSlide3CtaLabel'] ?: 'Nos réalisations',
                'ctaUrl' => $viewSettings['heroSlide3CtaUrl'] ?: route('public.projects.index'),
                'secondaryLabel' => $viewSettings['heroSlide3SecondaryLabel'] ?: 'Nous contacter',
                'secondaryUrl' => $viewSettings['heroSlide3SecondaryUrl'] ?: route('public.contact'),
            ],
        ];

        // Requête PG-safe : ORDER BY porte sur la colonne du SELECT DISTINCT,
        // sinon PostgreSQL rejette (42P10) l'ORDER BY hérité du scope published().
        $projectCategories = PublicProject::published()->reorder()->whereNotNull('category')->select('category')->distinct()->orderBy('category')->pluck('category')->filter()->values();

        return view('public.home', array_merge($viewSettings, compact('expertises', 'featuredProjects', 'latestPosts', 'testimonials', 'partners', 'heroSlides', 'projectCategories')));
    }
}
