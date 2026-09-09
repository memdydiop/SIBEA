<?php

namespace App\Data;

use App\Models\SiteSetting;
use Illuminate\Contracts\Validation\ValidationRule;

class HomepageData
{
    /**
     * Clés SiteSetting gérées par la homepage vitrine (source unique).
     *
     * @var list<string>
     */
    public const HOMEPAGE_KEYS = [
        'hero_title',
        'hero_subtitle',
        'hero_eyebrow',
        'hero_image',
        'hero_cta_label',
        'hero_cta_url',
        'hero_secondary_label',
        'hero_secondary_url',
        'hero_slide2_eyebrow',
        'hero_slide2_title',
        'hero_slide2_subtitle',
        'hero_slide2_image',
        'hero_slide2_cta_label',
        'hero_slide2_cta_url',
        'hero_slide2_secondary_label',
        'hero_slide2_secondary_url',
        'hero_slide3_eyebrow',
        'hero_slide3_title',
        'hero_slide3_subtitle',
        'hero_slide3_image',
        'hero_slide3_cta_label',
        'hero_slide3_cta_url',
        'hero_slide3_secondary_label',
        'hero_slide3_secondary_url',
        'site_name',
        'site_logo',
        'stats_projects_label',
        'stats_expertises_label',
        'stats_partners_label',
        'about_title',
        'about_content',
        'seo_title',
        'seo_description',
        // Landing 7 sections — secteurs / portfolio / garanties / process / formulaire
        'sectors_title',
        'sectors_subtitle',
        'sector_btp_badge',
        'sector_btp_title',
        'sector_btp_desc',
        'sector_btp_arg',
        'sector_lot_badge',
        'sector_lot_title',
        'sector_lot_desc',
        'sector_lot_arg',
        'sector_agro_badge',
        'sector_agro_title',
        'sector_agro_desc',
        'sector_agro_arg',
        'portfolio_title',
        'portfolio_subtitle',
        'guarantees_title',
        'guarantees_subtitle',
        'guarantee1_title',
        'guarantee1_desc',
        'guarantee2_title',
        'guarantee2_desc',
        'guarantee3_title',
        'guarantee3_desc',
        'guarantee4_title',
        'guarantee4_desc',
        'process_title',
        'process_subtitle',
        'process1_title',
        'process1_subtitle',
        'process1_desc',
        'process2_title',
        'process2_subtitle',
        'process2_desc',
        'process3_title',
        'process3_subtitle',
        'process3_desc',
        'process4_title',
        'process4_subtitle',
        'process4_desc',
        'form_title',
        'form_subtitle',
        'form_note',
    ];

