<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'SIBEA') }} — {{ __('BTP, Génie civil, VRD & Énergie') }}</title>
    <meta name="description" content="{{ $metaDescription ?? __('Entreprise BTP mono-entreprise : bâtiment, génie civil, VRD, aménagement, lotissement et énergie. De l’étude à la réception.') }}">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:title" content="{{ $title ?? config('app.name', 'SIBEA') }}">
    <meta property="og:description" content="{{ $metaDescription ?? '' }}">
    <meta property="og:url" content="{{ $canonical ?? url()->current() }}">
    @if(!empty($ogImage))
        <meta property="og:image" content="{{ $ogImage }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-background text-text antialiased selection:bg-accent selection:text-primary-900">
    {{-- Topbar moderne — contact + réseaux + WhatsApp --}}
    <div class="hidden border-b border-white/10 bg-primary-900 text-white/70 lg:block">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-2 text-xs sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center gap-1.5"><flux:icon.map-pin class="size-3" /> {{ \App\Models\SiteSetting::get('contact_address', 'Abidjan, Côte d’Ivoire') }}</span>
                <span class="hidden sm:inline-flex items-center gap-1.5">· {{ \App\Models\SiteSetting::get('contact_phone', '+225 27 22 00 00 00') }}</span>
                <x-whatsapp-link variant="pill" label="WhatsApp" class="hidden sm:inline-flex" />
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden sm:inline">{{ __('Une seule entreprise, pas de filiales') }}</span>
                <span class="h-3 w-px bg-white/20"></span>
                <a href="{{ route('public.contact') }}" class="hover:text-accent transition">{{ __('Contact') }}</a>
            </div>
        </div>
    </div>

    {{-- Header moderne — glass + blur au scroll --}}
    <header x-data="{ open: false, scrolled: false }" x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 10)" :class="scrolled ? 'bg-primary-900/95 backdrop-blur-xl shadow-[0_4px_30px_rgba(0,0,0,0.12)]' : 'bg-primary-900'" class="sticky top-0 z-40 w-full border-b border-white/10 text-white transition-all duration-300">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3.5 sm:px-6 lg:px-8">
            @php $siteLogo = \App\Models\SiteSetting::get('site_logo'); @endphp
            <a href="{{ route('home') }}" class="flex items-center gap-3 font-display font-bold tracking-tight group">
                @if($siteLogo)
                    <img src="{{ $siteLogo }}" alt="{{ \App\Models\SiteSetting::get('site_name', 'SIBEA') }} Logo" class="h-9 w-auto object-contain rounded-sm ring-1 ring-white/10 group-hover:ring-white/20 transition">
                @else
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-accent text-primary-900 text-sm font-extrabold shadow-sm group-hover:bg-accent-300 transition">SI</span>
                    <span class="text-[18px] font-extrabold tracking-tight text-white">SIBEA</span>
                @endif
            </a>

            @php
                $headerMenu = \App\Models\Menu::where('slug', 'header')->first();
                $headerItems = $headerMenu ? $headerMenu->allItems()->where('is_active', true)->orderBy('order')->get() : collect();
            @endphp
            <nav class="hidden items-center gap-1 text-sm font-medium md:flex" aria-label="Navigation principale">
                @if($headerItems->isNotEmpty())
                    @foreach($headerItems as $navItem)
                        @continue(in_array($navItem->label, ['Équipe', 'Equipe']) || str_contains(strtolower($navItem->url), 'devis') || str_contains(strtolower($navItem->url), 'equipe'))
                        @php
                            $navPath = trim(parse_url($navItem->url, PHP_URL_PATH) ?? $navItem->url, '/');
                            $isActive = ! str_starts_with($navItem->url, 'http') && ($navPath === '' ? request()->is('/') : request()->is($navPath) || request()->is($navPath.'/*'));
                        @endphp
                        <a href="{{ $navItem->url }}" @if($navItem->target === '_blank') target="_blank" rel="noopener noreferrer" @else target="{{ $navItem->target }}" @endif class="relative rounded-full px-3.5 py-2 transition {{ $isActive ? 'bg-white text-primary-900' : 'text-white/80 hover:bg-white/10 hover:text-white' }}" @if($isActive) aria-current="page" @endif>{{ $navItem->label }}</a>
                    @endforeach
                @else
                    <a href="{{ route('public.expertises.index') }}" class="rounded-full px-3.5 py-2 text-white/80 hover:bg-white/10 hover:text-white transition {{ request()->is('expertises*') ? 'bg-white text-primary-900' : '' }}">{{ __('Expertises') }}</a>
                    <a href="{{ route('public.projects.index') }}" class="rounded-full px-3.5 py-2 text-white/80 hover:bg-white/10 hover:text-white transition {{ request()->is('realisations*') ? 'bg-white text-primary-900' : '' }}">{{ __('Réalisations') }}</a>
                    <a href="{{ route('public.programs.index') }}" class="rounded-full px-3.5 py-2 text-white/80 hover:bg-white/10 hover:text-white transition {{ request()->is('programmes*') ? 'bg-white text-primary-900' : '' }}">{{ __('Programmes') }}</a>
                    <a href="{{ route('public.posts.index') }}" class="rounded-full px-3.5 py-2 text-white/80 hover:bg-white/10 hover:text-white transition {{ request()->is('actualites*') ? 'bg-white text-primary-900' : '' }}">{{ __('Actualités') }}</a>
                @endif
            </nav>

            <div class="flex items-center gap-2">

                @auth
                    <a href="{{ route('admin.dashboard') }}" class="hidden lg:inline-flex items-center rounded-full border border-white/15 bg-white/5 px-3.5 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white transition">{{ __('Espace pro') }}</a>
                @else
                    <a href="{{ route('login') }}" class="hidden lg:inline-flex items-center rounded-full border border-white/15 bg-white/5 px-3.5 py-2 text-sm text-white/70 hover:bg-white/10 hover:text-white transition">{{ __('Connexion') }}</a>
                @endauth

                {{-- Mobile burger --}}
                <button @click="open = !open" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/15 bg-white/5 text-white hover:bg-white/10 transition md:hidden" :aria-expanded="open.toString()" aria-label="Menu">
                    <svg x-show="!open" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="open" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile panel --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" x-cloak class="border-t border-white/10 bg-primary-900/95 backdrop-blur-xl md:hidden">
            <nav class="mx-auto max-w-7xl space-y-1 px-4 py-4 sm:px-6">
                @if($headerItems->isNotEmpty())
                    @foreach($headerItems as $navItem)
                        @continue(in_array($navItem->label, ['Équipe', 'Equipe']) || str_contains(strtolower($navItem->url), 'devis') || str_contains(strtolower($navItem->url), 'equipe'))
                        <a href="{{ $navItem->url }}" target="{{ $navItem->target }}" class="flex items-center justify-between rounded-xl px-4 py-3 text-sm font-medium text-white/90 hover:bg-white/10 hover:text-white transition">
                            {{ $navItem->label }} <span aria-hidden="true">→</span>
                        </a>
                    @endforeach
                @else
                    <a href="{{ route('public.expertises.index') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-white/90 hover:bg-white/10">{{ __('Expertises') }}</a>
                    <a href="{{ route('public.projects.index') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-white/90 hover:bg-white/10">{{ __('Réalisations') }}</a>
                    <a href="{{ route('public.programs.index') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-white/90 hover:bg-white/10">{{ __('Programmes') }}</a>
                    <a href="{{ route('public.posts.index') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-white/90 hover:bg-white/10">{{ __('Actualités') }}</a>
                @endif
            </nav>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    {{-- Footer moderne --}}
    <footer class="relative overflow-hidden bg-primary-900 text-white">
        {{-- subtle grid --}}
        <div class="pointer-events-none absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px); background-size: 40px 40px;"></div>
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            {{-- Newsletter bento --}}
            <div class="rounded-2xl bg-white/[0.06] p-6 ring-1 ring-white/10 backdrop-blur sm:p-8 lg:flex lg:items-center lg:justify-between">
                <div>
                    <h3 class="font-display text-lg font-bold text-white">{{ __('Restez informé') }}</h3>
                    <p class="mt-1 text-sm text-white/70">{{ __('Actualités chantiers, livraisons et nouveaux programmes — 1 email par mois.') }}</p>
                </div>
                <form action="{{ route('public.posts.index') }}" method="GET" class="mt-4 flex w-full max-w-md gap-2 lg:mt-0">
                    <input type="email" placeholder="{{ __('Votre email') }}" class="w-full rounded-full border border-white/15 bg-white/10 px-4 py-2.5 text-sm text-white placeholder:text-white/50 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20" disabled>
                    <button type="button" class="shrink-0 rounded-full bg-accent px-5 py-2.5 text-sm font-semibold text-primary-900 hover:bg-accent-300 transition">{{ __('S’abonner') }}</button>
                </form>
            </div>

            <div class="mt-12 grid gap-10 md:grid-cols-5">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3">
                        @if($siteLogo)
                            <img src="{{ $siteLogo }}" alt="SIBEA" class="h-9 w-auto rounded-lg bg-white p-1">
                        @else
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-accent text-primary-900 font-extrabold">SI</span>
                        @endif</div>
                    <p class="mt-3 max-w-sm text-sm leading-relaxed text-white/70">{{ __('Entreprise BTP — Bâtiment, Génie civil, VRD, Énergie. Une seule entreprise, pas de filiales. Solidité · Expertise · Innovation · Territoire.') }}</p>
                    <div class="mt-4 flex gap-2">
                        <x-whatsapp-link variant="icon" />
                        <a href="#" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white/70 hover:bg-white hover:text-primary-900 transition" aria-label="LinkedIn">in</a>
                        <a href="#" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white/70 hover:bg-white hover:text-primary-900 transition" aria-label="Facebook">f</a>
                        <a href="mailto:{{ \App\Models\SiteSetting::get('contact_email', 'contact@sibea.com') }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white/70 hover:bg-white hover:text-primary-900 transition" aria-label="Email">@</a>
                    </div>
                </div>
                <div>
                    <div class="text-sm font-display font-semibold text-white">{{ __('Expertises') }}</div>
                    <ul class="mt-4 space-y-2.5 text-sm text-white/70">
                        @foreach(\App\Models\Expertise::active()->limit(5)->get() as $exp)
                            <li><a href="{{ route('public.expertises.show', $exp->slug) }}" class="inline-flex items-center gap-1.5 hover:text-accent transition"><span class="h-1 w-1 rounded-full bg-accent"></span> {{ $exp->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
                @php
                    $footerMenu = \App\Models\Menu::where('slug', 'footer')->first();
                    $footerItems = $footerMenu ? $footerMenu->allItems()->where('is_active', true)->orderBy('order')->get() : collect();
                @endphp
                <div>
                    <div class="text-sm font-display font-semibold text-white">{{ __('Liens') }}</div>
                    <ul class="mt-4 space-y-2.5 text-sm text-white/70">
                        @if($footerItems->isNotEmpty())
                            @foreach($footerItems as $footerItem)
                                <li><a href="{{ $footerItem->url }}" @if($footerItem->target === '_blank') target="_blank" rel="noopener noreferrer" @else target="{{ $footerItem->target }}" @endif class="hover:text-accent transition">{{ $footerItem->label }}</a></li>
                            @endforeach
                        @else
                            <li><a href="{{ route('public.projects.index') }}" class="hover:text-accent transition">{{ __('Réalisations') }}</a></li>
                            <li><a href="{{ route('public.programs.index') }}" class="hover:text-accent transition">{{ __('Programmes') }}</a></li>
                            <li><a href="{{ route('public.posts.index') }}" class="hover:text-accent transition">{{ __('Actualités') }}</a></li>
                            <li><a href="{{ route('public.contact') }}" class="hover:text-accent transition">{{ __('Contact') }}</a></li>
                        @endif
                    </ul>
                </div>
                <div>
                    <div class="text-sm font-display font-semibold text-white">{{ __('Contact') }}</div>
                    <div class="mt-4 space-y-2 text-sm text-white/70">
                        <p>{{ \App\Models\SiteSetting::get('contact_address', 'Abidjan, Côte d’Ivoire') }}</p>
                        <p><a href="mailto:{{ \App\Models\SiteSetting::get('contact_email', 'contact@sibea.com') }}" class="hover:text-accent">{{ \App\Models\SiteSetting::get('contact_email', 'contact@sibea.com') }}</a></p>
                        <p><a href="tel:{{ \App\Models\SiteSetting::get('contact_phone', '+225 00 00 00 00') }}" class="hover:text-accent">{{ \App\Models\SiteSetting::get('contact_phone', '+225 00 00 00 00') }}</a></p>
                        <p><x-whatsapp-link variant="inline" label="WhatsApp"><span>→</span></x-whatsapp-link></p>
                        <a href="{{ route('public.quote.create') }}" class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-accent px-4 py-2 text-sm font-semibold text-primary-900 hover:bg-accent-300 transition">{{ __('Devis gratuit') }} <span>→</span></a>
                    </div>
                </div>
            </div>
            <div class="mt-10 flex flex-col items-center justify-between gap-3 border-t border-white/10 pt-6 text-xs text-white/50 md:flex-row">
                <span>© {{ now()->year }} SIBEA — {{ __('Tous droits réservés.') }} · {{ __('Conçu pour le territoire') }}</span>
                <span class="flex items-center gap-3"><x-whatsapp-link variant="pill" label="WhatsApp" /> <a href="{{ route('sitemap') }}" class="rounded-full bg-white/10 px-3 py-1 hover:bg-white hover:text-primary-900 transition">sitemap.xml</a> <a href="{{ route('public.pages.show', 'histoire') }}" class="hover:text-accent">{{ __('Histoire') }}</a> · <a href="#" class="hover:text-accent">{{ __('Mentions') }}</a></span>
            </div>
        </div>
    </footer>

    {{-- Bouton flottant WhatsApp — réutilisable --}}
    <x-whatsapp-link variant="float" aria-label="WhatsApp SIBEA" />
</body>
</html>
