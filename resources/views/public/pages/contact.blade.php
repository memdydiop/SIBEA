<x-layouts.public :title="__('Contact')">
    <div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight">{{ __('Contact') }}</h1>
        <p class="mt-2 text-zinc-600">{{ __('Une question, un projet ? Écrivez-nous.') }}</p>

        <div class="mt-8 grid gap-8 lg:grid-cols-2">
            <div class="space-y-4 text-sm">
                <div class="rounded-xl border border-zinc-200 p-6">
                    <div class="font-semibold">{{ __('Coordonnées') }}</div>
                    <div class="mt-2 text-zinc-600">{{ $contactAddress }}<br>{{ $contactEmail }}<br>{{ $contactPhone }}</div>
                </div>
                <div class="rounded-xl bg-zinc-900 p-6 text-white">
                    <div class="font-semibold">{{ __('Demander un devis') }}</div>
                    <p class="mt-2 text-sm text-zinc-300">{{ __('Obtenez une étude détaillée sous 48h.') }}</p>
                    <a href="{{ route('public.quote.create') }}" class="mt-4 inline-flex rounded bg-white px-4 py-2 text-sm font-semibold text-zinc-900">{{ __('Faire une demande') }}</a>
                </div>
            </div>
            <div class="rounded-xl border border-zinc-200 p-6">
                <h3 class="font-semibold">{{ __('Écrivez-nous') }}</h3>
                <p class="mt-2 text-sm text-zinc-600">{{ __('Pour un devis, utilisez le formulaire dédié.') }}</p>
                <a href="{{ route('public.quote.create') }}" class="mt-4 inline-flex rounded bg-zinc-900 px-4 py-2 text-sm font-semibold text-white">{{ __('Aller au formulaire devis') }}</a>
            </div>
        </div>
    </div>
</x-layouts.public>