    /**
     * Valeurs par défaut (fallback CmsSeeder / HomeController).
     *
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'hero_title' => 'Conception et réalisation d’infrastructures durables et clés en main',
            'hero_subtitle' => 'BTP · Génie Civil · Lotissement & Aménagement · Agro-industrie — Côte d’Ivoire, de l’étude à la réception.',
            'hero_eyebrow' => 'Fiabilité · Conformité · Réalisations concrètes',
            'hero_image' => null,
            'hero_cta_label' => 'Demander une étude de faisabilité',
            'hero_cta_url' => '/devis',
            'hero_secondary_label' => 'Prendre RDV avec un ingénieur',
            'hero_secondary_url' => '/contact',
            'hero_slide2_eyebrow' => 'Programmes immobiliers',
            'hero_slide2_title' => 'Lotissements viabilisés, titres sécurisés',
            'hero_slide2_subtitle' => 'Terrains et villas disponibles — viabilisation complète, voirie, assainissement, titres fonciers sécurisés.',
            'hero_slide2_image' => null,
            'hero_slide2_cta_label' => 'Découvrir les programmes',
            'hero_slide2_cta_url' => '/programmes',
            'hero_slide2_secondary_label' => 'Demander un devis',
            'hero_slide2_secondary_url' => '/devis',
            'hero_slide3_eyebrow' => 'Savoir-faire SIBEA',
            'hero_slide3_title' => 'Infrastructures durables, chantiers maîtrisés',
            'hero_slide3_subtitle' => 'De l’étude à la réception : bâtiment, VRD, génie civil, énergie — qualité, sécurité, délais tenus.',
            'hero_slide3_image' => null,
            'hero_slide3_cta_label' => 'Nos réalisations',
            'hero_slide3_cta_url' => '/realisations',
            'hero_slide3_secondary_label' => 'Nous contacter',
            'hero_slide3_secondary_url' => '/contact',
            'site_name' => 'SIBEA',
            'site_logo' => null,
            'stats_projects_label' => 'Projets livrés',
            'stats_expertises_label' => 'Expertises',
            'stats_partners_label' => 'Partenaires',
            'about_title' => 'À propos de SIBEA',
            'about_content' => null,
            'seo_title' => null,
            'seo_description' => null,
            // Landing — valeurs par défaut spec BTP
            'sectors_title' => 'Trois expertises, un seul interlocuteur',
            'sectors_subtitle' => 'Chaque profil s’y retrouve instantanément — BTP, Aménagement foncier et Agro-industrie, du gros œuvre aux process industriels.',
            'sector_btp_badge' => 'BTP & Génie Civil',
            'sector_btp_title' => 'Construction industrielle, gros œuvre, ouvrages d’art',
            'sector_btp_desc' => 'Engins en action, structures béton/acier — capacité technique et solidité financière pour tenir budget et planning.',
            'sector_btp_arg' => 'Respect du budget · Solidité financière',
            'sector_lot_badge' => 'Lotissement & Aménagement',
            'sector_lot_title' => 'Viabilisation VRD, aménagement urbain, gestion foncière',
            'sector_lot_desc' => 'Vues aériennes drone, plans masse 3D — sécurité juridique du foncier et VRD parfaite.',
            'sector_lot_arg' => 'Sécurité juridique · VRD parfaite',
            'sector_agro_badge' => 'Agro-industrie',
            'sector_agro_title' => 'Usines de transformation, entrepôts frigorifiques, logistique',
            'sector_agro_desc' => 'Intérieurs inox, process automatisés — hygiène stricte et optimisation des flux.',
            'sector_agro_arg' => 'Hygiène stricte · Flux optimisés',
            'portfolio_title' => 'On achète ce que l’on voit',
            'portfolio_subtitle' => 'Projets phares — photo du projet terminé, lieu, nature et indicateurs clés (surface, durée).',
            'guarantees_title' => 'Nos garanties & engagements',
            'guarantees_subtitle' => 'Forces opérationnelles — technique, conformité et réalisations concrètes pour une confiance absolue.',
            'guarantee1_title' => 'Respect des délais & budgets',
            'guarantee1_desc' => 'Gestion rigoureuse du planning, pilotage coûts, zéro dépassement non justifié.',
            'guarantee2_title' => 'Conformité & Sécurité QHSE',
            'guarantee2_desc' => 'Qualité, Hygiène, Sécurité, Environnement — normes strictes, audits continus.',
            'guarantee3_title' => 'Assurances & garanties',
            'guarantee3_desc' => 'Garantie décennale, RC Pro — ouvrages couverts, esprit tranquille.',
            'guarantee4_title' => 'Traçabilité & transparence',
            'guarantee4_desc' => 'Reporting, DOE, SAV — suivi quotidien, réception sans réserve.',
            'process_title' => 'Comment se déroule la collaboration ?',
            'process_subtitle' => 'Enlever le stress des grands travaux — 4 étapes claires, de l’étude à la réception.',
            'process1_title' => 'Étude & Diagnostic',
            'process1_subtitle' => 'Bureau d’études',
            'process1_desc' => 'Analyse faisabilité, relevés, contraintes — base technique solide.',
            'process2_title' => 'Chiffrage & Planification',
            'process2_subtitle' => 'Devis détaillé',
            'process2_desc' => 'Budget transparent, planning jalons, autorisations — 2–4 semaines.',
            'process3_title' => 'Exécution & Suivi',
            'process3_subtitle' => 'Suivi chantier',
            'process3_desc' => 'Chantier propre, reporting quotidien, QHSE — pilotage rigoureux.',
            'process4_title' => 'Livraison & Réception',
            'process4_subtitle' => 'Garantie décennale',
            'process4_desc' => 'Réception, levées de réserves, DOE — ouvrage durable, SAV inclus.',
            'form_title' => 'Parlons de votre projet',
            'form_subtitle' => 'Remplissez ce formulaire — nous qualifions votre besoin et revenons vers vous sous 24h avec un ingénieur dédié. Montants élevés, cycle long : on sécurise chaque étape.',
            'form_note' => 'Lead qualifié — pas de vente impulsive. Réponse d’un ingénieur sous 24h.',
        ];
    }

    /**
     * Règles de validation centralisées (source unique pour Action + Livewire).
     *
     * @return array<string, array<int, string|ValidationRule>>
     */
    public static function rules(): array
    {
        return [
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_subtitle' => ['required', 'string', 'max:600'],
            'hero_eyebrow' => ['required', 'string', 'max:120'],
            'hero_image' => ['nullable', 'string', 'max:2048'],
            'hero_cta_label' => ['nullable', 'string', 'max:80'],
            'hero_cta_url' => ['nullable', 'string', 'max:2048'],
            'hero_secondary_label' => ['nullable', 'string', 'max:80'],
            'hero_secondary_url' => ['nullable', 'string', 'max:2048'],
            'hero_slide2_eyebrow' => ['required', 'string', 'max:120'],
            'hero_slide2_title' => ['required', 'string', 'max:255'],
            'hero_slide2_subtitle' => ['required', 'string', 'max:600'],
            'hero_slide2_image' => ['nullable', 'string', 'max:2048'],
            'hero_slide2_cta_label' => ['nullable', 'string', 'max:80'],
            'hero_slide2_cta_url' => ['nullable', 'string', 'max:2048'],
            'hero_slide2_secondary_label' => ['nullable', 'string', 'max:80'],
            'hero_slide2_secondary_url' => ['nullable', 'string', 'max:2048'],
            'hero_slide3_eyebrow' => ['required', 'string', 'max:120'],
            'hero_slide3_title' => ['required', 'string', 'max:255'],
            'hero_slide3_subtitle' => ['required', 'string', 'max:600'],
            'hero_slide3_image' => ['nullable', 'string', 'max:2048'],
            'hero_slide3_cta_label' => ['nullable', 'string', 'max:80'],
            'hero_slide3_cta_url' => ['nullable', 'string', 'max:2048'],
            'hero_slide3_secondary_label' => ['nullable', 'string', 'max:80'],
            'hero_slide3_secondary_url' => ['nullable', 'string', 'max:2048'],
            'site_name' => ['required', 'string', 'max:100'],
            'site_logo' => ['nullable', 'string', 'max:2048'],
            'stats_projects_label' => ['required', 'string', 'max:80'],
            'stats_expertises_label' => ['required', 'string', 'max:80'],
            'stats_partners_label' => ['required', 'string', 'max:80'],
            'about_title' => ['required', 'string', 'max:255'],
            'about_content' => ['nullable', 'string', 'max:5000'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'sectors_title' => ['required', 'string', 'max:255'],
            'sectors_subtitle' => ['required', 'string', 'max:600'],
            'sector_btp_badge' => ['required', 'string', 'max:80'],
            'sector_btp_title' => ['required', 'string', 'max:255'],
            'sector_btp_desc' => ['required', 'string', 'max:500'],
            'sector_btp_arg' => ['required', 'string', 'max:120'],
            'sector_lot_badge' => ['required', 'string', 'max:80'],
            'sector_lot_title' => ['required', 'string', 'max:255'],
            'sector_lot_desc' => ['required', 'string', 'max:500'],
            'sector_lot_arg' => ['required', 'string', 'max:120'],
            'sector_agro_badge' => ['required', 'string', 'max:80'],
            'sector_agro_title' => ['required', 'string', 'max:255'],
            'sector_agro_desc' => ['required', 'string', 'max:500'],
            'sector_agro_arg' => ['required', 'string', 'max:120'],
            'portfolio_title' => ['required', 'string', 'max:255'],
            'portfolio_subtitle' => ['required', 'string', 'max:600'],
            'guarantees_title' => ['required', 'string', 'max:255'],
            'guarantees_subtitle' => ['required', 'string', 'max:600'],
            'guarantee1_title' => ['required', 'string', 'max:100'],
            'guarantee1_desc' => ['required', 'string', 'max:500'],
            'guarantee2_title' => ['required', 'string', 'max:100'],
            'guarantee2_desc' => ['required', 'string', 'max:500'],
            'guarantee3_title' => ['required', 'string', 'max:100'],
            'guarantee3_desc' => ['required', 'string', 'max:500'],
            'guarantee4_title' => ['required', 'string', 'max:100'],
            'guarantee4_desc' => ['required', 'string', 'max:500'],
            'process_title' => ['required', 'string', 'max:255'],
            'process_subtitle' => ['required', 'string', 'max:600'],
            'process1_title' => ['required', 'string', 'max:100'],
            'process1_subtitle' => ['required', 'string', 'max:80'],
            'process1_desc' => ['required', 'string', 'max:500'],
            'process2_title' => ['required', 'string', 'max:100'],
            'process2_subtitle' => ['required', 'string', 'max:80'],
            'process2_desc' => ['required', 'string', 'max:500'],
            'process3_title' => ['required', 'string', 'max:100'],
            'process3_subtitle' => ['required', 'string', 'max:80'],
            'process3_desc' => ['required', 'string', 'max:500'],
            'process4_title' => ['required', 'string', 'max:100'],
            'process4_subtitle' => ['required', 'string', 'max:80'],
            'process4_desc' => ['required', 'string', 'max:500'],
            'form_title' => ['required', 'string', 'max:255'],
            'form_subtitle' => ['required', 'string', 'max:600'],
            'form_note' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Groupe SiteSetting pour une clé.
     */
    public static function groupFor(string $key): string
    {
        if (str_starts_with($key, 'seo_')) {
            return 'seo';
        }

        if (in_array($key, ['site_name', 'site_logo'], true)) {
            return 'branding';
        }

        return 'general';
    }

    /**
     * Charge depuis SiteSetting avec fallback defaults (pour HomeController / Livewire mount).
     *
     * @return array<string, mixed>
     */
    public static function fromSettings(): array
    {
        $defaults = self::defaults();

        $loaded = [];
        foreach (self::HOMEPAGE_KEYS as $key) {
            $loaded[$key] = SiteSetting::get($key, $defaults[$key] ?? null);
            // Fallback explicite si get retourne null mais default non-null
            if ($loaded[$key] === null && isset($defaults[$key]) && $defaults[$key] !== null) {
                $loaded[$key] = $defaults[$key];
            }
        }

        // Normalise les nulls pour les champs required
        foreach (['hero_title', 'hero_subtitle', 'hero_eyebrow', 'hero_slide2_eyebrow', 'hero_slide2_title', 'hero_slide2_subtitle', 'hero_slide3_eyebrow', 'hero_slide3_title', 'hero_slide3_subtitle', 'stats_projects_label', 'stats_expertises_label', 'stats_partners_label', 'about_title', 'sectors_title', 'sectors_subtitle', 'sector_btp_badge', 'sector_btp_title', 'sector_btp_desc', 'sector_btp_arg', 'sector_lot_badge', 'sector_lot_title', 'sector_lot_desc', 'sector_lot_arg', 'sector_agro_badge', 'sector_agro_title', 'sector_agro_desc', 'sector_agro_arg', 'portfolio_title', 'portfolio_subtitle', 'guarantees_title', 'guarantees_subtitle', 'guarantee1_title', 'guarantee1_desc', 'guarantee2_title', 'guarantee2_desc', 'guarantee3_title', 'guarantee3_desc', 'guarantee4_title', 'guarantee4_desc', 'process_title', 'process_subtitle', 'process1_title', 'process1_subtitle', 'process1_desc', 'process2_title', 'process2_subtitle', 'process2_desc', 'process3_title', 'process3_subtitle', 'process3_desc', 'process4_title', 'process4_subtitle', 'process4_desc', 'form_title', 'form_subtitle', 'form_note'] as $k) {
            if ($loaded[$k] === null) {
                $loaded[$k] = $defaults[$k];
            }
        }

        return $loaded;
    }

    /**
     * @return list<string>
     */
    public static function homepageKeys(): array
    {
        return self::HOMEPAGE_KEYS;
    }
}
