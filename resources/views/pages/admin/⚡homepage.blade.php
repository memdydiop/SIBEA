<?php

use App\Actions\Audit\LogAuditAction;
use App\Models\SiteSetting;
use Flux\Flux;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Title('Page d’accueil')] class extends Component
{
    use WithFileUploads;

    public string $hero_title = '';

    public string $hero_subtitle = '';

    public ?string $hero_image = null;

    public $hero_image_upload = null;

    public ?string $site_logo = null;

    public $site_logo_upload = null;

    public string $site_name = 'SIBEA';

    public ?string $hero_cta_label = null;

    public ?string $hero_cta_url = null;

    public string $stats_projects_label = '';

    public string $stats_expertises_label = '';

    public string $stats_partners_label = '';

    public string $about_title = '';

    public ?string $about_content = null;

    public ?string $seo_title = null;

    public ?string $seo_description = null;

    public function mount(): void
    {
        Gate::authorize('viewAny', SiteSetting::class);

        $this->hero_title = SiteSetting::get('hero_title', 'Bâtir l’avenir avec excellence') ?? 'Bâtir l’avenir avec excellence';
        $this->hero_subtitle = SiteSetting::get('hero_subtitle', 'Entreprise BTP de référence — Bâtiment, Génie civil, VRD, Énergie depuis plus de 20 ans.') ?? 'Entreprise BTP de référence — Bâtiment, Génie civil, VRD, Énergie depuis plus de 20 ans.';
        $this->hero_image = SiteSetting::get('hero_image');
        $this->site_logo = SiteSetting::get('site_logo');
        $this->site_name = SiteSetting::get('site_name', config('app.name', 'SIBEA')) ?? config('app.name', 'SIBEA');
        $this->hero_cta_label = SiteSetting::get('hero_cta_label', 'Demander un devis');
        $this->hero_cta_url = SiteSetting::get('hero_cta_url', '/devis');
        $this->stats_projects_label = SiteSetting::get('stats_projects_label', 'Projets livrés') ?? 'Projets livrés';
        $this->stats_expertises_label = SiteSetting::get('stats_expertises_label', 'Expertises') ?? 'Expertises';
        $this->stats_partners_label = SiteSetting::get('stats_partners_label', 'Partenaires') ?? 'Partenaires';
        $this->about_title = SiteSetting::get('about_title', 'À propos de SIBEA') ?? 'À propos de SIBEA';
        $this->about_content = SiteSetting::get('about_content');
        $this->seo_title = SiteSetting::get('seo_title');
        $this->seo_description = SiteSetting::get('seo_description');
    }

    public function save(LogAuditAction $audit): void
    {
        Gate::authorize('viewAny', SiteSetting::class);

        if ($this->hero_image_upload) {
            Validator::make(
                ['hero_image_upload' => $this->hero_image_upload],
                ['hero_image_upload' => ['image', 'mimes:jpeg,png,webp', 'max:2048', 'dimensions:max_width=4000,max_height=4000']],
            )->validate();

            $path = $this->hero_image_upload->store('cms/home', 'public');
            $this->hero_image = '/storage/'.$path;
        }

        if ($this->site_logo_upload) {
            Validator::make(
                ['site_logo_upload' => $this->site_logo_upload],
                ['site_logo_upload' => ['image', 'mimes:jpeg,png,webp,svg', 'max:1024', 'dimensions:max_width=2000,max_height=2000']],
            )->validate();

            $path = $this->site_logo_upload->store('cms/branding', 'public');
            $this->site_logo = '/storage/'.$path;
        }

        $data = Validator::make([
            'hero_title' => $this->hero_title,
            'hero_subtitle' => $this->hero_subtitle,
            'hero_image' => $this->hero_image,
            'site_logo' => $this->site_logo,
            'site_name' => $this->site_name,
            'hero_cta_label' => $this->hero_cta_label,
            'hero_cta_url' => $this->hero_cta_url,
            'stats_projects_label' => $this->stats_projects_label,
            'stats_expertises_label' => $this->stats_expertises_label,
            'stats_partners_label' => $this->stats_partners_label,
            'about_title' => $this->about_title,
            'about_content' => $this->about_content,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
        ], [
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_subtitle' => ['required', 'string', 'max:600'],
            'hero_image' => ['nullable', 'string', 'max:2048'],
            'site_logo' => ['nullable', 'string', 'max:2048'],
            'site_name' => ['required', 'string', 'max:100'],
            'hero_cta_label' => ['nullable', 'string', 'max:80'],
            'hero_cta_url' => ['nullable', 'string', 'max:2048'],
            'stats_projects_label' => ['required', 'string', 'max:80'],
            'stats_expertises_label' => ['required', 'string', 'max:80'],
            'stats_partners_label' => ['required', 'string', 'max:80'],
            'about_title' => ['required', 'string', 'max:255'],
            'about_content' => ['nullable', 'string', 'max:5000'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
        ])->validate();

        $old = [
            'hero_title' => SiteSetting::get('hero_title'),
            'hero_subtitle' => SiteSetting::get('hero_subtitle'),
            'hero_image' => SiteSetting::get('hero_image'),
            'site_logo' => SiteSetting::get('site_logo'),
            'site_name' => SiteSetting::get('site_name'),
            'hero_cta_label' => SiteSetting::get('hero_cta_label'),
            'hero_cta_url' => SiteSetting::get('hero_cta_url'),
            'stats_projects_label' => SiteSetting::get('stats_projects_label'),
            'stats_expertises_label' => SiteSetting::get('stats_expertises_label'),
            'stats_partners_label' => SiteSetting::get('stats_partners_label'),
            'about_title' => SiteSetting::get('about_title'),
            'about_content' => SiteSetting::get('about_content'),
            'seo_title' => SiteSetting::get('seo_title'),
            'seo_description' => SiteSetting::get('seo_description'),
        ];

        SiteSetting::set('hero_title', $data['hero_title'], 'general');
        SiteSetting::set('hero_subtitle', $data['hero_subtitle'], 'general');
        SiteSetting::set('hero_image', $data['hero_image'], 'general');
        SiteSetting::set('site_logo', $data['site_logo'], 'branding');
        SiteSetting::set('site_name', $data['site_name'], 'branding');
        SiteSetting::set('hero_cta_label', $data['hero_cta_label'], 'general');
        SiteSetting::set('hero_cta_url', $data['hero_cta_url'], 'general');
        SiteSetting::set('stats_projects_label', $data['stats_projects_label'], 'general');
        SiteSetting::set('stats_expertises_label', $data['stats_expertises_label'], 'general');
        SiteSetting::set('stats_partners_label', $data['stats_partners_label'], 'general');
        SiteSetting::set('about_title', $data['about_title'], 'general');
        SiteSetting::set('about_content', $data['about_content'], 'general');
        SiteSetting::set('seo_title', $data['seo_title'], 'seo');
        SiteSetting::set('seo_description', $data['seo_description'], 'seo');

        Cache::forget('home:vitrine:v1');
        Cache::forget('home:vitrine:settings:v2');

        $audit('HOMEPAGE_UPDATED', SiteSetting::class, $old, $data);

        Flux::toast(variant: 'success', text: __('Page d’accueil mise à jour.'));

        $this->hero_image_upload = null;
        $this->site_logo_upload = null;
    }
}; ?>

<section class="w-full">
    <flux:heading size="xl" level="1">{{ __('Page d’accueil') }}</flux:heading>
    <flux:subheading class="mb-6">{{ __('Édition vitrine 100% — hero, stats, à propos, SEO. Image stockée dans cms/home.') }}</flux:subheading>

    <form wire:submit="save" class="space-y-6">
        {{-- Hero --}}
        <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <div class="bg-zinc-50 dark:bg-zinc-800/50 px-4 py-3 flex items-center justify-between">
                <div class="font-semibold text-sm">{{ __('Hero') }}</div>
                <flux:badge size="sm">general</flux:badge>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <flux:input wire:model="hero_title" :label="__('Titre hero (H1)')" required placeholder="Bâtir l’avenir avec excellence" />
                </div>
                <div class="sm:col-span-2">
                    <flux:textarea wire:model="hero_subtitle" :label="__('Sous-titre hero')" rows="3" required placeholder="Entreprise BTP de référence..." />
                </div>
                <flux:input wire:model="hero_image" :label="__('Image hero URL')" placeholder="https:// ou /storage/cms/home/..." />
                <flux:input type="file" wire:model="hero_image_upload" :label="__('Ou fichier hero (jpeg,png,webp max 2Mo, max 4000x4000)')" accept="image/jpeg,image/png,image/webp" />
                <div class="sm:col-span-2 -mt-2"><a href="{{ route('admin.media') }}" target="_blank" class="text-xs text-zinc-500 underline hover:text-zinc-700">{{ __('Ouvrir la médiathèque') }} →</a> <span class="text-xs text-zinc-400">{{ __('ou uploader ci-dessus (cms/home)') }}</span></div>
                @if($hero_image)
                    <div class="sm:col-span-2">
                        <div class="text-xs text-zinc-500 mb-2">{{ __('Aperçu image actuelle :') }}</div>
                        <img src="{{ $hero_image }}" alt="hero preview" class="h-32 w-auto rounded border object-cover">
                    </div>
                @endif
                @if($hero_image_upload)
                    <div class="sm:col-span-2 text-xs text-zinc-500">{{ __('Nouveau fichier sélectionné — sera stocké dans cms/home') }}</div>
                @endif
                <flux:input wire:model="hero_cta_label" :label="__('Label bouton CTA principal')" placeholder="Demander un devis" />
                <flux:input wire:model="hero_cta_url" :label="__('URL bouton CTA principal')" placeholder="/devis ou https://..." />
            </div>
        </div>

        {{-- Identité / Logo — branding CSM --}}
        <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <div class="bg-zinc-50 dark:bg-zinc-800/50 px-4 py-3 flex items-center justify-between">
                <div class="font-semibold text-sm">{{ __('Identité — Logo & nom') }}</div>
                <flux:badge size="sm">branding</flux:badge>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
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
        </div>

        {{-- Stats labels --}}
        <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <div class="bg-zinc-50 dark:bg-zinc-800/50 px-4 py-3 flex items-center justify-between">
                <div class="font-semibold text-sm">{{ __('Statistiques (labels)') }}</div>
                <flux:badge size="sm">general</flux:badge>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <flux:input wire:model="stats_projects_label" :label="__('Label projets')" required placeholder="Projets livrés" />
                <flux:input wire:model="stats_expertises_label" :label="__('Label expertises')" required placeholder="Expertises" />
                <flux:input wire:model="stats_partners_label" :label="__('Label partenaires')" required placeholder="Partenaires" />
            </div>
            <div class="px-6 pb-4 text-xs text-zinc-500">{{ __('Les compteurs restent calculés depuis les modèles PublicProject, Expertise, Partner.') }}</div>
        </div>

        {{-- À propos --}}
        <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <div class="bg-zinc-50 dark:bg-zinc-800/50 px-4 py-3 flex items-center justify-between">
                <div class="font-semibold text-sm">{{ __('À propos (vitrine homepage)') }}</div>
                <flux:badge size="sm">general</flux:badge>
            </div>
            <div class="p-6 space-y-4">
                <flux:input wire:model="about_title" :label="__('Titre à propos')" required placeholder="À propos de SIBEA" />
                <flux:textarea wire:model="about_content" :label="__('Contenu à propos')" rows="5" placeholder="Texte libre affiché en preview et potentiellement sur la homepage..." />
            </div>
        </div>

        {{-- SEO --}}
        <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <div class="bg-zinc-50 dark:bg-zinc-800/50 px-4 py-3 flex items-center justify-between">
                <div class="font-semibold text-sm">{{ __('SEO') }}</div>
                <flux:badge size="sm">seo</flux:badge>
            </div>
            <div class="p-6 grid grid-cols-1 gap-4">
                <flux:input wire:model="seo_title" :label="__('Meta title (seo_title)')" placeholder="SIBEA — BTP, Génie civil, VRD & Énergie" />
                <flux:textarea wire:model="seo_description" :label="__('Meta description (seo_description)')" rows="3" placeholder="Entreprise BTP mono-entreprise..." />
            </div>
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('home') }}" target="_blank" class="text-sm text-zinc-500 hover:text-zinc-700 underline">{{ __('Voir le site') }} →</a>
            <div class="flex gap-2">
                <flux:button variant="primary" type="submit" icon="check">{{ __('Enregistrer') }}</flux:button>
            </div>
        </div>
    </form>

    {{-- Preview --}}
    <div class="mt-10 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="bg-zinc-50 dark:bg-zinc-800/50 px-4 py-3 font-semibold text-sm">{{ __('Prévisualisation hero (rendu simplifié)') }}</div>
        <div class="relative overflow-hidden bg-primary-900 text-white">
            <div class="absolute inset-0">
                @if($hero_image)
                    <img src="{{ $hero_image }}" alt="" class="h-full w-full object-cover opacity-30">
                @else
                    <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
                @endif
                <div class="absolute inset-0 bg-primary-900/70"></div>
            </div>
            <div class="relative p-6 sm:p-8">
                <p class="font-display text-xs font-semibold uppercase tracking-[0.2em] text-accent">{{ __('BTP · Génie civil · VRD · Énergie') }}</p>
                <h2 class="mt-3 font-display text-[28px] font-extrabold leading-tight sm:text-[36px]">{{ $hero_title ?: __('Bâtir l’avenir avec excellence') }}</h2>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-white/80">{{ $hero_subtitle ?: __('Entreprise BTP de référence...') }}</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <span class="inline-flex items-center rounded-sm bg-accent px-5 py-2 font-display text-sm font-semibold text-primary-900">{{ $hero_cta_label ?: __('Demander un devis') }}</span>
                    <span class="inline-flex items-center rounded-sm border border-white/20 bg-transparent px-5 py-2 font-display text-sm font-semibold text-white">{{ __('Voir nos réalisations') }}</span>
                </div>
                <div class="mt-6 grid grid-cols-3 gap-4 border-t border-white/10 pt-4 text-center sm:text-left">
                    <div><div class="font-display text-xl font-bold text-accent">+999</div><div class="text-xs uppercase tracking-wide text-white/60">{{ $stats_projects_label ?: __('Projets livrés') }}</div></div>
                    <div><div class="font-display text-xl font-bold text-accent">4</div><div class="text-xs uppercase tracking-wide text-white/60">{{ $stats_expertises_label ?: __('Expertises') }}</div></div>
                    <div><div class="font-display text-xl font-bold text-accent">4+</div><div class="text-xs uppercase tracking-wide text-white/60">{{ $stats_partners_label ?: __('Partenaires') }}</div></div>
                </div>
            </div>
        </div>
        @if($about_title || $about_content)
            <div class="p-6 bg-white dark:bg-zinc-900">
                <h3 class="font-display text-lg font-bold text-primary-900 dark:text-white">{{ $about_title }}</h3>
                @if($about_content)
                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-zinc-600 dark:text-zinc-300">{{ $about_content }}</p>
                @endif
            </div>
        @endif
        @if($seo_title || $seo_description)
            <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800/50 border-t border-zinc-200 dark:border-zinc-700">
                <div class="text-xs font-semibold text-zinc-500">{{ __('Aperçu SEO') }}</div>
                @if($seo_title)<div class="text-sm font-medium text-primary-700 dark:text-white">{{ $seo_title }}</div>@endif
                @if($seo_description)<div class="text-xs text-zinc-500">{{ $seo_description }}</div>@endif
            </div>
        @endif
    </div>
</section>
