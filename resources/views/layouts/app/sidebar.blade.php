<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white">
    <flux:sidebar sticky collapsible="mobile"
        class="bg-[#111c43]! border-e border-white/10! text-[#a3aed1] [--ynex-primary:#845adf] [--ynex-primary-hover:#7a4fd6]"
        data-menu-styles="dark" data-nav-layout="vertical" data-vertical-style="overlay">

        <flux:sidebar.header class="border-b border-white/10 h-16! justify-between! lg:justify-center!">
            <x-app-logo :sidebar="true" href="{{ route('admin.dashboard') }}" wire:navigate />

            <flux:sidebar.collapse class="lg:hidden text-white/70 hover:text-white hover:bg-white/10" />
        </flux:sidebar.header>

        <div data-simplebar class="min-h-0 h-[calc(100vh-((--spacing(16))+(--spacing(12))))]! px-3 py-2">
            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('admin.dashboard')"
                        :current="request()->routeIs('admin.dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                @can('viewAny', App\Models\Department::class)
                    <flux:sidebar.group :heading="__('Administration')" class="grid">
                        <flux:sidebar.item icon="building-office" :href="route('admin.departments')"
                            :current="request()->routeIs('admin.departments')" wire:navigate>
                            {{ __('Départements') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="user-group" :href="route('admin.teams')"
                            :current="request()->routeIs('admin.teams')" wire:navigate>
                            {{ __('Équipes') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="users" :href="route('admin.employees')"
                            :current="request()->routeIs('admin.employees')" wire:navigate>
                            {{ __('Employés') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="shield-check" :href="route('admin.users')"
                            :current="request()->routeIs('admin.users')" wire:navigate>
                            {{ __('Utilisateurs') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="clipboard-document-list" :href="route('admin.audit')"
                            :current="request()->routeIs('admin.audit')" wire:navigate>
                            {{ __('Journal d’audit') }}
                        </flux:sidebar.item>
                    </flux:sidebar.group>
                @endcan

                @can('viewAny', App\Models\Expertise::class)
                    <flux:sidebar.group :heading="__('CMS Vitrine')" class="grid">
                        <flux:sidebar.item icon="home-modern" :href="route('admin.homepage')"
                            :current="request()->routeIs('admin.homepage')" wire:navigate>
                            {{ __('Page d’accueil') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="academic-cap" :href="route('admin.expertises')"
                            :current="request()->routeIs('admin.expertises')" wire:navigate>
                            {{ __('Expertises') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="wrench-screwdriver" :href="route('admin.services')"
                            :current="request()->routeIs('admin.services')" wire:navigate>
                            {{ __('Services') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="building-office-2" :href="route('admin.public-projects')"
                            :current="request()->routeIs('admin.public-projects')" wire:navigate>
                            {{ __('Réalisations') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="newspaper" :href="route('admin.posts')"
                            :current="request()->routeIs('admin.posts')" wire:navigate>
                            {{ __('Actualités') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="building-storefront" :href="route('admin.partners')"
                            :current="request()->routeIs('admin.partners')" wire:navigate>
                            {{ __('Partenaires') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="chat-bubble-left-right" :href="route('admin.testimonials')"
                            :current="request()->routeIs('admin.testimonials')" wire:navigate>
                            {{ __('Témoignages') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="inbox" :href="route('admin.quote-requests')"
                            :current="request()->routeIs('admin.quote-requests')" wire:navigate>
                            {{ __('Devis') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="cog-6-tooth" :href="route('admin.site-settings')"
                            :current="request()->routeIs('admin.site-settings')" wire:navigate>
                            {{ __('Paramètres') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="bars-3" :href="route('admin.menus')"
                            :current="request()->routeIs('admin.menus')" wire:navigate>
                            {{ __('Menus') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="list-bullet" :href="route('admin.menu-items')"
                            :current="request()->routeIs('admin.menu-items')" wire:navigate>
                            {{ __('Éléments de menu') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="document-text" :href="route('admin.pages')"
                            :current="request()->routeIs('admin.pages')" wire:navigate>
                            {{ __('Pages') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="map" :href="route('admin.programs')"
                            :current="request()->routeIs('admin.programs')" wire:navigate>
                            {{ __('Programmes') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="squares-2x2" :href="route('admin.program-lots')"
                            :current="request()->routeIs('admin.program-lots')" wire:navigate>
                            {{ __('Lots') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="photo" :href="route('admin.media')"
                            :current="request()->routeIs('admin.media')" wire:navigate>
                            {{ __('Médiathèque') }}
                        </flux:sidebar.item>
                    </flux:sidebar.group>
                @endcan
            </flux:sidebar.nav>

            <flux:spacer class="hidden" />
        </div>

        <div class="hidden lg:block border-t border-white/10 bg-[#111c43] p-3">
            <x-desktop-user-menu :name="auth()->user()->name" />
        </div>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer" data-test="logout-button">
                        {{ __('Log out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>
