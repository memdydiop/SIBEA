<x-layouts.public :title="__('Demande envoyée')">
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:px-8 text-center">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-600">✓</div>
        <h1 class="mt-4 text-2xl font-bold">{{ __('Demande envoyée !') }}</h1>
        <p class="mt-2 text-zinc-600">{{ __('Merci :name, votre demande a bien été enregistrée.', ['name' => $quote->full_name]) }}</p>
        <p class="mt-2 text-sm text-zinc-500">{{ __('Référence : :ref', ['ref' => $quote->reference]) }}</p>
        <p class="mt-4 text-sm text-zinc-600">{{ __('Notre équipe vous recontactera sous 48h à l’adresse :email.', ['email' => $quote->email]) }}</p>
        <div class="mt-8 flex justify-center gap-3">
            <a href="{{ route('home') }}" class="rounded bg-zinc-900 px-4 py-2 text-sm font-medium text-white">{{ __('Retour accueil') }}</a>
            <a href="{{ route('public.projects.index') }}" class="rounded border border-zinc-300 px-4 py-2 text-sm">{{ __('Voir nos réalisations') }}</a>
        </div>
    </div>
</x-layouts.public>
