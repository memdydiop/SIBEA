<?php

use App\Actions\Audit\LogAuditAction;
use App\Actions\Cms\SaveHomepageSettings;
use App\Concerns\WithHomepageSettings;
use App\Models\SiteSetting;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Title('Page d’accueil')] class extends Component
{
    use WithFileUploads;
    use WithHomepageSettings;

    public string $hero_title = '';

    public string $hero_subtitle = '';

    public string $hero_eyebrow = '';

    public ?string $hero_image = null;

    public $hero_image_upload = null;

    public ?string $site_logo = null;

    public $site_logo_upload = null;

    public string $site_name = 'SIBEA';

    public ?string $hero_cta_label = null;

    public ?string $hero_cta_url = null;

    public ?string $hero_secondary_label = null;

    public ?string $hero_secondary_url = null;

    // Slide 2
    public string $hero_slide2_eyebrow = '';

    public string $hero_slide2_title = '';

    public string $hero_slide2_subtitle = '';

    public ?string $hero_slide2_image = null;

    public $hero_slide2_image_upload = null;

    public ?string $hero_slide2_cta_label = null;

    public ?string $hero_slide2_cta_url = null;

    public ?string $hero_slide2_secondary_label = null;

    public ?string $hero_slide2_secondary_url = null;

    // Slide 3
    public string $hero_slide3_eyebrow = '';

    public string $hero_slide3_title = '';

    public string $hero_slide3_subtitle = '';

    public ?string $hero_slide3_image = null;

    public $hero_slide3_image_upload = null;

    public ?string $hero_slide3_cta_label = null;

    public ?string $hero_slide3_cta_url = null;

    public ?string $hero_slide3_secondary_label = null;

    public ?string $hero_slide3_secondary_url = null;

    public string $stats_projects_label = '';

    public string $stats_expertises_label = '';

    public string $stats_partners_label = '';

    public string $sectors_title = '';

    public string $sectors_subtitle = '';

    public string $sector_btp_badge = '';

    public string $sector_btp_title = '';

    public string $sector_btp_desc = '';

    public string $sector_btp_arg = '';

    public string $sector_lot_badge = '';

    public string $sector_lot_title = '';

    public string $sector_lot_desc = '';

    public string $sector_lot_arg = '';

    public string $sector_agro_badge = '';

    public string $sector_agro_title = '';

    public string $sector_agro_desc = '';

    public string $sector_agro_arg = '';

    public string $portfolio_title = '';

    public string $portfolio_subtitle = '';

    public string $guarantees_title = '';

    public string $guarantees_subtitle = '';

    public string $guarantee1_title = '';

    public string $guarantee1_desc = '';

    public string $guarantee2_title = '';

    public string $guarantee2_desc = '';

    public string $guarantee3_title = '';

    public string $guarantee3_desc = '';

    public string $guarantee4_title = '';

    public string $guarantee4_desc = '';

    public string $process_title = '';

    public string $process_subtitle = '';

    public string $process1_title = '';

    public string $process1_subtitle = '';

    public string $process1_desc = '';

    public string $process2_title = '';

    public string $process2_subtitle = '';

    public string $process2_desc = '';

    public string $process3_title = '';

    public string $process3_subtitle = '';

    public string $process3_desc = '';

    public string $process4_title = '';

    public string $process4_subtitle = '';

    public string $process4_desc = '';

    public string $form_title = '';

    public string $form_subtitle = '';

    public string $form_note = '';

    public string $about_title = '';

    public ?string $about_content = null;

    public ?string $seo_title = null;

    public ?string $seo_description = null;

    public function mount(): void
    {
        Gate::authorize('viewAny', SiteSetting::class);

        $data = $this->loadHomepageData();

        $this->hero_title = $data['hero_title'];
        $this->hero_subtitle = $data['hero_subtitle'];
        $this->hero_eyebrow = $data['hero_eyebrow'];
        $this->hero_image = $data['hero_image'];
        $this->site_logo = $data['site_logo'];
        $this->site_name = $data['site_name'] ?? config('app.name', 'SIBEA');
        $this->hero_cta_label = $data['hero_cta_label'];
        $this->hero_cta_url = $data['hero_cta_url'];
        $this->hero_secondary_label = $data['hero_secondary_label'];
        $this->hero_secondary_url = $data['hero_secondary_url'];
        $this->hero_slide2_eyebrow = $data['hero_slide2_eyebrow'];
        $this->hero_slide2_title = $data['hero_slide2_title'];
        $this->hero_slide2_subtitle = $data['hero_slide2_subtitle'];
        $this->hero_slide2_image = $data['hero_slide2_image'];
        $this->hero_slide2_cta_label = $data['hero_slide2_cta_label'];
        $this->hero_slide2_cta_url = $data['hero_slide2_cta_url'];
        $this->hero_slide2_secondary_label = $data['hero_slide2_secondary_label'];
        $this->hero_slide2_secondary_url = $data['hero_slide2_secondary_url'];
        $this->hero_slide3_eyebrow = $data['hero_slide3_eyebrow'];
        $this->hero_slide3_title = $data['hero_slide3_title'];
        $this->hero_slide3_subtitle = $data['hero_slide3_subtitle'];
        $this->hero_slide3_image = $data['hero_slide3_image'];
        $this->hero_slide3_cta_label = $data['hero_slide3_cta_label'];
        $this->hero_slide3_cta_url = $data['hero_slide3_cta_url'];
        $this->hero_slide3_secondary_label = $data['hero_slide3_secondary_label'];
        $this->hero_slide3_secondary_url = $data['hero_slide3_secondary_url'];
        $this->stats_projects_label = $data['stats_projects_label'];
        $this->stats_expertises_label = $data['stats_expertises_label'];
        $this->stats_partners_label = $data['stats_partners_label'];
        $this->sectors_title = $data['sectors_title'];
        $this->sectors_subtitle = $data['sectors_subtitle'];
        $this->sector_btp_badge = $data['sector_btp_badge'];
        $this->sector_btp_title = $data['sector_btp_title'];
        $this->sector_btp_desc = $data['sector_btp_desc'];
        $this->sector_btp_arg = $data['sector_btp_arg'];
        $this->sector_lot_badge = $data['sector_lot_badge'];
        $this->sector_lot_title = $data['sector_lot_title'];
        $this->sector_lot_desc = $data['sector_lot_desc'];
        $this->sector_lot_arg = $data['sector_lot_arg'];
        $this->sector_agro_badge = $data['sector_agro_badge'];
        $this->sector_agro_title = $data['sector_agro_title'];
        $this->sector_agro_desc = $data['sector_agro_desc'];
        $this->sector_agro_arg = $data['sector_agro_arg'];
        $this->portfolio_title = $data['portfolio_title'];
        $this->portfolio_subtitle = $data['portfolio_subtitle'];
        $this->guarantees_title = $data['guarantees_title'];
        $this->guarantees_subtitle = $data['guarantees_subtitle'];
        $this->guarantee1_title = $data['guarantee1_title'];
        $this->guarantee1_desc = $data['guarantee1_desc'];
        $this->guarantee2_title = $data['guarantee2_title'];
        $this->guarantee2_desc = $data['guarantee2_desc'];
        $this->guarantee3_title = $data['guarantee3_title'];
        $this->guarantee3_desc = $data['guarantee3_desc'];
        $this->guarantee4_title = $data['guarantee4_title'];
        $this->guarantee4_desc = $data['guarantee4_desc'];
        $this->process_title = $data['process_title'];
        $this->process_subtitle = $data['process_subtitle'];
        $this->process1_title = $data['process1_title'];
        $this->process1_subtitle = $data['process1_subtitle'];
        $this->process1_desc = $data['process1_desc'];
        $this->process2_title = $data['process2_title'];
        $this->process2_subtitle = $data['process2_subtitle'];
        $this->process2_desc = $data['process2_desc'];
        $this->process3_title = $data['process3_title'];
        $this->process3_subtitle = $data['process3_subtitle'];
        $this->process3_desc = $data['process3_desc'];
        $this->process4_title = $data['process4_title'];
        $this->process4_subtitle = $data['process4_subtitle'];
        $this->process4_desc = $data['process4_desc'];
        $this->form_title = $data['form_title'];
        $this->form_subtitle = $data['form_subtitle'];
        $this->form_note = $data['form_note'];
        $this->about_title = $data['about_title'];
        $this->about_content = $data['about_content'];
        $this->seo_title = $data['seo_title'];
        $this->seo_description = $data['seo_description'];
    }

    public function save(SaveHomepageSettings $action, LogAuditAction $audit): void
    {
        Gate::authorize('viewAny', SiteSetting::class);

        if ($this->hero_image_upload) {
            $this->hero_image = $this->storeHomepageImage($this->hero_image_upload);
        }

        if ($this->hero_slide2_image_upload) {
            $this->hero_slide2_image = $this->storeHomepageImage($this->hero_slide2_image_upload);
        }

        if ($this->hero_slide3_image_upload) {
            $this->hero_slide3_image = $this->storeHomepageImage($this->hero_slide3_image_upload);
        }

        if ($this->site_logo_upload) {
            $this->site_logo = $this->storeBrandingLogo($this->site_logo_upload);
        }

        $input = [
            'hero_title' => $this->hero_title,
            'hero_subtitle' => $this->hero_subtitle,
            'hero_eyebrow' => $this->hero_eyebrow,
            'hero_image' => $this->hero_image,
            'hero_cta_label' => $this->hero_cta_label,
            'hero_cta_url' => $this->hero_cta_url,
            'hero_secondary_label' => $this->hero_secondary_label,
            'hero_secondary_url' => $this->hero_secondary_url,
            'hero_slide2_eyebrow' => $this->hero_slide2_eyebrow,
            'hero_slide2_title' => $this->hero_slide2_title,
            'hero_slide2_subtitle' => $this->hero_slide2_subtitle,
            'hero_slide2_image' => $this->hero_slide2_image,
            'hero_slide2_cta_label' => $this->hero_slide2_cta_label,
            'hero_slide2_cta_url' => $this->hero_slide2_cta_url,
            'hero_slide2_secondary_label' => $this->hero_slide2_secondary_label,
            'hero_slide2_secondary_url' => $this->hero_slide2_secondary_url,
            'hero_slide3_eyebrow' => $this->hero_slide3_eyebrow,
            'hero_slide3_title' => $this->hero_slide3_title,
            'hero_slide3_subtitle' => $this->hero_slide3_subtitle,
            'hero_slide3_image' => $this->hero_slide3_image,
            'hero_slide3_cta_label' => $this->hero_slide3_cta_label,
            'hero_slide3_cta_url' => $this->hero_slide3_cta_url,
            'hero_slide3_secondary_label' => $this->hero_slide3_secondary_label,
            'hero_slide3_secondary_url' => $this->hero_slide3_secondary_url,
            'site_logo' => $this->site_logo,
            'site_name' => $this->site_name,
            'stats_projects_label' => $this->stats_projects_label,
            'stats_expertises_label' => $this->stats_expertises_label,
            'stats_partners_label' => $this->stats_partners_label,
            'sectors_title' => $this->sectors_title,
            'sectors_subtitle' => $this->sectors_subtitle,
            'sector_btp_badge' => $this->sector_btp_badge,
            'sector_btp_title' => $this->sector_btp_title,
            'sector_btp_desc' => $this->sector_btp_desc,
            'sector_btp_arg' => $this->sector_btp_arg,
            'sector_lot_badge' => $this->sector_lot_badge,
            'sector_lot_title' => $this->sector_lot_title,
            'sector_lot_desc' => $this->sector_lot_desc,
            'sector_lot_arg' => $this->sector_lot_arg,
            'sector_agro_badge' => $this->sector_agro_badge,
            'sector_agro_title' => $this->sector_agro_title,
            'sector_agro_desc' => $this->sector_agro_desc,
            'sector_agro_arg' => $this->sector_agro_arg,
            'portfolio_title' => $this->portfolio_title,
            'portfolio_subtitle' => $this->portfolio_subtitle,
            'guarantees_title' => $this->guarantees_title,
            'guarantees_subtitle' => $this->guarantees_subtitle,
            'guarantee1_title' => $this->guarantee1_title,
            'guarantee1_desc' => $this->guarantee1_desc,
            'guarantee2_title' => $this->guarantee2_title,
            'guarantee2_desc' => $this->guarantee2_desc,
            'guarantee3_title' => $this->guarantee3_title,
            'guarantee3_desc' => $this->guarantee3_desc,
            'guarantee4_title' => $this->guarantee4_title,
            'guarantee4_desc' => $this->guarantee4_desc,
            'process_title' => $this->process_title,
            'process_subtitle' => $this->process_subtitle,
            'process1_title' => $this->process1_title,
            'process1_subtitle' => $this->process1_subtitle,
            'process1_desc' => $this->process1_desc,
            'process2_title' => $this->process2_title,
            'process2_subtitle' => $this->process2_subtitle,
            'process2_desc' => $this->process2_desc,
            'process3_title' => $this->process3_title,
            'process3_subtitle' => $this->process3_subtitle,
            'process3_desc' => $this->process3_desc,
            'process4_title' => $this->process4_title,
            'process4_subtitle' => $this->process4_subtitle,
            'process4_desc' => $this->process4_desc,
            'form_title' => $this->form_title,
            'form_subtitle' => $this->form_subtitle,
            'form_note' => $this->form_note,
            'about_title' => $this->about_title,
            'about_content' => $this->about_content,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
        ];

        $action($input, $audit);

        Flux::toast(variant: 'success', text: __('Page d’accueil mise à jour.'));

        $this->hero_image_upload = null;
        $this->hero_slide2_image_upload = null;
        $this->hero_slide3_image_upload = null;
        $this->site_logo_upload = null;
    }
}; ?>

