<x-layouts.public :title="($seoTitle ?? $heroTitle)" :metaDescription="($seoDescription ?? $heroSubtitle)">
    {{-- Hero CDC 21 — IMAGE + OVERLAY bleu nuit + SUR-TITRE + H1 + DESCRIPTION + CTA --}}
    <section class="relative overflow-hidden bg-primary-900 text-white">
        <div class="absolute inset-0">
            @if($heroImage)
                <img src="{{ $heroImage }}" alt="" class="h-full w-full object-cover opacity-30">
            @else
                <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
            @endif
            <div class="absolute inset-0 bg-primary-900/70"></div>
            {{-- motif technique subtil CDC 20 --}}
            <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px); background-size: 40px 40px;"></div>
        </div>
        <div class="relative mx-auto max-w-[1280px] px-4 py-16 sm:px-6 lg:px-8 sm:py-20 lg:py-24">
            <div class="grid gap-10 lg:grid-cols-12 lg:items-center">
                <div class="lg:col-span-7">
                    <p class="font-display text-xs font-semibold uppercase tracking-[0.2em] text-accent">{{ __('BTP · Génie civil · VRD · Énergie') }}</p>
                    <h1 class="mt-4 font-display text-[36px] font-extrabold leading-[0.95] tracking-tight sm:text-[48px] lg:text-[56px]">{{ $heroTitle }}</h1>
                    <p class="mt-6 max-w-2xl text-[18px] leading-relaxed text-white/80">{{ $heroSubtitle }}</p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ ($heroCtaUrl ?? null) ?: route('public.quote.create') }}" class="inline-flex items-center rounded-sm bg-accent px-6 py-3 font-display text-sm font-semibold text-primary-900 hover:bg-accent-300 transition">{{ ($heroCtaLabel ?? null) ?: __('Demander un devis') }}</a>
                        <a href="{{ route('public.projects.index') }}" class="inline-flex items-center rounded-sm border border-white/20 bg-transparent px-6 py-3 font-display text-sm font-semibold text-white hover:bg-white/10 transition">{{ __('Voir nos réalisations') }}</a>
                    </div>
                    <div class="mt-10 grid grid-cols-3 gap-6 border-t border-white/10 pt-8">
                        <div><div class="font-display text-2xl font-bold text-accent">{{ $stats['projects'] }}+</div><div class="text-xs uppercase tracking-wide text-white/60">{{ $statsProjectsLabel ?? __('Projets livrés') }}</div></div>
                        <div><div class="font-display text-2xl font-bold text-accent">{{ $stats['expertises'] }}</div><div class="text-xs uppercase tracking-wide text-white/60">{{ $statsExpertisesLabel ?? __('Expertises') }}</div></div>
                        <div><div class="font-display text-2xl font-bold text-accent">{{ $stats['partners'] }}+</div><div class="text-xs uppercase tracking-wide text-white/60">{{ $statsPartnersLabel ?? __('Partenaires') }}</div></div>
                    </div>
                </div>
                <div class="relative lg:col-span-5">
                    <div class="aspect-[4/3] overflow-hidden rounded-md bg-white/5 p-2">
                        @if($heroImage)
                            <img src="{{ $heroImage }}" alt="{{ $heroTitle }}" class="h-full w-full rounded-sm object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center rounded-sm bg-white/10 text-sm text-white/50">{{ __('Image chantier — 4:3 object-cover') }}</div>
                        @endif
                    </div>
                    <div class="absolute -bottom-4 -left-4 hidden rounded-sm bg-accent px-4 py-3 text-primary-900 shadow lg:block">
                        <div class="text-xs font-semibold uppercase tracking-wide">{{ __('Depuis +20 ans') }}</div>
                        <div class="font-display text-sm font-bold">{{ __('Territoire & infrastructures') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(!empty($aboutContent))
        <section class="mx-auto max-w-[1280px] px-4 py-12 sm:px-6 lg:px-8 sm:py-16 border-b border-border">
            <h2 class="font-display text-[28px] font-bold tracking-tight text-primary-900 sm:text-[32px]">{{ $aboutTitle ?: __('À propos de SIBEA') }}</h2>
            <div class="mt-4 max-w-3xl whitespace-pre-line text-[16px] leading-relaxed text-neutral-700">{{ $aboutContent }}</div>
        </section>
    @endif

    {{-- Expertises CDC 22 — Eyebrow + Titre + Intro + Grille --}}
    <section class="mx-auto max-w-[1280px] px-4 py-16 sm:px-6 lg:px-8 sm:py-20">
        <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ __('Expertises') }}</p>
        <div class="mt-2 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="font-display text-[28px] font-bold tracking-tight text-primary-900 sm:text-[36px]">{{ __('Nos expertises') }}</h2>
                <p class="mt-3 max-w-2xl text-[16px] leading-relaxed text-neutral-700">{{ __('Bâtiment, Génie civil, VRD, Énergie : une offre mono-entreprise complète, sans filiales.') }}</p>
            </div>
            <a href="{{ route('public.expertises.index') }}" class="hidden text-sm font-semibold text-primary-900 hover:text-accent sm:block">{{ __('Toutes les expertises →') }}</a>
        </div>
        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($expertises as $exp)
                <a href="{{ route('public.expertises.show', $exp->slug) }}" class="group flex flex-col rounded-md border border-border bg-surface p-6 shadow-[0_1px_2px_rgba(11,31,51,0.06)] hover:shadow-sm transition">
                    <div class="h-10 w-10 rounded-sm bg-primary-100 flex items-center justify-center text-primary-700 text-xs font-bold">{{ Str::upper(Str::substr($exp->title,0,2)) }}</div>
                    <h3 class="mt-4 font-display text-[18px] font-semibold text-primary-900">{{ $exp->title }}</h3>
                    <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-neutral-700">{{ $exp->excerpt }}</p>
                    <div class="mt-4 text-xs font-medium uppercase tracking-wide text-neutral-500">{{ $exp->services_count }} {{ __('services') }} · <span class="text-accent group-hover:text-primary-700">{{ __('En savoir plus →') }}</span></div>
                </a>
            @empty
                <p class="text-sm text-neutral-500">{{ __('Aucune expertise publiée.') }}</p>
            @endforelse
        </div>
    </section>

    {{-- Réalisations CDC 15 — cartes sobres image 4:3 --}}
    @if($featuredProjects->isNotEmpty())
        <section class="bg-background">
            <div class="mx-auto max-w-[1280px] px-4 py-16 sm:px-6 lg:px-8 sm:py-20">
                <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ __('Réalisations') }}</p>
                <div class="mt-2 flex items-end justify-between">
                    <h2 class="font-display text-[28px] font-bold tracking-tight text-primary-900 sm:text-[36px]">{{ __('Réalisations phares') }}</h2>
                    <a href="{{ route('public.projects.index') }}" class="hidden text-sm font-semibold text-primary-900 hover:text-accent sm:block">{{ __('Toutes les réalisations →') }}</a>
                </div>
                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($featuredProjects as $project)
                        <a href="{{ route('public.projects.show', $project->slug) }}" class="group overflow-hidden rounded-md border border-border bg-surface shadow-[0_1px_2px_rgba(11,31,51,0.06)] hover:shadow-md transition">
                            <div class="aspect-[4/3] bg-neutral-100 overflow-hidden">
                                @if($project->cover_image)
                                    <img src="{{ $project->cover_image }}" alt="{{ $project->title }}" class="h-full w-full object-cover group-hover:scale-[1.02] transition duration-300">
                                @else
                                    <div class="h-full w-full bg-gradient-to-br from-neutral-100 to-neutral-300 group-hover:scale-[1.02] transition duration-300"></div>
                                @endif
                            </div>
                            <div class="p-5">
                                <div class="text-[11px] font-semibold uppercase tracking-wide text-neutral-500">{{ $project->category }} · {{ $project->year }}</div>
                                <h3 class="mt-2 font-display text-[16px] font-semibold text-primary-900 line-clamp-1">{{ $project->title }}</h3>
                                <p class="mt-1 text-sm leading-relaxed text-neutral-700 line-clamp-2">{{ $project->description }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Actualités — fond blanc, grille 3 --}}
    @if($latestPosts->isNotEmpty())
        <section class="mx-auto max-w-[1280px] px-4 py-16 sm:px-6 lg:px-8 sm:py-20">
            <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ __('Actualités') }}</p>
            <h2 class="mt-2 font-display text-[28px] font-bold tracking-tight text-primary-900 sm:text-[36px]">{{ __('Actualités & chantiers') }}</h2>
            <div class="mt-10 grid gap-6 sm:grid-cols-3">
                @foreach($latestPosts as $post)
                    <a href="{{ route('public.posts.show', $post->slug) }}" class="rounded-md border border-border bg-surface p-6 hover:shadow-sm transition">
                        <div class="text-[11px] font-semibold uppercase tracking-wide text-neutral-500">{{ $post->category }} · {{ $post->published_at?->format('d/m/Y') }}</div>
                        <h3 class="mt-3 font-display text-[16px] font-semibold text-primary-900 line-clamp-2">{{ $post->title }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-neutral-700 line-clamp-2">{{ $post->excerpt }}</p>
                    </a>
                @endforeach
            </div>
            <div class="mt-8 text-center">
                <a href="{{ route('public.posts.index') }}" class="inline-flex rounded-sm border border-primary-900 px-5 py-2 text-sm font-semibold text-primary-900 hover:bg-primary-900 hover:text-white transition">{{ __('Toutes les actualités') }}</a>
            </div>
        </section>
    @endif

    {{-- Témoignages — bleu nuit CDC 24 --}}
    @if($testimonials->isNotEmpty())
        <section class="bg-primary-900 text-white">
            <div class="mx-auto max-w-[1280px] px-4 py-16 sm:px-6 lg:px-8 sm:py-20">
                <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-accent">{{ __('Confiance') }}</p>
                <h2 class="mt-2 font-display text-[28px] font-bold tracking-tight sm:text-[32px]">{{ __('Ils nous font confiance') }}</h2>
                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    @foreach($testimonials as $t)
                        <div class="rounded-md bg-white/[0.06] p-6 border border-white/10">
                            <p class="text-sm leading-relaxed text-white/90">“{{ Str::limit($t->content, 160) }}”</p>
                            <div class="mt-4 font-display text-sm font-semibold text-white">{{ $t->author_name }}</div>
                            <div class="text-xs text-white/60">{{ $t->role }} @if($t->company) · {{ $t->company }} @endif</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Partenaires — neutre, sobre --}}
    @if($partners->isNotEmpty())
        <section class="mx-auto max-w-[1280px] px-4 py-12 sm:px-6 lg:px-8">
            <p class="text-center font-display text-[11px] font-semibold uppercase tracking-[0.18em] text-neutral-500">{{ __('Nos partenaires') }}</p>
            <div class="mt-6 flex flex-wrap justify-center gap-x-8 gap-y-3">
                @foreach($partners as $partner)
                    <span class="text-sm font-medium text-neutral-700">{{ $partner->name }}</span>
                @endforeach
            </div>
        </section>
    @endif

    {{-- CTA final — accent jaune charte CDC 3.1 --}}
    <section class="bg-accent">
        <div class="mx-auto max-w-[1280px] px-4 py-12 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display text-[24px] font-bold tracking-tight text-primary-900 sm:text-[28px]">{{ __('Prêt à lancer votre projet ?') }}</h2>
            <p class="mt-2 text-sm text-primary-900/70">{{ __('Obtenez une étude et un devis détaillé sous 48h.') }}</p>
            <a href="{{ route('public.quote.create') }}" class="mt-6 inline-flex rounded-sm bg-primary-900 px-6 py-3 font-display text-sm font-semibold text-white hover:bg-primary-800 transition">{{ __('Demander un devis') }}</a>
        </div>
    </section>
</x-layouts.public>
