<x-layouts.public :title="__('Expertises')">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight">{{ __('Nos expertises') }}</h1>
        <p class="mt-2 max-w-2xl text-zinc-600">{{ __('Une entreprise mono-entreprise couvrant l’ensemble de la chaîne BTP : de l’étude à la réception.') }}</p>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($expertises as $exp)
                <a href="{{ route('public.expertises.show', $exp->slug) }}" class="rounded-xl border border-zinc-200 p-6 hover:shadow-sm">
                    <h3 class="font-semibold">{{ $exp->title }}</h3>
                    <p class="mt-2 text-sm text-zinc-600">{{ $exp->excerpt }}</p>
                    <div class="mt-4 text-xs text-zinc-500">{{ $exp->services_count }} {{ __('services') }}</div>
                </a>
            @empty
                <p class="text-sm text-zinc-500">{{ __('Aucune expertise.') }}</p>
            @endforelse
        </div>
    </div>
</x-layouts.public>
