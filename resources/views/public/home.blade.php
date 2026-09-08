<x-layouts.public :title="($seoTitle ?? $heroTitle)" :metaDescription="($seoDescription ?? $heroSubtitle)">
    {{-- Hero slideshow CDC — 3 slides Alpine, branché sur $heroImage / $heroSlides --}}
    <section
        x-data="heroSlideshow(@js($heroSlides ?? []))"
        x-init="init()"
        @mouseenter="pause()"
        @mouseleave="if (playing) play()"
        @keydown.arrow-left.window="prev()"
        @keydown.arrow-right.window="next()"
        class="relative overflow-hidden bg-primary-900 text-white"
        aria-roledescription="carousel"
        aria-label="{{ __('Projets phares') }}"
    >
        {{-- Backgrounds — crossfade --}}
        <div class="absolute inset-0">
            <template x-for="(slide, index) in slides" :key="index">
                <div
                    x-show="current === index"
                    x-transition:enter="transition ease-out duration-700"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-500"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute inset-0"
                    x-cloak
                >
                    <template x-if="slide.image">
                        <img :src="slide.image" :alt="slide.title" class="h-full w-full object-cover opacity-30">
                    </template>
                    <template x-if="!slide.image">
                        <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
                    </template>
                    <div class="absolute inset-0 bg-primary-900/70"></div>
                </div>
            </template>
            {{-- fallback no-JS : premier slide statique --}}
            <noscript>
                @if($heroImage)
                    <img src="{{ $heroImage }}" alt="" class="h-full w-full object-cover opacity-30">
                @else
                    <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
                @endif
                <div class="absolute inset-0 bg-primary-900/70"></div>
            </noscript>
            {{-- motif technique subtil CDC 20 --}}
            <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px); background-size: 40px 40px;"></div>
        </div>

        <div class="relative mx-auto max-w-[1280px] px-4 py-10 sm:px-6 lg:px-8 sm:py-14 lg:py-16">
            <div class="grid gap-8 lg:grid-cols-12 lg:items-center">
                <div class="lg:col-span-7 flex min-h-[420px] flex-col justify-center sm:min-h-[440px]">
                    {{-- Slides text --}}
                    <template x-for="(slide, index) in slides" :key="index">
                        <div
                            x-show="current === index"
                            x-transition:enter="transition ease-out duration-500 delay-100"
                            x-transition:enter-start="opacity-0 translate-y-3"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-cloak
                        >
                            <p class="font-display text-xs font-semibold uppercase tracking-[0.2em] text-accent" x-text="slide.eyebrow"></p>
                            <h1 class="mt-4 font-display text-[34px] font-extrabold leading-[0.95] tracking-tight sm:text-[46px] lg:text-[54px]" x-text="slide.title"></h1>
                            <p class="mt-5 max-w-2xl text-[17px] leading-relaxed text-white/80" x-text="slide.subtitle"></p>
                            <div class="mt-8 flex flex-wrap gap-3">
                                <a :href="slide.ctaUrl" class="inline-flex items-center rounded-sm bg-accent px-6 py-3 font-display text-sm font-semibold text-primary-900 hover:bg-accent-300 transition" x-text="slide.ctaLabel"></a>
                                <a :href="slide.secondaryUrl" class="inline-flex items-center rounded-sm border border-white/20 bg-transparent px-6 py-3 font-display text-sm font-semibold text-white hover:bg-white/10 transition" x-text="slide.secondaryLabel"></a>
                            </div>
                        </div>
                    </template>

                    {{-- No-JS fallback --}}
                    <noscript>
                        <p class="font-display text-xs font-semibold uppercase tracking-[0.2em] text-accent">{{ __('BTP · Génie civil · VRD · Énergie') }}</p>
                        <h1 class="mt-4 font-display text-[36px] font-extrabold leading-[0.95] tracking-tight sm:text-[48px] lg:text-[56px]">{{ $heroTitle }}</h1>
                        <p class="mt-6 max-w-2xl text-[18px] leading-relaxed text-white/80">{{ $heroSubtitle }}</p>
                        <div class="mt-8 flex flex-wrap gap-3">
                            <a href="{{ ($heroCtaUrl ?? null) ?: route('public.quote.create') }}" class="inline-flex items-center rounded-sm bg-accent px-6 py-3 font-display text-sm font-semibold text-primary-900 hover:bg-accent-300 transition">{{ ($heroCtaLabel ?? null) ?: __('Demander un devis') }}</a>
                            <a href="{{ route('public.projects.index') }}" class="inline-flex items-center rounded-sm border border-white/20 bg-transparent px-6 py-3 font-display text-sm font-semibold text-white hover:bg-white/10 transition">{{ __('Voir nos réalisations') }}</a>
                        </div>
                    </noscript>

                    {{-- Controls --}}
                    <div class="mt-10 flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2" role="tablist" aria-label="{{ __('Slides') }}">
                            <template x-for="(slide, index) in slides" :key="index">
                                <button
                                    @click="go(index)"
                                    :aria-selected="current === index ? 'true' : 'false'"
                                    :class="current === index ? 'bg-accent w-8' : 'bg-white/30 hover:bg-white/60 w-2.5'"
                                    class="h-2.5 rounded-full transition-all duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 focus-visible:ring-offset-primary-900"
                                    role="tab"
                                    :aria-label="'Slide ' + (index + 1) + ' : ' + slide.title"
                                ></button>
                            </template>
                        </div>
                        <div class="hidden h-4 w-px bg-white/20 sm:block" aria-hidden="true"></div>
                        <div class="flex items-center gap-1.5">
                            <button @click="prev()" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-white/20 text-white hover:bg-white/10 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent" aria-label="{{ __('Précédent') }}">‹</button>
                            <button @click="next()" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-white/20 text-white hover:bg-white/10 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent" aria-label="{{ __('Suivant') }}">›</button>
                            <button @click="toggle()" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-white/20 text-white hover:bg-white/10 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent" :aria-label="playing ? '{{ __('Pause') }}' : '{{ __('Lecture') }}'">
                                <span x-show="playing" class="text-[11px] leading-none" aria-hidden="true">❚❚</span>
                                <span x-show="!playing" class="text-[11px] leading-none translate-x-px" aria-hidden="true">▶</span>
                            </button>
                        </div>
                        <span class="text-xs font-mono text-white/50" aria-live="polite"><span x-text="current + 1"></span> / <span x-text="slides.length"></span></span>
                    </div>
                </div>

                {{-- Preview visuel 4:3 — desktop uniquement, synchronisé avec slide courant --}}
                <div class="relative hidden lg:col-span-5 lg:block">
                    <div class="aspect-[4/3] overflow-hidden rounded-md bg-white/5 p-2">
                        <template x-for="(slide, index) in slides" :key="index">
                            <img
                                x-show="current === index"
                                x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 scale-[0.98]"
                                x-transition:enter-end="opacity-100 scale-100"
                                :src="slide.image"
                                :alt="slide.title"
                                class="h-full w-full rounded-sm object-cover"
                                x-cloak
                            >
                        </template>
                        <noscript>
                            @if($heroImage)
                                <img src="{{ $heroImage }}" alt="{{ $heroTitle }}" class="h-full w-full rounded-sm object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center rounded-sm bg-white/10 text-sm text-white/50">{{ __('Image chantier — 4:3 object-cover') }}</div>
                            @endif
                        </noscript>
                    </div>
                    <div class="absolute -bottom-4 -left-4 hidden rounded-sm bg-accent px-4 py-3 text-primary-900 shadow lg:block">
                        <div class="text-xs font-semibold uppercase tracking-wide">{{ __('Depuis +20 ans') }}</div>
                        <div class="font-display text-sm font-bold">{{ __('Territoire & infrastructures') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Barre de progression auto-play --}}
        <div class="pointer-events-none absolute bottom-0 left-0 h-1 w-full bg-white/10">
            <div
                class="h-full bg-accent transition-all ease-linear"
                :style="`width: ${((current + 1) / slides.length) * 100}%; transition-duration: ${interval}ms`"
                x-show="playing"
            ></div>
        </div>
    </section>

    <script>
        window.heroSlideshow = function (slides) {
            const fallback = slides && slides.length ? slides : [{ eyebrow: '', title: '', subtitle: '', image: null, ctaLabel: '', ctaUrl: '#', secondaryLabel: '', secondaryUrl: '#' }];
            return {
                slides: fallback,
                current: 0,
                playing: true,
                timer: null,
                interval: 5500,
                init() {
                    // précharge slide 2
                    if (this.slides[1]?.image) { const i = new Image(); i.src = this.slides[1].image; }
                    this.play();
                },
                play() {
                    this.pause();
                    if (!this.playing) return;
                    this.timer = setInterval(() => this.next(), this.interval);
                },
                pause() {
                    if (this.timer) clearInterval(this.timer);
                    this.timer = null;
                },
                toggle() {
                    this.playing = !this.playing;
                    if (this.playing) this.play(); else this.pause();
                },
                next() {
                    this.current = (this.current + 1) % this.slides.length;
                    this.preloadNext();
                },
                prev() {
                    this.current = (this.current - 1 + this.slides.length) % this.slides.length;
                    this.preloadNext();
                },
                go(i) {
                    this.current = i;
                    this.play();
                    this.preloadNext();
                },
                preloadNext() {
                    const n = (this.current + 1) % this.slides.length;
                    const src = this.slides[n]?.image;
                    if (src) { const img = new Image(); img.src = src; }
                },
            };
        };
    </script>
    <style>[x-cloak]{display:none !important}</style>

    {{-- Section chiffres isolée — détachée du hero (inspiration Construction theme stats) --}}
    <section class="border-y border-border bg-surface">
        <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-6 py-8 sm:grid-cols-4 sm:gap-0 sm:py-0">
                <div class="flex flex-col items-center gap-1 border-border px-2 py-4 text-center sm:items-start sm:border-r sm:px-8 sm:py-8 sm:text-left">
                    <div class="font-display text-[30px] font-extrabold leading-none tracking-tight text-primary-900 sm:text-[32px]">{{ $stats['projects'] }}<span class="text-accent">+</span></div>
                    <div class="text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ $statsProjectsLabel ?? __('Projets livrés') }}</div>
                    <div class="hidden text-xs leading-relaxed text-neutral-500 sm:block">{{ __('Bâtiment, VRD, génie civil') }}</div>
                </div>
                <div class="flex flex-col items-center gap-1 border-border px-2 py-4 text-center sm:items-start sm:border-r sm:px-8 sm:py-8 sm:text-left">
                    <div class="font-display text-[30px] font-extrabold leading-none tracking-tight text-primary-900 sm:text-[32px]">{{ $stats['expertises'] }}</div>
                    <div class="text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ $statsExpertisesLabel ?? __('Expertises') }}</div>
                    <div class="hidden text-xs leading-relaxed text-neutral-500 sm:block">{{ __('Mono-entreprise, sans filiales') }}</div>
                </div>
                <div class="flex flex-col items-center gap-1 border-border px-2 py-4 text-center sm:items-start sm:border-r sm:px-8 sm:py-8 sm:text-left">
                    <div class="font-display text-[30px] font-extrabold leading-none tracking-tight text-primary-900 sm:text-[32px]">{{ $stats['partners'] }}<span class="text-accent">+</span></div>
                    <div class="text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ $statsPartnersLabel ?? __('Partenaires') }}</div>
                    <div class="hidden text-xs leading-relaxed text-neutral-500 sm:block">{{ __('Réseau & fournisseurs') }}</div>
                </div>
                <div class="flex flex-col items-center gap-1 px-2 py-4 text-center sm:items-start sm:px-8 sm:py-8 sm:text-left">
                    <div class="font-display text-[30px] font-extrabold leading-none tracking-tight text-primary-900 sm:text-[32px]">20<span class="text-accent">+</span></div>
                    <div class="text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ __('Années') }}</div>
                    <div class="hidden text-xs leading-relaxed text-neutral-500 sm:block">{{ __('Territoire & infrastructures') }}</div>
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

    {{-- Réalisations CDC 15 — filtre portfolio Alpine (Tous / catégories) --}}
    @if($featuredProjects->isNotEmpty())
        <section class="bg-background" x-data="{ active: 'all' }">
            <div class="mx-auto max-w-[1280px] px-4 py-16 sm:px-6 lg:px-8 sm:py-20">
                <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ __('Réalisations') }}</p>
                <div class="mt-2 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <h2 class="font-display text-[28px] font-bold tracking-tight text-primary-900 sm:text-[36px]">{{ __('Réalisations phares') }}</h2>
                    <a href="{{ route('public.projects.index') }}" class="hidden text-sm font-semibold text-primary-900 hover:text-accent sm:block">{{ __('Toutes les réalisations →') }}</a>
                </div>

                @if(($projectCategories ?? collect())->isNotEmpty())
                    <div class="mt-6 flex flex-wrap gap-2">
                        <button
                            @click="active = 'all'"
                            :class="active === 'all' ? 'bg-primary-900 text-white border-primary-900' : 'border border-border bg-surface text-zinc-700 hover:bg-zinc-50'"
                            class="rounded-full px-3.5 py-1.5 text-sm font-medium transition"
                        >{{ __('Tous') }}</button>
                        @foreach($projectCategories as $cat)
                            <button
                                @click="active = @js($cat)"
                                :class="active === @js($cat) ? 'bg-primary-900 text-white border-primary-900' : 'border border-border bg-surface text-zinc-700 hover:bg-zinc-50'"
                                class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition"
                            >{{ $cat }}</button>
                        @endforeach
                    </div>
                @endif

                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($featuredProjects as $project)
                        <a
                            href="{{ route('public.projects.show', $project->slug) }}"
                            class="group overflow-hidden rounded-md border border-border bg-surface shadow-[0_1px_2px_rgba(11,31,51,0.06)] hover:shadow-md transition"
                            x-show="active === 'all' || active === @js($project->category)"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-[0.98]"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-[0.98]"
                            x-cloak
                        >
                            <div class="aspect-[4/3] bg-neutral-100 overflow-hidden relative">
                                @if($project->cover_image)
                                    <img src="{{ $project->cover_image }}" alt="{{ $project->title }}" class="h-full w-full object-cover group-hover:scale-[1.02] transition duration-300">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                                @else
                                    <div class="h-full w-full bg-gradient-to-br from-neutral-100 to-neutral-300 group-hover:scale-[1.02] transition duration-300"></div>
                                @endif
                                <div class="absolute left-3 top-3 flex gap-1.5">
                                    <span class="rounded-full bg-white/90 px-2 py-0.5 text-[11px] font-semibold text-primary-900 backdrop-blur">{{ $project->category }}</span>
                                    @if($project->year)<span class="rounded-full bg-accent px-2 py-0.5 text-[11px] font-semibold text-primary-900">{{ $project->year }}</span>@endif
                                </div>
                            </div>
                            <div class="p-5">
                                <div class="text-[11px] font-semibold uppercase tracking-wide text-neutral-500">{{ $project->category }} · {{ $project->year }}</div>
                                <h3 class="mt-2 font-display text-[16px] font-semibold text-primary-900 line-clamp-1 group-hover:text-accent transition">{{ $project->title }}</h3>
                                <p class="mt-1 text-sm leading-relaxed text-neutral-700 line-clamp-2">{{ $project->description }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Fallback no-JS : déjà rendu côté serveur, mais on garde le lien --}}
                <noscript>
                    <div class="mt-4 text-sm text-neutral-500">{{ __('Filtre désactivé sans JavaScript —') }} <a href="{{ route('public.projects.index') }}" class="font-semibold text-primary-700 hover:text-accent">{{ __('voir toutes les réalisations') }}</a></div>
                </noscript>

                <div class="mt-8 text-center sm:hidden">
                    <a href="{{ route('public.projects.index') }}" class="inline-flex rounded-sm border border-primary-900 px-5 py-2 text-sm font-semibold text-primary-900 hover:bg-primary-900 hover:text-white transition">{{ __('Toutes les réalisations →') }}</a>
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
