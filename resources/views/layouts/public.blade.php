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
<body class="min-h-screen bg-background text-text antialiased">
    {{-- Header — bleu nuit CDC 2.1 --}}
    <header class="sticky top-0 z-40 w-full border-b border-primary-900/10 bg-primary-900 text-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            @php $siteLogo = \App\Models\SiteSetting::get('site_logo'); @endphp
            <a href="{{ route('home') }}" class="flex items-center gap-3 font-display font-bold tracking-tight">
                @if($siteLogo)
                    <img src="{{ $siteLogo }}" alt="{{ \App\Models\SiteSetting::get('site_name') }} Logo" class="h-10 w-auto object-contain rounded">
                @else
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-sm bg-accent text-primary-900 text-sm font-extrabold">SI</span>
                    <span class="text-white">SIBEA</span>
                @endif
            </a>
            @php
                $headerMenu = \App\Models\Menu::where('slug', 'header')->first();
                $headerItems = $headerMenu ? $headerMenu->allItems()->where('is_active', true)->orderBy('order')->get() : collect();
            @endphp
            <nav class="hidden gap-6 text-sm font-medium md:flex">
                @if($headerItems->isNotEmpty())
                    @foreach($headerItems as $navItem)
                        @php
                            $navPath = trim(parse_url($navItem->url, PHP_URL_PATH) ?? $navItem->url, '/');
                            $isActive = $navPath === ''
                                        ? request()->is('/')
                                        : request()->is($navPath) || request()->is($navPath . '/*');
                                // Ne pas marquer actif si URL externe ou ancre
                                if (str_starts_with($navItem->url, 'http') || $navItem->url === '#') {
                                    $isActive = false;
                                }
                        @endphp
                        <a href="{{ $navItem->url }}" 
                            @if($navItem->target === '_blank') target="_blank" rel="noopener noreferrer" 
                            @else target="{{ $navItem->target }}" @endif 
                            @class(['text-accent' => $isActive, 'text-white/80 hover:text-accent' ]) 
                            @if($isActive) aria-current="page" @endif>{{ $navItem->label }}</a>
                    @endforeach
                @else
                    <a href="{{ route('public.expertises.index') }}" @class(['text-white/80 hover:text-accent', 'text-accent' => request()->is('expertises/*') || request()->is('expertises')])>{{ __('Expertises') }}</a>
                    <a href="{{ route('public.projects.index') }}" @class(['text-white/80 hover:text-accent', 'text-accent' => request()->is('projects/*') || request()->is('projects')])>{{ __('Réalisations') }}</a>
                    <a href="{{ route('public.programs.index') }}" @class(['text-white/80 hover:text-accent', 'text-accent' => request()->is('programs/*') || request()->is('programs')])>{{ __('Programmes') }}</a>
                    <a href="{{ route('public.posts.index') }}" @class(['text-white/80 hover:text-accent', 'text-accent' => request()->is('posts/*') || request()->is('posts')])>{{ __('Actualités') }}</a>
                    <a href="{{ route('public.team.index') }}" @class(['text-white/80 hover:text-accent', 'text-accent' => request()->is('team/*') || request()->is('team')])>{{ __('Équipe') }}</a>
                @endif
            </nav>
            <div class="flex items-center gap-2">
                {{-- CTA jaune chantier CDC 3.1 --}}
                <a href="{{ route('public.quote.create') }}" class="inline-flex items-center rounded-sm bg-accent px-4 py-2 text-sm font-display font-semibold text-primary-900 hover:bg-accent-300 transition">{{ __('Demander un devis') }}</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="hidden text-sm text-white/70 hover:text-white md:inline">{{ __('Espace pro') }}</a>
                @else
                    <a href="{{ route('login') }}" class="hidden text-sm text-white/70 hover:text-white md:inline">{{ __('Connexion') }}</a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    {{-- Footer — bleu nuit CDC 24 --}}
    <footer class="bg-primary-900 text-white">
        <div class="mx-auto max-w-[1280px] px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-8 md:grid-cols-4">
                <div>
                    <div class="font-display font-bold text-white">SIBEA</div>
                    <p class="mt-2 text-sm leading-relaxed text-white/70">{{ __('Entreprise BTP — Bâtiment, Génie civil, VRD, Énergie. Une seule entreprise, pas de filiales. Solidité · Expertise · Innovation · Territoire.') }}</p>
                    <div class="mt-4 h-px w-12 bg-accent"></div>
                </div>
                <div>
                    <div class="text-sm font-display font-semibold text-white">{{ __('Expertises') }}</div>
                    <ul class="mt-3 space-y-2 text-sm text-white/70">
                        @foreach(\App\Models\Expertise::active()->limit(5)->get() as $exp)
                            <li><a href="{{ route('public.expertises.show', $exp->slug) }}" class="hover:text-accent">{{ $exp->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
                @php
                    $footerMenu = \App\Models\Menu::where('slug', 'footer')->first();
                    $footerItems = $footerMenu ? $footerMenu->allItems()->where('is_active', true)->orderBy('order')->get() : collect();
                @endphp
                <div>
                    <div class="text-sm font-display font-semibold text-white">{{ __('Liens') }}</div>
                    <ul class="mt-3 space-y-2 text-sm text-white/70">
                        @if($footerItems->isNotEmpty())
                            @foreach($footerItems as $footerItem)
                                <li><a href="{{ $footerItem->url }}" @if($footerItem->target === '_blank') target="_blank" rel="noopener noreferrer" @else target="{{ $footerItem->target }}" @endif class="hover:text-accent">{{ $footerItem->label }}</a></li>
                            @endforeach
                        @else
                            <li><a href="{{ route('public.projects.index') }}" class="hover:text-accent">{{ __('Réalisations') }}</a></li>
                            <li><a href="{{ route('public.programs.index') }}" class="hover:text-accent">{{ __('Programmes') }}</a></li>
                            <li><a href="{{ route('public.posts.index') }}" class="hover:text-accent">{{ __('Actualités') }}</a></li>
                            <li><a href="{{ route('public.contact') }}" class="hover:text-accent">{{ __('Contact') }}</a></li>
                        @endif
                    </ul>
                </div>
                <div>
                    <div class="text-sm font-display font-semibold text-white">{{ __('Contact') }}</div>
                    <p class="mt-3 text-sm leading-relaxed text-white/70">{{ \App\Models\SiteSetting::get('contact_address', 'Abidjan, Côte d’Ivoire') }}<br>{{ \App\Models\SiteSetting::get('contact_email', 'contact@sibea.com') }}<br>{{ \App\Models\SiteSetting::get('contact_phone', '+225 00 00 00 00') }}</p>
                    <a href="{{ route('public.quote.create') }}" class="mt-4 inline-flex rounded-sm bg-accent px-4 py-2 text-sm font-semibold text-primary-900 hover:bg-accent-300">{{ __('Devis gratuit') }}</a>
                </div>
            </div>
            <div class="mt-10 flex flex-col items-center justify-between gap-2 border-t border-white/10 pt-6 text-xs text-white/50 md:flex-row">
                <span>© {{ now()->year }} SIBEA — {{ __('Tous droits réservés.') }}</span>
                <span><a href="{{ route('sitemap') }}" class="hover:text-accent">sitemap.xml</a> · <a href="{{ route('public.pages.show', 'histoire') }}" class="hover:text-accent">{{ __('Histoire') }}</a></span>
            </div>
        </div>
    </footer>
</body>
</html>
