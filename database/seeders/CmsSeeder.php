<?php

namespace Database\Seeders;

use App\Models\Expertise;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Program;
use App\Models\ProgramLot;
use App\Models\PublicProject;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CmsSeeder extends Seeder
{
    /**
     * Seed CMS vitrine with demo data — mono-entreprise, pas de filiales.
     */
    public function run(): void
    {
        // Settings — hero, contact, SEO, branding (logo)
        $settings = [
            ['key' => 'hero_title', 'value' => 'Bâtir l’avenir avec excellence', 'group' => 'general'],
            ['key' => 'hero_subtitle', 'value' => 'Entreprise BTP de référence — Bâtiment, Génie civil, VRD, Énergie, Aménagement. De l’étude à la réception.', 'group' => 'general'],
            ['key' => 'about_title', 'value' => 'À propos de SIBEA', 'group' => 'general'],
            ['key' => 'about_content', 'value' => "SIBEA est une entreprise mono-entreprise intervenant en Bâtiment, Génie civil, VRD, Aménagement, Lotissement et Énergie.\n\nOrganisation interne : Directions, Départements/Services, Équipes, Employés — sans agences ni filiales (CDC 1.1).\n\nNous accompagnons chaque projet de l’acquisition à la réception et à l’archivage, avec traçabilité, sécurité et performance.", 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'contact@sibea.ci', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '+225 27 22 00 00 00', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => 'Abidjan, Cocody — Côte d’Ivoire', 'group' => 'contact'],
            ['key' => 'seo_title', 'value' => 'SIBEA — BTP, Génie civil, VRD & Énergie', 'group' => 'seo'],
            ['key' => 'seo_description', 'value' => 'Entreprise BTP mono-entreprise : bâtiment, génie civil, VRD, aménagement, lotissement et énergie. De l’étude à la réception.', 'group' => 'seo'],
            ['key' => 'site_name', 'value' => 'SIBEA', 'group' => 'branding'],
            ['key' => 'site_logo', 'value' => null, 'group' => 'branding'],
        ];

        foreach ($settings as $s) {
            SiteSetting::firstOrCreate(['key' => $s['key']], $s);
        }

        // Expertises + Services
        $expertises = [
            ['title' => 'Bâtiment', 'excerpt' => 'Construction neuve, réhabilitation, bureaux, logements, industriel.'],
            ['title' => 'Génie civil', 'excerpt' => 'Ouvrages d’art, infrastructures, fondations profondes.'],
            ['title' => 'VRD & Aménagement', 'excerpt' => 'Voiries, réseaux divers, assainissement, aménagements urbains.'],
            ['title' => 'Énergie & Électricité', 'excerpt' => 'Réseaux MT/BT, solaire, efficacité énergétique.'],
        ];

        foreach ($expertises as $i => $data) {
            $exp = Expertise::firstOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'title' => $data['title'],
                    'excerpt' => $data['excerpt'],
                    'content' => $data['excerpt'].' Contenu détaillé de l’expertise '.$data['title'].'.',
                    'order' => $i,
                    'is_active' => true,
                ]
            );

            // 2 services per expertise
            foreach (['Conception & Études', 'Exécution & Pilotage'] as $j => $svcTitle) {
                $svcSlug = Str::slug($exp->title.' '.$svcTitle);
                Service::firstOrCreate(
                    ['slug' => $svcSlug],
                    [
                        'expertise_id' => $exp->id,
                        'title' => $svcTitle.' — '.$exp->title,
                        'excerpt' => 'Service '.$svcTitle.' pour '.$exp->title.'.',
                        'content' => 'Détails service '.$svcTitle.'.',
                        'order' => $j,
                        'is_active' => true,
                    ]
                );
            }
        }

        // Public Projects — réalisations
        $projects = [
            ['title' => 'Résidence Les Palmiers', 'category' => 'Bâtiment', 'location' => 'Abidjan', 'year' => 2024, 'client_name' => 'SCI Palmiers'],
            ['title' => 'Pont de Jacqueville', 'category' => 'Génie civil', 'location' => 'Jacqueville', 'year' => 2023, 'client_name' => 'État de Côte d’Ivoire'],
            ['title' => 'VRD Cité Verte', 'category' => 'VRD', 'location' => 'Yamoussoukro', 'year' => 2024, 'client_name' => 'Commune Yamoussoukro'],
        ];

        foreach ($projects as $proj) {
            $slug = Str::slug($proj['title']);
            PublicProject::firstOrCreate(
                ['slug' => $slug],
                [
                    'title' => $proj['title'],
                    'category' => $proj['category'],
                    'client_name' => $proj['client_name'],
                    'location' => $proj['location'],
                    'year' => $proj['year'],
                    'description' => 'Projet phare livré avec excellence opérationnelle et traçabilité complète.',
                    'is_featured' => true,
                    'is_published' => true,
                    'published_at' => now()->subDays(rand(10, 100)),
                ]
            );
        }

        // Posts — actualités
        $posts = [
            ['title' => 'Démarrage chantier Cité Verte : terrassement achevé', 'category' => 'Chantier'],
            ['title' => 'SIBEA certifiée ISO 9001 : démarche qualité renforcée', 'category' => 'RSE'],
            ['title' => 'Innovation : coffrage modulaire pour voiles béton', 'category' => 'Innovation'],
        ];

        foreach ($posts as $p) {
            $slug = Str::slug($p['title']);
            Post::firstOrCreate(
                ['slug' => $slug],
                [
                    'title' => $p['title'],
                    'category' => $p['category'],
                    'excerpt' => 'Actualité SIBEA — suivi de chantier et innovation BTP.',
                    'content' => 'Contenu détaillé de l’actualité. Suivi, photos, jalons.',
                    'is_published' => true,
                    'published_at' => now()->subDays(rand(1, 20)),
                ]
            );
        }

        // Partners
        foreach (['Lafarge', 'SGB', 'PFO Africa', 'Colas'] as $i => $name) {
            Partner::firstOrCreate(
                ['name' => $name],
                [
                    'website_url' => 'https://example.com',
                    'order' => $i,
                    'is_active' => true,
                ]
            );
        }

        // Testimonials
        foreach ([
            ['author' => 'Mme Koné', 'company' => 'SCI Palmiers', 'content' => 'SIBEA a livré dans les délais avec une qualité irréprochable. Chantier propre, reporting quotidien.'],
            ['author' => 'M. Diallo', 'company' => 'Mairie Jacqueville', 'content' => 'Professionnalisme et traçabilité exemplaires sur un ouvrage complexe.'],
        ] as $i => $t) {
            Testimonial::firstOrCreate(
                ['author_name' => $t['author'], 'company' => $t['company']],
                [
                    'role' => 'Maître d’ouvrage',
                    'content' => $t['content'],
                    'rating' => 5,
                    'order' => $i,
                    'is_active' => true,
                ]
            );
        }

        // Pages — histoire, vision
        $pages = [
            [
                'slug' => 'histoire',
                'title' => 'Notre histoire',
                'meta_title' => 'Notre histoire — SIBEA',
                'meta_description' => 'Découvrez l’histoire de SIBEA, entreprise BTP de référence en Côte d’Ivoire.',
                'excerpt' => 'De la création à aujourd’hui, le parcours d’une entreprise bâtie sur l’excellence.',
                'content' => "Fondée avec la vision de bâtir l’avenir, SIBEA a su s’imposer comme référence BTP mono-entreprise.\n\nChaque chantier est mené avec exigence, traçabilité et sécurité.",
                'order' => 0,
            ],
            [
                'slug' => 'vision',
                'title' => 'Notre vision',
                'meta_title' => 'Notre vision — SIBEA',
                'meta_description' => 'La vision SIBEA : excellence opérationnelle et innovation durable.',
                'excerpt' => 'Accompagner chaque projet de l’idée à la réception avec performance durable.',
                'content' => "Notre vision : bâtir des ouvrages durables, sûrs et performants.\n\nInnovation sobre, transparence et engagement RSE guident chaque décision.",
                'order' => 1,
            ],
        ];

        foreach ($pages as $p) {
            Page::firstOrCreate(
                ['slug' => $p['slug']],
                [
                    'title' => $p['title'],
                    'meta_title' => $p['meta_title'],
                    'meta_description' => $p['meta_description'],
                    'excerpt' => $p['excerpt'],
                    'content' => $p['content'],
                    'cover_image' => null,
                    'is_published' => true,
                    'published_at' => now()->subDays(rand(1, 10)),
                    'order' => $p['order'],
                ]
            );
        }

        // Menus — header & footer
        $headerMenu = Menu::firstOrCreate(
            ['slug' => 'header'],
            ['name' => 'Header', 'location' => 'header']
        );

        $footerMenu = Menu::firstOrCreate(
            ['slug' => 'footer'],
            ['name' => 'Footer', 'location' => 'footer']
        );

        $headerItems = [
            ['label' => 'Accueil', 'url' => '/', 'order' => 0],
            ['label' => 'Expertises', 'url' => '/expertises', 'order' => 1],
            ['label' => 'Réalisations', 'url' => '/realisations', 'order' => 2],
            ['label' => 'Programmes', 'url' => '/programmes', 'order' => 3],
            ['label' => 'Actualités', 'url' => '/actualites', 'order' => 4],
            ['label' => 'Équipe', 'url' => '/equipe', 'order' => 5],
            ['label' => 'À propos', 'url' => '/a-propos', 'order' => 6],
            ['label' => 'Contact', 'url' => '/contact', 'order' => 7],
        ];

        foreach ($headerItems as $item) {
            MenuItem::firstOrCreate(
                ['menu_id' => $headerMenu->id, 'label' => $item['label']],
                [
                    'url' => $item['url'],
                    'target' => '_self',
                    'order' => $item['order'],
                    'is_active' => true,
                ]
            );
        }

        $footerItems = [
            ['label' => 'Accueil', 'url' => '/', 'order' => 0],
            ['label' => 'Mentions légales', 'url' => '/mentions-legales', 'order' => 1],
            ['label' => 'Politique de confidentialité', 'url' => '/confidentialite', 'order' => 2],
            ['label' => 'Contact', 'url' => '/contact', 'order' => 3],
        ];

        foreach ($footerItems as $item) {
            MenuItem::firstOrCreate(
                ['menu_id' => $footerMenu->id, 'label' => $item['label']],
                [
                    'url' => $item['url'],
                    'target' => '_self',
                    'order' => $item['order'],
                    'is_active' => true,
                ]
            );
        }

        // Programs & Lots — vitrine lotissements (avec nouveau schéma plots, municipality=commune, district=quartier)
        $programsData = [
            [
                'slug' => 'les-jardins-de-cocody',
                'title' => 'Les Jardins de Cocody',
                'city' => 'Abidjan',
                'municipality' => 'Cocody',
                'district' => 'Riviera Golf',
                'total_area' => 25000.00,
                'excerpt' => 'Lotissement haut standing à Cocody — viabilisé, titres fonciers sécurisés.',
                'description' => "Programme Les Jardins de Cocody : 40 lots viabilisés, voiries, assainissement, électricité et eau potable.\n\nSécurité, espaces verts et proximité des commodités.",
                'cover_path' => null,
                'total_lots' => 3,
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'order' => 0,
            ],
            [
                'slug' => 'cite-verte-yamoussoukro',
                'title' => 'Cité Verte Yamoussoukro',
                'city' => 'Yamoussoukro',
                'municipality' => 'Yamoussoukro',
                'district' => 'Centre',
                'total_area' => 35000.00,
                'excerpt' => 'Éco-quartier à Yamoussoukro — lots résidentiels et commerces de proximité.',
                'description' => 'Cité Verte : 60 lots, aménagement paysager, VRD complets et démarche durable.',
                'cover_path' => null,
                'total_lots' => 3,
                'is_published' => true,
                'published_at' => now()->subDays(3),
                'order' => 1,
            ],
        ];

        foreach ($programsData as $progData) {
            $program = Program::firstOrCreate(
                ['slug' => $progData['slug']],
                $progData
            );

            $lots = [
                ['reference' => strtoupper($program->slug).'-LOT-0001', 'surface' => 350.00, 'price' => 25000000, 'status' => 'disponible', 'is_viabilise' => true, 'juridical_status' => 'ACD', 'published_at' => now()->subDays(5)],
                ['reference' => strtoupper($program->slug).'-LOT-0002', 'surface' => 300.00, 'price' => 22000000, 'status' => 'reserve', 'is_viabilise' => true, 'juridical_status' => 'TF', 'published_at' => now()->subDays(3)],
                ['reference' => strtoupper($program->slug).'-LOT-0003', 'surface' => 500.00, 'price' => 18000000, 'status' => 'vendu', 'is_viabilise' => true, 'juridical_status' => null, 'published_at' => now()->subDays(1)],
            ];

            foreach ($lots as $lotData) {
                ProgramLot::firstOrCreate(
                    ['reference' => $lotData['reference']],
                    array_merge($lotData, ['program_id' => $program->id])
                );
            }
        }
    }
}
