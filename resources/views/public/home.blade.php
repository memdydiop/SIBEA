<x-layouts.public :title="($seoTitle ?? $heroTitle)" :metaDescription="($seoDescription ?? $heroSubtitle)">
    {{-- 1. Hero — promesse de fiabilité (photo/video haute qualité) --}}
    <section
        x-data="heroSlideshow(@js($heroSlides ?? []))"
        x-init="init()"
        @mouseenter="pause()"
        @mouseleave="if (playing) play()"
        @keydown.arrow-left.window="prev()"
        @keydown.arrow-right.window="next()"
        class="relative overflow-hidden bg-primary-900 text-white"
        aria-roledescription="carousel"
        aria-label="{{ __('Infrastructures durables') }}"
    >
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
                        <img :src="slide.image" :alt="slide.title" class="h-full w-full object-cover opacity-35">
                    </template>
                    <template x-if="!slide.image">
                        <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
                    </template>
                    <div class="absolute inset-0 bg-primary-900/70"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-primary-900/60 via-transparent to-transparent"></div>
                </div>
            </template>
            <noscript>
                @if($heroImage)
                    <img src="{{ $heroImage }}" alt="" class="h-full w-full object-cover opacity-35">
                @else
                    <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
                @endif
                <div class="absolute inset-0 bg-primary-900/70"></div>
            </noscript>
            <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px); background-size: 40px 40px;"></div>
        </div>

        <div class="relative mx-auto max-w-[1280px] px-4 py-12 sm:px-6 lg:px-8 sm:py-16">
            <div class="grid gap-8 lg:grid-cols-12 lg:items-center">
                <div class="lg:col-span-7 flex min-h-[460px] flex-col justify-center sm:min-h-[480px]">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div
                            x-show="current === index"
                            x-transition:enter="transition ease-out duration-500 delay-100"
                            x-transition:enter-start="opacity-0 translate-y-3"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-cloak
                        >
                            <p class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-accent ring-1 ring-white/10 backdrop-blur" x-text="slide.eyebrow"></p>
                            <h1 class="mt-4 font-display text-[34px] font-extrabold leading-[0.9] tracking-tight sm:text-[46px] lg:text-[52px]" x-text="slide.title"></h1>
                            <p class="mt-4 max-w-2xl text-[16px] leading-relaxed text-white/80" x-text="slide.subtitle"></p>
                            <p class="mt-2 text-xs font-medium uppercase tracking-wide text-white/50">{{ __('Côte d’Ivoire · De l’étude à la réception · Clés en main') }}</p>
                            <div class="mt-7 flex flex-wrap gap-3">
                                <a :href="slide.ctaUrl" class="inline-flex items-center gap-2 rounded-full bg-accent px-6 py-3.5 font-display text-sm font-bold text-primary-900 shadow-sm hover:bg-accent-300 hover:shadow transition" x-text="slide.ctaLabel"></a>
                                <a :href="slide.secondaryUrl" class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/5 px-6 py-3.5 font-display text-sm font-semibold text-white backdrop-blur hover:bg-white/10 transition" x-text="slide.secondaryLabel"></a>
                            </div>
                            <div class="mt-6 flex items-center gap-3 text-xs text-white/60">
                                <span class="inline-flex items-center gap-1.5"><span class="h-1.5 w-1.5 rounded-full bg-success"></span> {{ __('Garantie décennale') }}</span>
                                <span>·</span>
                                <span>{{ __('QHSE · ISO 9001') }}</span>
                            </div>
                        </div>
                    </template>
                    <noscript>
                        <p class="inline-flex rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-accent">{{ __('BTP · Génie civil · VRD · Énergie') }}</p>
                        <h1 class="mt-4 font-display text-[36px] font-extrabold leading-[0.9] tracking-tight sm:text-[48px] lg:text-[52px]">{{ $heroTitle }}</h1>
                        <p class="mt-4 max-w-2xl text-[16px] leading-relaxed text-white/80">{{ $heroSubtitle }}</p>
                        <p class="mt-2 text-xs font-medium uppercase tracking-wide text-white/50">{{ __('Côte d’Ivoire · De l’étude à la réception') }}</p>
                        <div class="mt-7 flex flex-wrap gap-3">
                            <a href="{{ ($heroCtaUrl ?? null) ?: route('public.quote.create') }}" class="inline-flex items-center gap-2 rounded-full bg-accent px-6 py-3.5 font-display text-sm font-bold text-primary-900 hover:bg-accent-300 transition">{{ ($heroCtaLabel ?? null) ?: __('Demander une étude de faisabilité') }} <span>→</span></a>
                            <a href="{{ route('public.contact') }}" class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/5 px-6 py-3.5 text-sm font-semibold text-white hover:bg-white/10 transition">{{ __('Prendre RDV avec un ingénieur') }}</a>
                        </div>
                    </noscript>
                    <div class="mt-8 flex flex-wrap items-center gap-4">
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
                            <button @click="prev()" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-white/20 text-white hover:bg-white/10 transition" aria-label="{{ __('Précédent') }}">‹</button>
                            <button @click="next()" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-white/20 text-white hover:bg-white/10 transition" aria-label="{{ __('Suivant') }}">›</button>
                            <button @click="toggle()" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-white/20 text-white hover:bg-white/10 transition" :aria-label="playing ? __('Pause') : __('Lecture')">
                                <span x-show="playing" class="text-[11px] leading-none" aria-hidden="true">❚❚</span>
                                <span x-show="!playing" class="text-[11px] leading-none translate-x-px" aria-hidden="true">▶</span>
                            </button>
                        </div>
                        <span class="text-xs font-mono text-white/50" aria-live="polite"><span x-text="current + 1"></span> / <span x-text="slides.length"></span></span>
                    </div>
                </div>
                <div class="relative hidden lg:col-span-5 lg:block">
                    <div class="aspect-[4/3] overflow-hidden rounded-2xl bg-white/5 p-2 shadow-[0_16px_50px_rgba(0,0,0,0.25)] ring-1 ring-white/10 backdrop-blur">
                        <template x-for="(slide, index) in slides" :key="index">
                            <img
                                x-show="current === index"
                                x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 scale-[0.98]"
                                x-transition:enter-end="opacity-100 scale-100"
                                :src="slide.image"
                                :alt="slide.title"
                                class="h-full w-full rounded-xl object-cover"
                                x-cloak
                            >
                        </template>
                        <noscript>
                            @if($heroImage)
                                <img src="{{ $heroImage }}" alt="{{ $heroTitle }}" class="h-full w-full rounded-xl object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center rounded-xl bg-white/10 text-sm text-white/50">{{ __('Chantier d’envergure — 4:3') }}</div>
                            @endif
                        </noscript>
                    </div>
                    <div class="absolute -bottom-4 -left-4 rounded-2xl bg-white px-4 py-3 shadow-[0_8px_30px_rgba(0,0,0,0.18)] ring-1 ring-border">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-900 text-accent">✓</div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wide text-neutral-500">{{ __('Livré') }}</div>
                                <div class="font-display text-sm font-bold text-primary-900">{{ __('14 mois · 15 000 m²') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -top-3 -right-3 hidden rounded-full bg-accent px-3 py-1.5 text-xs font-bold text-primary-900 shadow sm:block">{{ __('Clés en main') }}</div>
                </div>
            </div>
        </div>
        <div class="pointer-events-none absolute bottom-0 left-0 h-1 w-full bg-white/10">
            <div class="h-full bg-accent transition-all ease-linear" :style="`width: ${((current + 1) / slides.length) * 100}%; transition-duration: ${interval}ms`" x-show="playing"></div>
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
                    if (this.slides[1]?.image) { const i = new Image(); i.src = this.slides[1].image; }
                    this.play();
                },
                play() { this.pause(); if (!this.playing) return; this.timer = setInterval(() => this.next(), this.interval); },
                pause() { if (this.timer) clearInterval(this.timer); this.timer = null; },
                toggle() { this.playing = !this.playing; if (this.playing) this.play(); else this.pause(); },
                next() { this.current = (this.current + 1) % this.slides.length; this.preloadNext(); },
                prev() { this.current = (this.current - 1 + this.slides.length) % this.slides.length; this.preloadNext(); },
                go(i) { this.current = i; this.play(); this.preloadNext(); },
                preloadNext() { const n = (this.current + 1) % this.slides.length; const src = this.slides[n]?.image; if (src) { const img = new Image(); img.src = src; } },
            };
        };
    </script>
    <style>[x-cloak]{display:none !important}</style>

    {{-- 2. Barre de Crédibilité — logos + certifications --}}
    <section class="border-y border-border bg-surface">
        <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-6 py-6 sm:py-7 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-[0.18em] text-neutral-500">
                    <span class="text-primary-700">{{ __('Ils nous font confiance') }}</span>
                    <span class="hidden h-3 w-px bg-border sm:block"></span>
                    <span>{{ __('Institutions & grands comptes') }}</span>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    @forelse($partners as $partner)
                        <span class="rounded-full border border-border bg-background px-3.5 py-1.5 text-xs font-semibold text-neutral-700">{{ $partner->name }}</span>
                    @empty
                        <span class="rounded-full border border-border bg-background px-3.5 py-1.5 text-xs font-semibold text-neutral-700">État de Côte d’Ivoire</span>
                        <span class="rounded-full border border-border bg-background px-3.5 py-1.5 text-xs font-semibold text-neutral-700">PFO Africa</span>
                        <span class="rounded-full border border-border bg-background px-3.5 py-1.5 text-xs font-semibold text-neutral-700">Colas</span>
                        <span class="rounded-full border border-border bg-background px-3.5 py-1.5 text-xs font-semibold text-neutral-700">Lafarge</span>
                    @endforelse
                </div>
                <div class="flex flex-wrap items-center gap-2 border-t border-border pt-6 lg:border-t-0 lg:pt-0">
                    <span class="rounded-full bg-primary-900 px-3 py-1.5 text-xs font-bold text-white">ISO 9001</span>
                    <span class="rounded-full bg-primary-50 px-3 py-1.5 text-xs font-semibold text-primary-700 ring-1 ring-primary-900/10">QHSE</span>
                    <span class="rounded-full bg-accent px-3 py-1.5 text-xs font-bold text-primary-900">Qualibat</span>
                    <span class="rounded-full border border-border bg-surface px-3 py-1.5 text-xs font-semibold text-neutral-700">OPQIBI</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats bento moderne — flottant --}}
    <section class="relative z-10 -mt-4">
        <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
                <div class="group rounded-2xl bg-surface p-5 shadow-[0_8px_30px_rgba(11,31,51,0.08)] ring-1 ring-border hover:shadow-[0_12px_40px_rgba(11,31,51,0.12)] hover:-translate-y-0.5 transition-all sm:p-6">
                    <div class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-primary-900 text-accent text-sm">◆</div>
                    <div class="mt-3 font-display text-[28px] font-extrabold leading-none tracking-tight text-primary-900 sm:text-[32px]">{{ $stats['projects'] }}<span class="text-accent">+</span></div>
                    <div class="mt-1 text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ $statsProjectsLabel ?? __('Projets livrés') }}</div>
                    <div class="mt-1 hidden text-xs leading-relaxed text-neutral-500 sm:block">{{ __('Bâtiment, VRD, génie civil') }}</div>
                </div>
                <div class="group rounded-2xl bg-surface p-5 shadow-[0_8px_30px_rgba(11,31,51,0.08)] ring-1 ring-border hover:shadow-[0_12px_40px_rgba(11,31,51,0.12)] hover:-translate-y-0.5 transition-all sm:p-6">
                    <div class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-accent text-primary-900 text-sm font-bold">{{ $stats['expertises'] }}</div>
                    <div class="mt-3 font-display text-[28px] font-extrabold leading-none tracking-tight text-primary-900 sm:text-[32px]">{{ $stats['expertises'] }}</div>
                    <div class="mt-1 text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ $statsExpertisesLabel ?? __('Expertises') }}</div>
                    <div class="mt-1 hidden text-xs leading-relaxed text-neutral-500 sm:block">{{ __('Mono-entreprise, sans filiales') }}</div>
                </div>
                <div class="group rounded-2xl bg-surface p-5 shadow-[0_8px_30px_rgba(11,31,51,0.08)] ring-1 ring-border hover:shadow-[0_12px_40px_rgba(11,31,51,0.12)] hover:-translate-y-0.5 transition-all sm:p-6">
                    <div class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-primary-100 text-primary-700 text-xs font-bold">◉</div>
                    <div class="mt-3 font-display text-[28px] font-extrabold leading-none tracking-tight text-primary-900 sm:text-[32px]">{{ $stats['partners'] }}<span class="text-accent">+</span></div>
                    <div class="mt-1 text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ $statsPartnersLabel ?? __('Partenaires') }}</div>
                    <div class="mt-1 hidden text-xs leading-relaxed text-neutral-500 sm:block">{{ __('Réseau & fournisseurs') }}</div>
                </div>
                <div class="group rounded-2xl bg-primary-900 p-5 text-white shadow-[0_8px_30px_rgba(11,31,51,0.18)] ring-1 ring-white/10 hover:shadow-[0_12px_40px_rgba(11,31,51,0.22)] hover:-translate-y-0.5 transition-all sm:p-6">
                    <div class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-accent text-primary-900 text-xs font-extrabold">20</div>
                    <div class="mt-3 font-display text-[28px] font-extrabold leading-none tracking-tight text-white sm:text-[32px]">20<span class="text-accent">+</span></div>
                    <div class="mt-1 text-xs font-semibold uppercase tracking-[0.18em] text-accent">{{ __('Années') }}</div>
                    <div class="mt-1 hidden text-xs leading-relaxed text-white/60 sm:block">{{ __('Territoire & infrastructures') }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Secteurs d’Activité / Expertises — grille 3 --}}
    <section class="mx-auto max-w-[1280px] px-4 py-16 sm:px-6 lg:px-8 sm:py-20">
        <div class="flex items-center gap-3">
            <span class="h-px w-8 bg-accent"></span>
            <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ __('Secteurs d’activité') }}</p>
        </div>
        <div class="mt-3 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="font-display text-[32px] font-extrabold tracking-tight text-primary-900 sm:text-[40px]">{{ $sectorsTitle ?? __('Trois expertises, un seul interlocuteur') }}</h2>
                <p class="mt-3 max-w-2xl text-[17px] leading-relaxed text-neutral-600">{{ $sectorsSubtitle ?? __('Chaque profil s’y retrouve instantanément — BTP, Aménagement foncier et Agro-industrie, du gros œuvre aux process industriels.') }}</p>
            </div>
            <a href="{{ route('public.expertises.index') }}" class="hidden sm:inline-flex items-center gap-2 rounded-full border border-border bg-surface px-4 py-2 text-sm font-medium text-primary-900 hover:bg-primary-900 hover:text-white hover:border-primary-900 transition">{{ __('Toutes les expertises') }} <span aria-hidden="true">→</span></a>
        </div>

        {{-- Grille 3 secteurs idéaux + tableau comparatif — CMS --}}
        <div class="mt-10 grid gap-5 lg:grid-cols-3">
            {{-- BTP & Génie Civil --}}
            <div class="group relative flex flex-col overflow-hidden rounded-2xl border border-border bg-surface shadow-[0_4px_24px_rgba(11,31,51,0.04)] hover:shadow-[0_12px_40px_rgba(11,31,51,0.08)] hover:-translate-y-1 transition-all">
                <div class="h-1 w-full bg-gradient-to-r from-primary-900 via-accent to-primary-700"></div>
                <div class="aspect-[16/10] overflow-hidden bg-neutral-100 relative">
                    <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-neutral-800 flex items-center justify-center">
                        <span class="text-white/20 font-display text-5xl font-extrabold">BTP</span>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                    <span class="absolute left-3 top-3 rounded-full bg-white px-3 py-1 text-[11px] font-bold text-primary-900 shadow-sm">{{ $sectorBtpBadge ?? __('BTP & Génie Civil') }}</span>
                </div>
                <div class="p-6 flex flex-1 flex-col">
                    <h3 class="font-display text-[18px] font-bold text-primary-900">{{ $sectorBtpTitle ?? __('Construction industrielle, gros œuvre, ouvrages d’art') }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-neutral-600">{{ $sectorBtpDesc ?? __('Engins en action, structures béton/acier — capacité technique et solidité financière pour tenir budget et planning.') }}</p>
                    <div class="mt-4 rounded-xl bg-primary-50 px-3 py-2 ring-1 ring-primary-900/5">
                        <div class="text-xs font-semibold uppercase tracking-wide text-primary-700">{{ __('Argument massue') }}</div>
                        <div class="text-sm font-medium text-primary-900">{{ $sectorBtpArg ?? __('Respect du budget · Solidité financière') }}</div>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-1.5 text-xs">
                        <span class="rounded-full bg-zinc-100 px-2.5 py-1 font-medium text-neutral-700">{{ __('Gros œuvre') }}</span>
                        <span class="rounded-full bg-zinc-100 px-2.5 py-1 font-medium text-neutral-700">{{ __('Ouvrages d’art') }}</span>
                    </div>
                </div>
            </div>

            {{-- Lotissement & Aménagement --}}
            <div class="group relative flex flex-col overflow-hidden rounded-2xl border border-border bg-surface shadow-[0_4px_24px_rgba(11,31,51,0.04)] hover:shadow-[0_12px_40px_rgba(11,31,51,0.08)] hover:-translate-y-1 transition-all">
                <div class="h-1 w-full bg-gradient-to-r from-accent via-primary-700 to-primary-900"></div>
                <div class="aspect-[16/10] overflow-hidden bg-neutral-100 relative">
                    <div class="h-full w-full bg-gradient-to-br from-accent via-amber-400 to-primary-900 flex items-center justify-center">
                        <span class="text-primary-900/20 font-display text-5xl font-extrabold">VRD</span>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    <span class="absolute left-3 top-3 rounded-full bg-white px-3 py-1 text-[11px] font-bold text-primary-900 shadow-sm">{{ $sectorLotBadge ?? __('Lotissement & Aménagement') }}</span>
                </div>
                <div class="p-6 flex flex-1 flex-col">
                    <h3 class="font-display text-[18px] font-bold text-primary-900">{{ $sectorLotTitle ?? __('Viabilisation VRD, aménagement urbain, gestion foncière') }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-neutral-600">{{ $sectorLotDesc ?? __('Vues aériennes drone, plans masse 3D — sécurité juridique du foncier et VRD parfaite.') }}</p>
                    <div class="mt-4 rounded-xl bg-accent/10 px-3 py-2 ring-1 ring-accent/20">
                        <div class="text-xs font-semibold uppercase tracking-wide text-primary-700">{{ __('Argument massue') }}</div>
                        <div class="text-sm font-medium text-primary-900">{{ $sectorLotArg ?? __('Sécurité juridique · VRD parfaite') }}</div>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-1.5 text-xs">
                        <span class="rounded-full bg-zinc-100 px-2.5 py-1 font-medium text-neutral-700">{{ __('Viabilisation') }}</span>
                        <span class="rounded-full bg-zinc-100 px-2.5 py-1 font-medium text-neutral-700">{{ __('Gestion foncière') }}</span>
                    </div>
                </div>
            </div>

            {{-- Agro-industrie --}}
            <div class="group relative flex flex-col overflow-hidden rounded-2xl border border-border bg-surface shadow-[0_4px_24px_rgba(11,31,51,0.04)] hover:shadow-[0_12px_40px_rgba(11,31,51,0.08)] hover:-translate-y-1 transition-all">
                <div class="h-1 w-full bg-gradient-to-r from-success via-primary-700 to-primary-900"></div>
                <div class="aspect-[16/10] overflow-hidden bg-neutral-100 relative">
                    <div class="h-full w-full bg-gradient-to-br from-neutral-100 to-success/20 flex items-center justify-center">
                        <span class="text-success/30 font-display text-5xl font-extrabold">AGRO</span>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    <span class="absolute left-3 top-3 rounded-full bg-success px-3 py-1 text-[11px] font-bold text-white shadow-sm">{{ $sectorAgroBadge ?? __('Agro-industrie') }}</span>
                </div>
                <div class="p-6 flex flex-1 flex-col">
                    <h3 class="font-display text-[18px] font-bold text-primary-900">{{ $sectorAgroTitle ?? __('Usines de transformation, entrepôts frigorifiques, logistique') }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-neutral-600">{{ $sectorAgroDesc ?? __('Intérieurs inox, process automatisés — hygiène stricte et optimisation des flux.') }}</p>
                    <div class="mt-4 rounded-xl bg-success/10 px-3 py-2 ring-1 ring-success/20">
                        <div class="text-xs font-semibold uppercase tracking-wide text-success">{{ __('Argument massue') }}</div>
                        <div class="text-sm font-medium text-primary-900">{{ $sectorAgroArg ?? __('Hygiène stricte · Flux optimisés') }}</div>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-1.5 text-xs">
                        <span class="rounded-full bg-zinc-100 px-2.5 py-1 font-medium text-neutral-700">{{ __('Entrepôts froids') }}</span>
                        <span class="rounded-full bg-zinc-100 px-2.5 py-1 font-medium text-neutral-700">{{ __('Process') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tableau comparatif discret --}}
        <div class="mt-8 overflow-hidden rounded-2xl border border-border bg-surface hidden lg:block">
            <div class="grid grid-cols-4 gap-px bg-border">
                <div class="bg-zinc-50 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-neutral-500">{{ __('Secteur') }}</div>
                <div class="bg-zinc-50 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-neutral-500">{{ __('Visuel à privilégier') }}</div>
                <div class="bg-zinc-50 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-neutral-500">{{ __('Déclencheur d’achat') }}</div>
                <div class="bg-zinc-50 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-neutral-500">{{ __('Action') }}</div>
                <div class="bg-surface px-4 py-3 text-sm font-medium text-primary-900">{{ __('BTP & Génie Civil') }}</div>
                <div class="bg-surface px-4 py-3 text-xs text-neutral-600">{{ __('Engins en action, béton/acier') }}</div>
                <div class="bg-surface px-4 py-3 text-xs text-neutral-600">{{ __('Capacité technique, respect budget') }}</div>
                <div class="bg-surface px-4 py-3"><a href="{{ route('public.quote.create') }}" class="text-xs font-semibold text-accent hover:text-primary-700">{{ __('Devis →') }}</a></div>
                <div class="bg-surface px-4 py-3 text-sm font-medium text-primary-900">{{ __('Lotissement') }}</div>
                <div class="bg-surface px-4 py-3 text-xs text-neutral-600">{{ __('Vue drone, plan masse 3D') }}</div>
                <div class="bg-surface px-4 py-3 text-xs text-neutral-600">{{ __('Sécurité juridique, VRD') }}</div>
                <div class="bg-surface px-4 py-3"><a href="{{ route('public.programs.index') }}" class="text-xs font-semibold text-accent hover:text-primary-700">{{ __('Programmes →') }}</a></div>
                <div class="bg-surface px-4 py-3 text-sm font-medium text-primary-900">{{ __('Agro-industrie') }}</div>
                <div class="bg-surface px-4 py-3 text-xs text-neutral-600">{{ __('Intérieur usine inox, automatisé') }}</div>
                <div class="bg-surface px-4 py-3 text-xs text-neutral-600">{{ __('Hygiène, optimisation flux') }}</div>
                <div class="bg-surface px-4 py-3"><a href="{{ route('public.contact') }}" class="text-xs font-semibold text-accent hover:text-primary-700">{{ __('RDV →') }}</a></div>
            </div>
        </div>

        {{-- Fallback expertises dynamiques (si besoin) --}}
        @if($expertises->isNotEmpty())
            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($expertises as $exp)
                    <a href="{{ route('public.expertises.show', $exp->slug) }}" class="group relative flex flex-col overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-[0_4px_24px_rgba(11,31,51,0.04)] hover:shadow-[0_12px_40px_rgba(11,31,51,0.08)] hover:-translate-y-1 transition-all">
                        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-primary-900 via-accent to-primary-700 opacity-0 group-hover:opacity-100 transition"></div>
                        <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-primary-900 text-white shadow-sm group-hover:bg-accent group-hover:text-primary-900 transition">
                            <span class="text-xs font-extrabold">{{ Str::upper(Str::substr($exp->title,0,2)) }}</span>
                        </div>
                        <h3 class="mt-5 font-display text-[17px] font-bold leading-tight text-primary-900 group-hover:text-primary-700 transition">{{ $exp->title }}</h3>
                        <p class="mt-2 line-clamp-2 text-[14px] leading-relaxed text-neutral-600">{{ $exp->excerpt }}</p>
                        <div class="mt-5 flex items-center gap-2 text-xs font-semibold uppercase tracking-wide">
                            <span class="rounded-full bg-primary-50 px-2.5 py-1 text-primary-700">{{ $exp->services_count }} {{ __('services') }}</span>
                            <span class="ml-auto inline-flex items-center gap-1 text-primary-900 group-hover:gap-1.5 transition-all">{{ __('En savoir plus') }} <span class="text-accent">→</span></span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    {{-- 4. Catalogue Réalisations — Portfolio avec Surface/Durée --}}
    @if($featuredProjects->isNotEmpty())
        <section class="bg-background" x-data="{ active: 'all' }">
            <div class="mx-auto max-w-[1280px] px-4 py-16 sm:px-6 lg:px-8 sm:py-20">
                <div class="flex items-center gap-3">
                    <span class="h-px w-8 bg-accent"></span>
                    <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ __('Portfolio') }}</p>
                </div>
                <div class="mt-3 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="font-display text-[32px] font-extrabold tracking-tight text-primary-900 sm:text-[40px]">{{ $portfolioTitle ?? __('On achète ce que l’on voit') }}</h2>
                        <p class="mt-2 max-w-2xl text-[16px] leading-relaxed text-neutral-600">{{ $portfolioSubtitle ?? __('Projets phares — photo du projet terminé, lieu, nature et indicateurs clés (surface, durée).') }}</p>
                    </div>
                    <a href="{{ route('public.projects.index') }}" class="hidden sm:inline-flex items-center gap-2 rounded-full bg-primary-900 px-4 py-2 text-sm font-medium text-white hover:bg-primary-800 transition">{{ __('Tout voir') }} <span>→</span></a>
                </div>

                @if(($projectCategories ?? collect())->isNotEmpty())
                    <div class="mt-6 flex flex-wrap gap-2">
                        <button @click="active = 'all'" :class="active === 'all' ? 'bg-primary-900 text-white border-primary-900 shadow-sm' : 'border border-border bg-surface text-zinc-700 hover:bg-zinc-50'" class="rounded-full px-4 py-2 text-sm font-medium transition">{{ __('Tous') }}</button>
                        @foreach($projectCategories as $cat)
                            <button @click="active = @js($cat)" :class="active === @js($cat) ? 'bg-primary-900 text-white border-primary-900 shadow-sm' : 'border border-border bg-surface text-zinc-700 hover:bg-zinc-50'" class="rounded-full border px-4 py-2 text-sm font-medium transition">{{ $cat }}</button>
                        @endforeach
                    </div>
                @endif

                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($featuredProjects as $project)
                        <a href="{{ route('public.projects.show', $project->slug) }}" class="group relative flex flex-col overflow-hidden rounded-2xl border border-border bg-surface shadow-[0_8px_30px_rgba(11,31,51,0.06)] hover:shadow-[0_16px_40px_rgba(11,31,51,0.12)] hover:-translate-y-1 transition-all" x-show="active === 'all' || active === @js($project->category)" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-[0.98]" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-[0.98]" x-cloak>
                            <div class="aspect-[4/3] bg-neutral-100 overflow-hidden relative">
                                @if($project->cover_image)
                                    <img src="{{ $project->cover_image }}" alt="{{ $project->title }}" class="h-full w-full object-cover group-hover:scale-[1.05] transition duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent opacity-70 group-hover:opacity-80 transition"></div>
                                @else
                                    <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700 group-hover:scale-[1.05] transition duration-500"></div>
                                @endif
                                <div class="absolute left-3 top-3 flex gap-1.5">
                                    <span class="rounded-full bg-white px-2.5 py-1 text-[11px] font-semibold text-primary-900 shadow-sm">{{ $project->category }}</span>
                                    @if($project->year)<span class="rounded-full bg-accent px-2.5 py-1 text-[11px] font-bold text-primary-900">{{ $project->year }}</span>@endif
                                </div>
                                <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between">
                                    <span class="rounded-full bg-black/50 px-2.5 py-1 text-[11px] font-medium text-white backdrop-blur">{{ $project->location ?? __('Côte d’Ivoire') }}</span>
                                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white text-primary-900 opacity-0 shadow-sm group-hover:opacity-100 translate-x-2 group-hover:translate-x-0 transition-all">→</span>
                                </div>
                            </div>
                            <div class="flex flex-1 flex-col p-5">
                                <h3 class="font-display text-[17px] font-bold leading-tight text-primary-900 line-clamp-2 group-hover:text-primary-700 transition">{{ $project->title }}</h3>
                                <p class="mt-1 text-xs text-neutral-500">{{ $project->category }} · {{ $project->client_name ?? __('Maître d’ouvrage') }}</p>
                                <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                                    <div class="rounded-xl bg-primary-50 px-3 py-2 ring-1 ring-primary-900/5">
                                        <div class="font-semibold uppercase tracking-wide text-primary-700 text-[10px]">{{ __('Surface') }}</div>
                                        <div class="font-bold text-primary-900">@if($project->key_figures && isset(array_values($project->key_figures)[0])) {{ array_values($project->key_figures)[0] }} @else 15 000 m² @endif</div>
                                    </div>
                                    <div class="rounded-xl bg-accent/10 px-3 py-2 ring-1 ring-accent/20">
                                        <div class="font-semibold uppercase tracking-wide text-primary-700 text-[10px]">{{ __('Durée') }}</div>
                                        <div class="font-bold text-primary-900">14 {{ __('mois') }}</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <noscript>
                    <div class="mt-4 text-sm text-neutral-500">{{ __('Filtre désactivé sans JavaScript —') }} <a href="{{ route('public.projects.index') }}" class="font-semibold text-primary-700 hover:text-accent">{{ __('voir toutes les réalisations') }}</a></div>
                </noscript>
            </div>
        </section>
    @endif

    {{-- 5. Garanties & Engagements — 4 blocs icônes — CMS --}}
    <section class="mx-auto max-w-[1280px] px-4 py-16 sm:px-6 lg:px-8 sm:py-20">
        <div class="mx-auto max-w-2xl text-center">
            <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ __('Rassurance') }}</p>
            <h2 class="mt-3 font-display text-[28px] font-extrabold tracking-tight text-primary-900 sm:text-[36px]">{{ $guaranteesTitle ?? __('Nos garanties & engagements') }}</h2>
            <p class="mt-3 text-[16px] leading-relaxed text-neutral-600">{{ $guaranteesSubtitle ?? __('Forces opérationnelles — technique, conformité et réalisations concrètes pour une confiance absolue.') }}</p>
        </div>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-border bg-surface p-6 shadow-[0_4px_24px_rgba(11,31,51,0.04)] hover:shadow-[0_8px_30px_rgba(11,31,51,0.08)] hover:-translate-y-0.5 transition-all text-center">
                <div class="mx-auto inline-flex h-12 w-12 items-center justify-center rounded-xl bg-primary-900 text-accent">⏱</div>
                <h3 class="mt-4 font-display text-[15px] font-bold text-primary-900">{{ $guarantee1Title ?? __('Respect des délais & budgets') }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-neutral-600">{{ $guarantee1Desc ?? __('Gestion rigoureuse du planning, pilotage coûts, zéro dépassement non justifié.') }}</p>
            </div>
            <div class="rounded-2xl border border-border bg-surface p-6 shadow-[0_4px_24px_rgba(11,31,51,0.04)] hover:shadow-[0_8px_30px_rgba(11,31,51,0.08)] hover:-translate-y-0.5 transition-all text-center">
                <div class="mx-auto inline-flex h-12 w-12 items-center justify-center rounded-xl bg-accent text-primary-900">🛡</div>
                <h3 class="mt-4 font-display text-[15px] font-bold text-primary-900">{{ $guarantee2Title ?? __('Conformité & Sécurité QHSE') }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-neutral-600">{{ $guarantee2Desc ?? __('Qualité, Hygiène, Sécurité, Environnement — normes strictes, audits continus.') }}</p>
            </div>
            <div class="rounded-2xl border border-border bg-surface p-6 shadow-[0_4px_24px_rgba(11,31,51,0.04)] hover:shadow-[0_8px_30px_rgba(11,31,51,0.08)] hover:-translate-y-0.5 transition-all text-center">
                <div class="mx-auto inline-flex h-12 w-12 items-center justify-center rounded-xl bg-primary-100 text-primary-700 border border-primary-900/10">▣</div>
                <h3 class="mt-4 font-display text-[15px] font-bold text-primary-900">{{ $guarantee3Title ?? __('Assurances & garanties') }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-neutral-600">{{ $guarantee3Desc ?? __('Garantie décennale, RC Pro — ouvrages couverts, esprit tranquille.') }}</p>
            </div>
            <div class="rounded-2xl border border-border bg-surface p-6 shadow-[0_4px_24px_rgba(11,31,51,0.04)] hover:shadow-[0_8px_30px_rgba(11,31,51,0.08)] hover:-translate-y-0.5 transition-all text-center">
                <div class="mx-auto inline-flex h-12 w-12 items-center justify-center rounded-xl bg-success/10 text-success border border-success/20">✓</div>
                <h3 class="mt-4 font-display text-[15px] font-bold text-primary-900">{{ $guarantee4Title ?? __('Traçabilité & transparence') }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-neutral-600">{{ $guarantee4Desc ?? __('Reporting, DOE, SAV — suivi quotidien, réception sans réserve.') }}</p>
            </div>
        </div>
    </section>

    {{-- 6. Processus — 4 étapes simples — CMS --}}
    <section class="bg-surface border-y border-border">
        <div class="mx-auto max-w-[1280px] px-4 py-16 sm:px-6 lg:px-8 sm:py-20">
            <div class="mx-auto max-w-2xl text-center">
                <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ __('Processus') }}</p>
                <h2 class="mt-3 font-display text-[28px] font-extrabold tracking-tight text-primary-900 sm:text-[36px]">{{ $processTitle ?? __('Comment se déroule la collaboration ?') }}</h2>
                <p class="mt-3 text-[16px] leading-relaxed text-neutral-600">{{ $processSubtitle ?? __('Enlever le stress des grands travaux — 4 étapes claires, de l’étude à la réception.') }}</p>
            </div>
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="relative overflow-hidden rounded-2xl border border-border bg-background p-6 hover:shadow-[0_8px_30px_rgba(11,31,51,0.08)] hover:-translate-y-0.5 transition-all">
                    <div class="absolute right-4 top-4 font-display text-5xl font-extrabold text-primary-900/5">01</div>
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-primary-900 text-accent">◎</div>
                    <h3 class="mt-4 font-display text-[16px] font-bold text-primary-900">{{ $process1Title ?? __('Étude & Diagnostic') }}</h3>
                    <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-accent">{{ $process1Subtitle ?? __('Bureau d’études') }}</p>
                    <p class="mt-2 text-sm leading-relaxed text-neutral-600">{{ $process1Desc ?? __('Analyse faisabilité, relevés, contraintes — base technique solide.') }}</p>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-border bg-background p-6 hover:shadow-[0_8px_30px_rgba(11,31,51,0.08)] hover:-translate-y-0.5 transition-all">
                    <div class="absolute right-4 top-4 font-display text-5xl font-extrabold text-primary-900/5">02</div>
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-accent text-primary-900">₵</div>
                    <h3 class="mt-4 font-display text-[16px] font-bold text-primary-900">{{ $process2Title ?? __('Chiffrage & Planification') }}</h3>
                    <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-accent">{{ $process2Subtitle ?? __('Devis détaillé') }}</p>
                    <p class="mt-2 text-sm leading-relaxed text-neutral-600">{{ $process2Desc ?? __('Budget transparent, planning jalons, autorisations — 2–4 semaines.') }}</p>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-border bg-background p-6 hover:shadow-[0_8px_30px_rgba(11,31,51,0.08)] hover:-translate-y-0.5 transition-all">
                    <div class="absolute right-4 top-4 font-display text-5xl font-extrabold text-primary-900/5">03</div>
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-primary-700 text-white">⬢</div>
                    <h3 class="mt-4 font-display text-[16px] font-bold text-primary-900">{{ $process3Title ?? __('Exécution & Suivi') }}</h3>
                    <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-accent">{{ $process3Subtitle ?? __('Suivi chantier') }}</p>
                    <p class="mt-2 text-sm leading-relaxed text-neutral-600">{{ $process3Desc ?? __('Chantier propre, reporting quotidien, QHSE — pilotage rigoureux.') }}</p>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-border bg-background p-6 hover:shadow-[0_8px_30px_rgba(11,31,51,0.08)] hover:-translate-y-0.5 transition-all">
                    <div class="absolute right-4 top-4 font-display text-5xl font-extrabold text-primary-900/5">04</div>
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-success text-white">✓</div>
                    <h3 class="mt-4 font-display text-[16px] font-bold text-primary-900">{{ $process4Title ?? __('Livraison & Réception') }}</h3>
                    <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-accent">{{ $process4Subtitle ?? __('Garantie décennale') }}</p>
                    <p class="mt-2 text-sm leading-relaxed text-neutral-600">{{ $process4Desc ?? __('Réception, levées de réserves, DOE — ouvrage durable, SAV inclus.') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 7. Formulaire de Conversion Principal — cœur landing — CMS --}}
    <section class="mx-auto max-w-[1280px] px-4 py-16 sm:px-6 lg:px-8 sm:py-20">
        <div class="grid gap-10 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ __('Convertir') }}</p>
                <h2 class="mt-3 font-display text-[28px] font-extrabold tracking-tight text-primary-900 sm:text-[36px]">{{ $formTitle ?? __('Parlons de votre projet') }}</h2>
                <p class="mt-3 text-[16px] leading-relaxed text-neutral-600">{{ $formSubtitle ?? __('Remplissez ce formulaire — nous qualifions votre besoin et revenons vers vous sous 24h avec un ingénieur dédié. Montants élevés, cycle long : on sécurise chaque étape.') }}</p>
                <div class="mt-6 space-y-3 text-sm text-neutral-600">
                    <div class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-success"></span> {{ __('Réponse sous 24h ouvrées') }}</div>
                    <div class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-accent"></span> {{ __('Étude de faisabilité offerte') }}</div>
                    <div class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-primary-700"></span> {{ __('Confidentialité & RGPD') }}</div>
                </div>
                <div class="mt-8 rounded-2xl bg-primary-900 p-6 text-white">
                    <div class="text-sm font-semibold text-accent">{{ __('Besoin urgent ?') }}</div>
                    <a href="tel:{{ \App\Models\SiteSetting::get('contact_phone', '+225 27 22 00 00 00') }}" class="mt-1 font-display text-xl font-bold hover:text-accent transition">{{ \App\Models\SiteSetting::get('contact_phone', '+225 27 22 00 00 00') }}</a>
                    <p class="mt-1 text-xs text-white/60">{{ \App\Models\SiteSetting::get('contact_address', 'Abidjan, Cocody') }}</p>
                </div>
            </div>
            <div class="lg:col-span-7">
                <form method="POST" action="{{ route('public.quote.store') }}" class="rounded-2xl border border-border bg-surface p-6 shadow-[0_8px_30px_rgba(11,31,51,0.08)] sm:p-8">
                    @csrf
                    @if($errors->any())
                        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            <div class="font-semibold">{{ __('Veuillez corriger :') }}</div>
                            <ul class="mt-2 list-disc pl-5 space-y-1">
                                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-zinc-700">{{ __('Nom *') }} <span class="text-zinc-400 font-normal">{{ __('(Prénom + Nom)') }}</span></label>
                            <div class="mt-1.5 grid grid-cols-2 gap-2">
                                <input name="first_name" value="{{ old('first_name') }}" required placeholder="{{ __('Prénom') }}" class="w-full rounded-xl border border-zinc-200 bg-white px-3.5 py-3 text-sm shadow-sm focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none">
                                <input name="last_name" value="{{ old('last_name') }}" required placeholder="{{ __('Nom') }}" class="w-full rounded-xl border border-zinc-200 bg-white px-3.5 py-3 text-sm shadow-sm focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-700">{{ __('Entreprise *') }}</label>
                            <input name="company" value="{{ old('company') }}" required placeholder="{{ __('Société / Institution') }}" class="mt-1.5 w-full rounded-xl border border-zinc-200 bg-white px-3.5 py-3 text-sm shadow-sm focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-700">{{ __('Téléphone *') }}</label>
                            <input name="phone" value="{{ old('phone') }}" required placeholder="{{ __('+225 ...') }}" class="mt-1.5 w-full rounded-xl border border-zinc-200 bg-white px-3.5 py-3 text-sm shadow-sm focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-zinc-700">{{ __('Email *') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="{{ __('vous@entreprise.ci') }}" class="mt-1.5 w-full rounded-xl border border-zinc-200 bg-white px-3.5 py-3 text-sm shadow-sm focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-sm font-medium text-zinc-700">{{ __('Type de projet *') }}</label>
                            <select name="service_type" required class="mt-1.5 w-full rounded-xl border border-zinc-200 bg-white px-3.5 py-3 text-sm shadow-sm focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none">
                                <option value="">{{ __('Choisir — BTP, Aménagement...') }}</option>
                                <option value="BTP & Génie Civil" @selected(old('service_type')=='BTP & Génie Civil')>BTP & Génie Civil</option>
                                <option value="Lotissement & Aménagement" @selected(old('service_type')=='Lotissement & Aménagement')>Lotissement & Aménagement</option>
                                <option value="Agro-industrie" @selected(old('service_type')=='Agro-industrie')>Agro-industrie</option>
                                <option value="Bâtiment" @selected(old('service_type')=='Bâtiment')>Bâtiment</option>
                                <option value="VRD" @selected(old('service_type')=='VRD')>VRD</option>
                                <option value="Énergie" @selected(old('service_type')=='Énergie')>Énergie</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-sm font-medium text-zinc-700">{{ __('Décrivez brièvement le projet *') }}</label>
                            <textarea name="description" rows="4" required placeholder="{{ __('Surface, localisation, budget indicatif, délai souhaité…') }}" class="mt-1.5 w-full rounded-xl border border-zinc-200 bg-white px-3.5 py-3 text-sm shadow-sm placeholder:text-zinc-400 focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <label class="mt-5 flex items-start gap-3 rounded-xl bg-zinc-50 p-3 ring-1 ring-zinc-200">
                        <input type="checkbox" name="consent" value="1" required class="mt-0.5 h-4 w-4 rounded border-zinc-300 text-primary-600 focus:ring-accent">
                        <span class="text-xs leading-relaxed text-zinc-600">{{ __('J’accepte que mes données soient utilisées pour traiter ma demande (RGPD).') }}</span>
                    </label>
                    <button type="submit" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-full bg-accent px-8 py-4 text-sm font-display font-bold text-primary-900 shadow-sm hover:bg-accent-300 hover:shadow transition sm:w-auto">{{ __('Demander mon étude de faisabilité') }} <span>→</span></button>
                    <p class="mt-3 text-center text-xs text-zinc-500 sm:text-left">{{ $formNote ?? __('Lead qualifié — pas de vente impulsive. Réponse d’un ingénieur sous 24h.') }}</p>
                </form>
            </div>
        </div>
    </section>

    {{-- Actualités — bento moderne --}}
    @if($latestPosts->isNotEmpty())
        <section class="mx-auto max-w-[1280px] px-4 py-16 sm:px-6 lg:px-8 sm:py-20">
            <div class="flex items-center gap-3">
                <span class="h-px w-8 bg-accent"></span>
                <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ __('Actualités') }}</p>
            </div>
            <div class="mt-3 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <h2 class="font-display text-[32px] font-extrabold tracking-tight text-primary-900 sm:text-[40px]">{{ __('Actualités & chantiers') }}</h2>
                <a href="{{ route('public.posts.index') }}" class="hidden sm:inline-flex items-center gap-2 rounded-full border border-border bg-surface px-4 py-2 text-sm font-medium text-primary-900 hover:bg-primary-900 hover:text-white transition">{{ __('Toutes les actualités') }} <span>→</span></a>
            </div>
            <div class="mt-10 grid gap-6 sm:grid-cols-3">
                @foreach($latestPosts as $post)
                    <a href="{{ route('public.posts.show', $post->slug) }}" class="group flex flex-col overflow-hidden rounded-2xl border border-border bg-surface shadow-[0_4px_24px_rgba(11,31,51,0.04)] hover:shadow-[0_12px_40px_rgba(11,31,51,0.08)] hover:-translate-y-1 transition-all">
                        <div class="h-1 w-full bg-gradient-to-r from-primary-900 via-accent to-primary-700 opacity-0 group-hover:opacity-100 transition"></div>
                        <div class="p-6 flex flex-1 flex-col">
                            <div class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-wide">
                                <span class="rounded-full bg-primary-50 px-2.5 py-1 text-primary-700">{{ $post->category }}</span>
                                <span class="text-neutral-400">·</span>
                                <span class="text-neutral-500">{{ $post->published_at?->format('d/m/Y') }}</span>
                            </div>
                            <h3 class="mt-4 font-display text-[17px] font-bold leading-snug text-primary-900 line-clamp-2 group-hover:text-primary-700 transition">{{ $post->title }}</h3>
                            <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-neutral-600">{{ $post->excerpt }}</p>
                            <div class="mt-5 inline-flex items-center gap-1.5 text-xs font-semibold text-primary-700 group-hover:gap-2 transition-all">{{ __('Lire') }} <span class="text-accent">→</span></div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-8 text-center sm:hidden">
                <a href="{{ route('public.posts.index') }}" class="inline-flex rounded-full border border-primary-900 px-5 py-2 text-sm font-semibold text-primary-900 hover:bg-primary-900 hover:text-white transition">{{ __('Toutes les actualités') }} <span>→</span></a>
            </div>
        </section>
    @endif

    {{-- Témoignages — moderne bento --}}
    @if($testimonials->isNotEmpty())
        <section class="relative overflow-hidden bg-primary-900 text-white">
            <div class="pointer-events-none absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px); background-size: 40px 40px;"></div>
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-white/[0.04] to-transparent"></div>
            <div class="relative mx-auto max-w-[1280px] px-4 py-16 sm:px-6 lg:px-8 sm:py-20">
                <div class="flex items-center gap-3">
                    <span class="h-px w-8 bg-accent"></span>
                    <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-accent">{{ __('Confiance') }}</p>
                </div>
                <h2 class="mt-3 font-display text-[32px] font-extrabold tracking-tight sm:text-[40px]">{{ __('Ils nous font confiance') }}</h2>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-white/70">{{ __('Maîtres d’ouvrage, architectes et industriels — retours terrain.') }}</p>
                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    @foreach($testimonials as $t)
                        <div class="relative flex flex-col rounded-2xl bg-white/[0.06] p-6 ring-1 ring-white/10 backdrop-blur hover:bg-white/[0.08] transition">
                            <div class="text-3xl leading-none text-accent/80">“</div>
                            <p class="mt-2 flex-1 text-[15px] leading-relaxed text-white/90">“{{ Str::limit($t->content, 160) }}”</p>
                            <div class="mt-6 flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-accent text-primary-900 text-xs font-extrabold">{{ Str::upper(Str::substr($t->author_name,0,1)) }}</div>
                                <div>
                                    <div class="font-display text-sm font-bold text-white">{{ $t->author_name }}</div>
                                    <div class="text-xs text-white/60">{{ $t->role }} @if($t->company) · {{ $t->company }} @endif</div>
                                </div>
                                <div class="ml-auto hidden sm:flex gap-0.5 text-accent text-xs">★★★★★</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Partenaires — logo cloud moderne --}}
    @if($partners->isNotEmpty())
        <section class="border-y border-border bg-surface">
            <div class="mx-auto max-w-[1280px] px-4 py-10 sm:px-6 lg:px-8">
                <p class="text-center font-display text-[11px] font-semibold uppercase tracking-[0.18em] text-neutral-500">{{ __('Ils nous font confiance — partenaires & fournisseurs') }}</p>
                <div class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-4">
                    @foreach($partners as $partner)
                        <div class="flex items-center justify-center rounded-2xl border border-border bg-background px-4 py-5 text-sm font-semibold text-neutral-700 shadow-sm hover:border-accent/30 hover:shadow-md transition">
                            <span class="truncate">{{ $partner->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA final — moderne gradient + bento --}}
    <section class="relative overflow-hidden bg-primary-900">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
        <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px); background-size: 40px 40px;"></div>
        <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-accent/20 blur-3xl"></div>
        <div class="absolute -left-20 -bottom-20 h-72 w-72 rounded-full bg-white/5 blur-3xl"></div>
        <div class="relative mx-auto max-w-[1280px] px-4 py-14 sm:px-6 lg:px-8 sm:py-16">
            <div class="rounded-2xl bg-white p-8 shadow-[0_16px_50px_rgba(0,0,0,0.18)] sm:p-10 lg:flex lg:items-center lg:justify-between lg:gap-8">
                <div>
                    <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ __('Démarrons') }}</p>
                    <h2 class="mt-2 font-display text-[26px] font-extrabold tracking-tight text-primary-900 sm:text-[30px]">{{ __('Prêt à lancer votre projet ?') }}</h2>
                    <p class="mt-2 max-w-xl text-[15px] leading-relaxed text-neutral-600">{{ __('Obtenez une étude et un devis détaillé sous 48h. Réponse d’un ingénieur, pas d’un bot.') }}</p>
                    <div class="mt-4 flex flex-wrap gap-2 text-xs text-neutral-500">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-primary-50 px-3 py-1 font-medium text-primary-700"><span class="h-1.5 w-1.5 rounded-full bg-success"></span> {{ __('Sans engagement') }}</span>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-accent/15 px-3 py-1 font-medium text-primary-900">{{ __('48h') }} · {{ __('Gratuit') }}</span>
                    </div>
                </div>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row lg:mt-0 lg:shrink-0">
                    <a href="{{ route('public.quote.create') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-accent px-7 py-3.5 text-sm font-display font-bold text-primary-900 shadow-sm hover:bg-accent-300 hover:shadow transition">{{ __('Demander un devis') }} <span>→</span></a>
                    <a href="{{ route('public.contact') }}" class="inline-flex items-center justify-center rounded-full border border-border bg-surface px-7 py-3.5 text-sm font-semibold text-primary-900 hover:bg-zinc-50 transition">{{ __('Parler à un expert') }}</a>
                </div>
            </div>
            <p class="mt-4 text-center text-xs text-white/60">{{ __('Moyenne 4.8/5 sur 124 projets livrés — traçabilité & SAV inclus.') }}</p>
        </div>
    </section>
</x-layouts.public>
