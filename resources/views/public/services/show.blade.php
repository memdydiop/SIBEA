<x-layouts.public :title="$service->meta_title ?? $service->title" :metaDescription="$service->meta_description ?? $service->excerpt">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ $service->expertise ? route('public.expertises.show', $service->expertise->slug) : route('public.expertises.index') }}" class="text-sm text-zinc-500 hover:text-zinc-900">← {{ $service->expertise?->title ?? __('Expertises') }}</a>
        <h1 class="mt-4 text-3xl font-bold tracking-tight">{{ $service->title }}</h1>
        @if($service->excerpt)
            <p class="mt-2 max-w-3xl text-zinc-600">{{ $service->excerpt }}</p>
        @endif
        @if($service->content)
            <div class="prose mt-6 max-w-none text-zinc-700">{!! nl2br(e($service->content)) !!}</div>
        @endif

        @if($relatedServices->isNotEmpty())
            <h2 class="mt-10 text-xl font-semibold">{{ __('Autres services') }}</h2>
            <div class="mt-4 grid gap-6 sm:grid-cols-2">
                @foreach($relatedServices as $rel)
                    <a href="{{ route('public.services.show', $rel->slug) }}" class="rounded-xl border border-zinc-200 p-6 hover:shadow-sm">
                        <h3 class="font-semibold">{{ $rel->title }}</h3>
                        <p class="mt-2 text-sm text-zinc-600">{{ $rel->excerpt }}</p>
                    </a>
                @endforeach
            </div>
        @endif

        <div class="mt-10 rounded-xl bg-zinc-900 p-6 text-white">
            <h3 class="font-semibold">{{ __('Besoin de ce service ?') }}</h3>
            <p class="mt-1 text-sm text-zinc-300">{{ __('Demandez un devis détaillé, réponse sous 48h.') }}</p>
            <a href="{{ route('public.quote.create') }}" class="mt-4 inline-flex rounded bg-white px-4 py-2 text-sm font-semibold text-zinc-900">{{ __('Demander un devis') }}</a>
        </div>
    </div>
</x-layouts.public>
