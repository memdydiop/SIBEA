<x-layouts.public :title="$aboutTitle">
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight">{{ $aboutTitle }}</h1>
        <div class="prose mt-6 max-w-none text-zinc-700">
            @if($aboutContent)
                {!! nl2br(e($aboutContent)) !!}
            @else
                <p>{{ __('SIBEA est une entreprise mono-entreprise BTP intervenant en Bâtiment, Génie civil, VRD, Aménagement, Lotissement et Énergie. Organisation : Directions, Départements/Services, Équipes, Employés — sans filiales ni agences (CDC 1.1).') }}</p>
                <h3>{{ __('Vision') }}</h3>
                <p>{{ __('Accompagner chaque projet de l’acquisition au chantier jusqu’à la réception et l’archivage, avec traçabilité, sécurité et performance.') }}</p>
                <h3>{{ __('Valeurs') }}</h3>
                <ul>
                    <li>{{ __('Excellence opérationnelle') }}</li>
                    <li>{{ __('Sécurité et QHSE') }}</li>
                    <li>{{ __('Transparence et traçabilité') }}</li>
                    <li>{{ __('Innovation sobre') }}</li>
                </ul>
            @endif
        </div>
        <div class="mt-8">
            <a href="{{ route('public.quote.create') }}" class="inline-flex rounded bg-zinc-900 px-4 py-2 text-sm font-semibold text-white">{{ __('Nous contacter') }}</a>
        </div>
    </div>
</x-layouts.public>
