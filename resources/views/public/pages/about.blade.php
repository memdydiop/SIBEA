<x-layouts.public :title="$aboutTitle">
    {{-- Hero CDC --}}
    <section class="relative overflow-hidden bg-primary-900 text-white">
        <div class="absolute inset-0">
            <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
            <div class="absolute inset-0 bg-primary-900/70"></div>
            <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px); background-size: 40px 40px;"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 sm:py-16">
            <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-accent">{{ __('À propos') }}</p>
            <h1 class="mt-3 font-display text-[32px] font-extrabold leading-tight sm:text-[40px]">{{ $aboutTitle }}</h1>
            <p class="mt-3 max-w-2xl text-[16px] leading-relaxed text-white/80">{{ __('SIBEA — Bâtir le territoire avec excellence opérationnelle, sécurité QHSE et innovation sobre depuis plus de 20 ans.') }}</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('public.quote.create') }}" class="inline-flex items-center rounded-sm bg-accent px-5 py-2.5 text-sm font-display font-semibold text-primary-900 hover:bg-accent-300 transition">{{ __('Demander un devis') }}</a>
                <a href="{{ route('public.team.index') }}" class="inline-flex items-center rounded-sm border border-white/20 bg-transparent px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/10 transition">{{ __('Voir l’équipe') }}</a>
            </div>
        </div>
    </section>

    <div class="bg-background">
        <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8 sm:py-12">
            {{-- Stats sobres 3 colonnes --}}
            <div class="grid grid-cols-3 gap-4 rounded-xl border border-zinc-200 bg-white p-4 sm:p-5">
                <div class="text-center">
                    <div class="font-display text-xl font-bold text-primary-900">20+</div>
                    <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Ans d’expérience') }}</div>
                </div>
                <div class="text-center border-x border-zinc-200">
                    <div class="font-display text-xl font-bold text-primary-900">100+</div>
                    <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Projets') }}</div>
                </div>
                <div class="text-center">
                    <div class="font-display text-xl font-bold text-accent">BTP</div>
                    <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Mono-entreprise') }}</div>
                </div>
            </div>

            <div class="prose mt-8 max-w-none text-zinc-800 sm:prose-zinc">
                @if($aboutContent)
                    {!! nl2br(e($aboutContent)) !!}
                @else
                    <p>{{ __('SIBEA est une entreprise mono-entreprise BTP intervenant en Bâtiment, Génie civil, VRD, Aménagement, Lotissement et Énergie. Organisation : Directions, Départements/Services, Équipes, Employés — sans filiales ni agences (CDC 1.1).') }}</p>
                    <h3 class="font-display text-primary-900">{{ __('Vision') }}</h3>
                    <p>{{ __('Accompagner chaque projet de l’acquisition au chantier jusqu’à la réception et l’archivage, avec traçabilité, sécurité et performance.') }}</p>
                    <h3 class="font-display text-primary-900">{{ __('Valeurs') }}</h3>
                    <ul>
                        <li>{{ __('Excellence opérationnelle') }}</li>
                        <li>{{ __('Sécurité et QHSE') }}</li>
                        <li>{{ __('Transparence et traçabilité') }}</li>
                        <li>{{ __('Innovation sobre') }}</li>
                    </ul>
                @endif
            </div>

            <div class="mt-10 flex flex-wrap gap-3">
                <a href="{{ route('public.quote.create') }}" class="inline-flex items-center rounded-sm bg-accent px-5 py-2.5 text-sm font-display font-semibold text-primary-900 hover:bg-accent-300 transition">{{ __('Nous contacter') }}</a>
                <a href="{{ route('public.expertises.index') }}" class="inline-flex items-center rounded-sm border border-zinc-300 bg-white px-5 py-2.5 text-sm font-semibold text-zinc-700 hover:bg-zinc-50 transition">{{ __('Nos expertises') }}</a>
            </div>
        </div>
    </div>
</x-layouts.public>