<section class="w-full">
    <x-page-header :title="__('Page d’accueil')" :subtitle="__('Édition vitrine 100% — hero slideshow 3 slides, stats, à propos, SEO. Images stockées dans cms/home.')" />

    <form wire:submit="save" class="space-y-6">
        <div class="grid gap-4 xl:grid-cols-3">
            {{-- Slide 1 — Hero principal --}}
            <flux:card class="">
                <x-card-header title="Slide 1 — Hero principal" subtitle="BTP · Génie civil · VRD · Énergie">
                    <x-slot:actions><flux:badge size="sm">general</flux:badge></x-slot:actions>
                </x-card-header>
                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:input wire:model="hero_eyebrow" :label="__('Sur-titre slide 1')" required placeholder="BTP · Génie civil · VRD · Énergie" />
                    <div></div>
                    <div class="sm:col-span-2">
                        <flux:input wire:model="hero_title" :label="__('Titre slide 1')" required placeholder="Bâtir l’avenir avec excellence" />
                    </div>
                    <div class="sm:col-span-2">
                        <flux:textarea wire:model="hero_subtitle" :label="__('Sous-titre slide 1')" rows="3" required placeholder="Entreprise BTP de référence..." />
                    </div>
                    <flux:input wire:model="hero_image" :label="__('Image slide 1 URL')" placeholder="https:// ou /storage/cms/home/..." />
                    <flux:input type="file" wire:model="hero_image_upload" :label="__('Ou fichier slide 1')" accept="image/jpeg,image/png,image/webp" />
                    <div class="sm:col-span-2 -mt-2 text-xs text-zinc-400">{{ __('Vide = fallback gradient primary-900') }}</div>
                    @if($hero_image)
                        <div class="sm:col-span-2">
                            <div class="text-xs text-zinc-500 mb-2">{{ __('Aperçu :') }}</div>
                            <img src="{{ $hero_image }}" alt="hero preview" class="h-32 w-auto rounded border object-cover">
                            <div class="mt-1"><flux:button variant="ghost" size="sm" icon="trash" wire:click="$set('hero_image', null)" class="text-red-500 text-xs">{{ __('Retirer') }}</flux:button></div>
                        </div>
                    @endif
                    @if($hero_image_upload)
                        <div class="sm:col-span-2 text-xs text-zinc-500">{{ __('Nouveau fichier sélectionné — sera stocké dans cms/home') }}</div>
                    @endif
                    <flux:input wire:model="hero_cta_label" :label="__('CTA principal')" placeholder="Demander un devis" />
                    <flux:input wire:model="hero_cta_url" :label="__('URL CTA principal')" placeholder="/devis" />
                    <flux:input wire:model="hero_secondary_label" :label="__('CTA secondaire')" placeholder="Voir nos réalisations" />
                    <flux:input wire:model="hero_secondary_url" :label="__('URL CTA secondaire')" placeholder="/realisations" />
                </div>
            </flux:card>

            {{-- Slide 2 — Programmes --}}
            <flux:card class="">
                <x-card-header title="Slide 2 — Programmes" subtitle="Lotissements — terrains viabilisés">
                    <x-slot:actions><flux:badge size="sm">general</flux:badge></x-slot:actions>
                </x-card-header>
                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:input wire:model="hero_slide2_eyebrow" :label="__('Sur-titre slide 2')" required placeholder="Programmes immobiliers" />
                    <div></div>
                    <div class="sm:col-span-2">
                        <flux:input wire:model="hero_slide2_title" :label="__('Titre slide 2')" required placeholder="Lotissements viabilisés, titres sécurisés" />
                    </div>
                    <div class="sm:col-span-2">
                        <flux:textarea wire:model="hero_slide2_subtitle" :label="__('Sous-titre slide 2')" rows="3" required />
                    </div>
                    <flux:input wire:model="hero_slide2_image" :label="__('Image slide 2 URL')" placeholder="https:// ou /storage/cms/home/... (vide = cover projet phare)" />
                    <flux:input type="file" wire:model="hero_slide2_image_upload" :label="__('Ou fichier slide 2')" accept="image/jpeg,image/png,image/webp" />
                    <div class="sm:col-span-2 -mt-2 text-xs text-zinc-400">{{ __('Vide = fallback sur cover du 1er projet phare ou hero_image') }}</div>
                    @if($hero_slide2_image)
                        <div class="sm:col-span-2">
                            <div class="text-xs text-zinc-500 mb-2">{{ __('Aperçu :') }}</div>
                            <img src="{{ $hero_slide2_image }}" alt="slide2 preview" class="h-32 w-auto rounded border object-cover">
                            <div class="mt-1"><flux:button variant="ghost" size="sm" icon="trash" wire:click="$set('hero_slide2_image', null)" class="text-red-500 text-xs">{{ __('Retirer') }}</flux:button></div>
                        </div>
                    @endif
                    <flux:input wire:model="hero_slide2_cta_label" :label="__('CTA principal')" placeholder="Découvrir les programmes" />
                    <flux:input wire:model="hero_slide2_cta_url" :label="__('URL CTA principal')" placeholder="/programmes" />
                    <flux:input wire:model="hero_slide2_secondary_label" :label="__('CTA secondaire')" placeholder="Demander un devis" />
                    <flux:input wire:model="hero_slide2_secondary_url" :label="__('URL CTA secondaire')" placeholder="/devis" />
                </div>
            </flux:card>

            {{-- Slide 3 — Savoir-faire --}}
            <flux:card class="">
                <x-card-header title="Slide 3 — Savoir-faire" subtitle="Infrastructures — chantiers maîtrisés">
                    <x-slot:actions><flux:badge size="sm">general</flux:badge></x-slot:actions>
                </x-card-header>
                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:input wire:model="hero_slide3_eyebrow" :label="__('Sur-titre slide 3')" required placeholder="Savoir-faire SIBEA" />
                    <div></div>
                    <div class="sm:col-span-2">
                        <flux:input wire:model="hero_slide3_title" :label="__('Titre slide 3')" required placeholder="Infrastructures durables, chantiers maîtrisés" />
                    </div>
                    <div class="sm:col-span-2">
                        <flux:textarea wire:model="hero_slide3_subtitle" :label="__('Sous-titre slide 3')" rows="3" required />
                    </div>
                    <flux:input wire:model="hero_slide3_image" :label="__('Image slide 3 URL')" placeholder="https:// ou /storage/cms/home/... (vide = cover projet)" />
                    <flux:input type="file" wire:model="hero_slide3_image_upload" :label="__('Ou fichier slide 3')" accept="image/jpeg,image/png,image/webp" />
                    <div class="sm:col-span-2 -mt-2 text-xs text-zinc-400">{{ __('Vide = fallback sur cover 2e projet phare') }}</div>
                    @if($hero_slide3_image)
                        <div class="sm:col-span-2">
                            <div class="text-xs text-zinc-500 mb-2">{{ __('Aperçu :') }}</div>
                            <img src="{{ $hero_slide3_image }}" alt="slide3 preview" class="h-32 w-auto rounded border object-cover">
                            <div class="mt-1"><flux:button variant="ghost" size="sm" icon="trash" wire:click="$set('hero_slide3_image', null)" class="text-red-500 text-xs">{{ __('Retirer') }}</flux:button></div>
                        </div>
                    @endif
                    <flux:input wire:model="hero_slide3_cta_label" :label="__('CTA principal')" placeholder="Nos réalisations" />
                    <flux:input wire:model="hero_slide3_cta_url" :label="__('URL CTA principal')" placeholder="/realisations" />
                    <flux:input wire:model="hero_slide3_secondary_label" :label="__('CTA secondaire')" placeholder="Nous contacter" />
                    <flux:input wire:model="hero_slide3_secondary_url" :label="__('URL CTA secondaire')" placeholder="/contact" />
                </div>
            </flux:card>
        </div>
        

        <div class="grid gap-4 xl:grid-cols-4">
        {{-- Identité / Logo — branding --}}
        <flux:card class="col-span-3">
            <x-card-header title="Identité — Logo & nom" subtitle="Logo header & sidebar">
                <x-slot:actions><flux:badge size="sm">branding</flux:badge></x-slot:actions>
            </x-card-header>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="site_name" :label="__('Nom du site')" required placeholder="SIBEA" />
                <div></div>
                <flux:input wire:model="site_logo" :label="__('Logo URL')" placeholder="https:// ou /storage/cms/branding/..." />
                <flux:input type="file" wire:model="site_logo_upload" :label="__('Ou fichier logo (png,webp,svg,jpeg max 1Mo, max 2000x2000)')" accept="image/jpeg,image/png,image/webp,image/svg+xml" />
                <div class="sm:col-span-2 -mt-2"><a href="{{ route('admin.media') }}" target="_blank" class="text-xs text-zinc-500 underline hover:text-zinc-700">{{ __('Ouvrir la médiathèque') }} →</a> <span class="text-xs text-zinc-400">{{ __('ou uploader ci-dessus (cms/branding)') }}</span></div>
                @if($site_logo)
                    <div class="sm:col-span-2">
                        <div class="text-xs text-zinc-500 mb-2">{{ __('Aperçu logo actuel :') }}</div>
                        <div class="flex items-center gap-4 p-4 bg-primary-900 rounded border">
                            <img src="{{ $site_logo }}" alt="logo preview" class="h-10 w-auto object-contain bg-white/10 rounded p-1">
                            <span class="text-sm text-white/80">{{ $site_name }}</span>
                        </div>
                        <div class="mt-2"><flux:button variant="ghost" size="sm" icon="trash" wire:click="$set('site_logo', null)" class="text-red-500">{{ __('Retirer le logo') }}</flux:button> <span class="text-xs text-zinc-400">{{ __('Reviendra au badge SI + SIBEA') }}</span></div>
                    </div>
                @endif
                @if($site_logo_upload)
                    <div class="sm:col-span-2 text-xs text-zinc-500">{{ __('Nouveau fichier sélectionné — sera stocké dans cms/branding') }}</div>
                @endif
            </div>
        </flux:card>

        {{-- Stats labels --}}
        <flux:card class="">
            <x-card-header title="Statistiques (labels)" subtitle="Labels bande 4 colonnes chiffres">
                <x-slot:actions><flux:badge size="sm">general</flux:badge></x-slot:actions>
            </x-card-header>
            <div class="p-4 grid grid-cols-1  gap-4">
                <flux:input wire:model="stats_projects_label" :label="__('Label projets')" required placeholder="Projets livrés" />
                <flux:input wire:model="stats_expertises_label" :label="__('Label expertises')" required placeholder="Expertises" />
                <flux:input wire:model="stats_partners_label" :label="__('Label partenaires')" required placeholder="Partenaires" />
            </div>
            <div class="px-4 pb-4 text-xs text-zinc-500">{{ __('Les compteurs restent calculés depuis les modèles PublicProject, Expertise, Partner. Bande 4 cols ajoutée (20+ ans statique).') }}</div>
        </flux:card>
        </div>

        {{-- Secteurs d’activité (3) --}}
        <flux:card class="!p-0 overflow-hidden">
            <x-card-header title="Secteurs d’activité — 3 piliers" subtitle="BTP / Lotissement / Agro — grille landing">
                <x-slot:actions><flux:badge size="sm">general</flux:badge></x-slot:actions>
            </x-card-header>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:input wire:model="sectors_title" :label="__('Titre section secteurs')" required placeholder="Trois expertises, un seul interlocuteur" class="sm:col-span-2" />
                    <div class="sm:col-span-2"><flux:textarea wire:model="sectors_subtitle" :label="__('Sous-titre secteurs')" rows="2" required /></div>
                </div>
                <div class="grid gap-6 lg:grid-cols-3">
                    <div class="rounded-xl border border-border bg-zinc-50 p-4 space-y-3">
                        <div class="text-xs font-bold uppercase tracking-wide text-primary-700">{{ __('BTP & Génie Civil') }}</div>
                        <flux:input wire:model="sector_btp_badge" :label="__('Badge BTP')" required placeholder="BTP & Génie Civil" />
                        <flux:input wire:model="sector_btp_title" :label="__('Titre BTP')" required />
                        <flux:textarea wire:model="sector_btp_desc" :label="__('Description BTP')" rows="3" required />
                        <flux:input wire:model="sector_btp_arg" :label="__('Argument massue BTP')" required placeholder="Respect du budget · Solidité financière" />
                    </div>
                    <div class="rounded-xl border border-border bg-zinc-50 p-4 space-y-3">
                        <div class="text-xs font-bold uppercase tracking-wide text-primary-700">{{ __('Lotissement & Aménagement') }}</div>
                        <flux:input wire:model="sector_lot_badge" :label="__('Badge Lotissement')" required />
                        <flux:input wire:model="sector_lot_title" :label="__('Titre Lotissement')" required />
                        <flux:textarea wire:model="sector_lot_desc" :label="__('Description Lotissement')" rows="3" required />
                        <flux:input wire:model="sector_lot_arg" :label="__('Argument Lotissement')" required />
                    </div>
                    <div class="rounded-xl border border-border bg-zinc-50 p-4 space-y-3">
                        <div class="text-xs font-bold uppercase tracking-wide text-success">{{ __('Agro-industrie') }}</div>
                        <flux:input wire:model="sector_agro_badge" :label="__('Badge Agro')" required />
                        <flux:input wire:model="sector_agro_title" :label="__('Titre Agro')" required />
                        <flux:textarea wire:model="sector_agro_desc" :label="__('Description Agro')" rows="3" required />
                        <flux:input wire:model="sector_agro_arg" :label="__('Argument Agro')" required />
                    </div>
                </div>
            </div>
        </flux:card>

        {{-- Portfolio --}}
        <flux:card class="!p-0 overflow-hidden">
            <x-card-header title="Portfolio — Réalisations" subtitle="On achète ce que l’on voit — Surface / Durée">
                <x-slot:actions><flux:badge size="sm">general</flux:badge></x-slot:actions>
            </x-card-header>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="portfolio_title" :label="__('Titre portfolio')" required placeholder="On achète ce que l’on voit" />
                <flux:textarea wire:model="portfolio_subtitle" :label="__('Sous-titre portfolio')" rows="2" required class="sm:col-span-2" />
            </div>
        </flux:card>

        {{-- Garanties & Engagements — 4 blocs --}}
        <flux:card class="!p-0 overflow-hidden">
            <x-card-header title="Garanties & Engagements — 4 blocs" subtitle="Rassurance technique / conformité">
                <x-slot:actions><flux:badge size="sm">general</flux:badge></x-slot:actions>
            </x-card-header>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:input wire:model="guarantees_title" :label="__('Titre garanties')" required class="sm:col-span-2" />
                    <div class="sm:col-span-2"><flux:textarea wire:model="guarantees_subtitle" :label="__('Sous-titre garanties')" rows="2" required /></div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-border bg-zinc-50 p-4 space-y-2">
                        <flux:input wire:model="guarantee1_title" :label="__('Garantie 1 — titre')" required placeholder="Respect des délais & budgets" />
                        <flux:textarea wire:model="guarantee1_desc" :label="__('Description 1')" rows="2" required />
                    </div>
                    <div class="rounded-xl border border-border bg-zinc-50 p-4 space-y-2">
                        <flux:input wire:model="guarantee2_title" :label="__('Garantie 2 — titre')" required placeholder="Conformité & Sécurité QHSE" />
                        <flux:textarea wire:model="guarantee2_desc" :label="__('Description 2')" rows="2" required />
                    </div>
                    <div class="rounded-xl border border-border bg-zinc-50 p-4 space-y-2">
                        <flux:input wire:model="guarantee3_title" :label="__('Garantie 3 — titre')" required placeholder="Assurances & garanties" />
                        <flux:textarea wire:model="guarantee3_desc" :label="__('Description 3')" rows="2" required />
                    </div>
                    <div class="rounded-xl border border-border bg-zinc-50 p-4 space-y-2">
                        <flux:input wire:model="guarantee4_title" :label="__('Garantie 4 — titre')" required placeholder="Traçabilité & transparence" />
                        <flux:textarea wire:model="guarantee4_desc" :label="__('Description 4')" rows="2" required />
                    </div>
                </div>
            </div>
        </flux:card>

        {{-- Processus 4 étapes --}}
        <flux:card class="!p-0 overflow-hidden">
            <x-card-header title="Processus — 4 étapes" subtitle="Étude → Chiffrage → Exécution → Livraison">
                <x-slot:actions><flux:badge size="sm">general</flux:badge></x-slot:actions>
            </x-card-header>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:input wire:model="process_title" :label="__('Titre process')" required class="sm:col-span-2" />
                    <div class="sm:col-span-2"><flux:textarea wire:model="process_subtitle" :label="__('Sous-titre process')" rows="2" required /></div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl border border-border bg-zinc-50 p-4 space-y-2">
                        <div class="text-xs font-bold text-primary-700">01</div>
                        <flux:input wire:model="process1_title" :label="__('Étape 1 titre')" required placeholder="Étude & Diagnostic" />
                        <flux:input wire:model="process1_subtitle" :label="__('Sous-titre 1')" required placeholder="Bureau d’études" />
                        <flux:textarea wire:model="process1_desc" :label="__('Description 1')" rows="2" required />
                    </div>
                    <div class="rounded-xl border border-border bg-zinc-50 p-4 space-y-2">
                        <div class="text-xs font-bold text-primary-700">02</div>
                        <flux:input wire:model="process2_title" :label="__('Étape 2 titre')" required placeholder="Chiffrage & Planification" />
                        <flux:input wire:model="process2_subtitle" :label="__('Sous-titre 2')" required placeholder="Devis détaillé" />
                        <flux:textarea wire:model="process2_desc" :label="__('Description 2')" rows="2" required />
                    </div>
                    <div class="rounded-xl border border-border bg-zinc-50 p-4 space-y-2">
                        <div class="text-xs font-bold text-primary-700">03</div>
                        <flux:input wire:model="process3_title" :label="__('Étape 3 titre')" required placeholder="Exécution & Suivi" />
                        <flux:input wire:model="process3_subtitle" :label="__('Sous-titre 3')" required placeholder="Suivi chantier" />
                        <flux:textarea wire:model="process3_desc" :label="__('Description 3')" rows="2" required />
                    </div>
                    <div class="rounded-xl border border-border bg-zinc-50 p-4 space-y-2">
                        <div class="text-xs font-bold text-primary-700">04</div>
                        <flux:input wire:model="process4_title" :label="__('Étape 4 titre')" required placeholder="Livraison & Réception" />
                        <flux:input wire:model="process4_subtitle" :label="__('Sous-titre 4')" required placeholder="Garantie décennale" />
                        <flux:textarea wire:model="process4_desc" :label="__('Description 4')" rows="2" required />
                    </div>
                </div>
            </div>
        </flux:card>

        {{-- Formulaire conversion --}}
        <flux:card class="!p-0 overflow-hidden">
            <x-card-header title="Formulaire — Conversion" subtitle="Cœur landing : Nom/Entreprise/Téléphone/Email/Type/Description">
                <x-slot:actions><flux:badge size="sm">general</flux:badge></x-slot:actions>
            </x-card-header>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="form_title" :label="__('Titre formulaire')" required placeholder="Parlons de votre projet" class="sm:col-span-2" />
                <div class="sm:col-span-2"><flux:textarea wire:model="form_subtitle" :label="__('Sous-titre formulaire')" rows="2" required /></div>
                <flux:input wire:model="form_note" :label="__('Note bas formulaire')" required placeholder="Lead qualifié — pas de vente impulsive. Réponse d’un ingénieur sous 24h." class="sm:col-span-2" />
            </div>
        </flux:card>

        {{-- À propos --}}
        <flux:card class="">
            <x-card-header title="À propos (vitrine homepage)" subtitle="Affiché sous la bande chiffres">
                <x-slot:actions><flux:badge size="sm">general</flux:badge></x-slot:actions>
            </x-card-header>
            <div class="p-4 space-y-4">
                <flux:input wire:model="about_title" :label="__('Titre à propos')" required placeholder="À propos de SIBEA" />
                <flux:textarea wire:model="about_content" :label="__('Contenu à propos')" rows="5" placeholder="Texte libre affiché en preview et potentiellement sur la homepage..." />
            </div>
        </flux:card>

        {{-- SEO --}}
        <flux:card class="">
            <x-card-header title="SEO" subtitle="Meta title & description">
                <x-slot:actions><flux:badge size="sm">seo</flux:badge></x-slot:actions>
            </x-card-header>
            <div class="p-4 grid grid-cols-1 gap-4">
                <flux:input wire:model="seo_title" :label="__('Meta title (seo_title)')" placeholder="SIBEA — BTP, Génie civil, VRD & Énergie" />
                <flux:textarea wire:model="seo_description" :label="__('Meta description (seo_description)')" rows="3" placeholder="Entreprise BTP mono-entreprise..." />
            </div>
        </flux:card>

        <div class="flex justify-between items-center">
            <a href="{{ route('home') }}" target="_blank" class="text-sm text-zinc-500 hover:text-zinc-700 underline">{{ __('Voir le site') }} →</a>
            <div class="flex gap-2">
                <flux:button variant="primary" type="submit" icon="check">{{ __('Enregistrer') }}</flux:button>
            </div>
        </div>
    </form>

    {{-- Preview 3 slides --}}
    <flux:card class="mt-10 ">
        <x-card-header title="Prévisualisation — 3 slides" subtitle="Rendu vitrine hero slideshow"></x-card-header>
        @php $previewSlides = [
            ['eyebrow' => $hero_eyebrow ?: 'BTP · Génie civil · VRD · Énergie', 'title' => $hero_title, 'subtitle' => $hero_subtitle, 'image' => $hero_image, 'cta' => $hero_cta_label, 'cta2' => $hero_secondary_label],
            ['eyebrow' => $hero_slide2_eyebrow, 'title' => $hero_slide2_title, 'subtitle' => $hero_slide2_subtitle, 'image' => $hero_slide2_image ?? $hero_image, 'cta' => $hero_slide2_cta_label, 'cta2' => $hero_slide2_secondary_label],
            ['eyebrow' => $hero_slide3_eyebrow, 'title' => $hero_slide3_title, 'subtitle' => $hero_slide3_subtitle, 'image' => $hero_slide3_image ?? $hero_image, 'cta' => $hero_slide3_cta_label, 'cta2' => $hero_slide3_secondary_label],
        ]; @endphp
        @foreach($previewSlides as $i => $s)
            <div class="relative overflow-hidden bg-primary-900 text-white border-t border-white/10 first:border-t-0">
                <div class="absolute inset-0">
                    @if($s['image'])
                        <img src="{{ $s['image'] }}" alt="" class="h-full w-full object-cover opacity-30">
                    @else
                        <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
                    @endif
                    <div class="absolute inset-0 bg-primary-900/70"></div>
                </div>
                <div class="relative p-6 sm:p-7">
                    <div class="text-xs font-semibold uppercase tracking-[0.18em] text-accent">{{ $s['eyebrow'] }} <span class="text-white/40">· Slide {{ $i+1 }}</span></div>
                    <h2 class="mt-2 font-display text-[22px] font-extrabold leading-tight sm:text-[26px]">{{ $s['title'] ?: '—' }}</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-white/80">{{ $s['subtitle'] ?: '—' }}</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="inline-flex items-center rounded-sm bg-accent px-4 py-1.5 font-display text-xs font-semibold text-primary-900">{{ $s['cta'] ?: 'CTA' }}</span>
                        <span class="inline-flex items-center rounded-sm border border-white/20 bg-transparent px-4 py-1.5 font-display text-xs font-semibold text-white">{{ $s['cta2'] ?: 'Secondaire' }}</span>
                    </div>
                </div>
            </div>
        @endforeach
        @if($about_title || $about_content)
            <div class="p-6 bg-white border-t border-zinc-200">
                <h3 class="font-display text-lg font-bold text-primary-900">{{ $about_title }}</h3>
                @if($about_content)
                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-zinc-600">{{ $about_content }}</p>
                @endif
            </div>
        @endif
        @if($seo_title || $seo_description)
            <div class="px-6 py-4 bg-zinc-50 border-t border-zinc-200">
                <div class="text-xs font-semibold text-zinc-500">{{ __('Aperçu SEO') }}</div>
                @if($seo_title)<div class="text-sm font-medium text-primary-700">{{ $seo_title }}</div>@endif
                @if($seo_description)<div class="text-xs text-zinc-500">{{ $seo_description }}</div>@endif
            </div>
        @endif
    </flux:card>
</section>
