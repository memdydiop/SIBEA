<x-layouts.public :title="$expertise->meta_title ?? $expertise->title" :metaDescription="$expertise->meta_description ?? $expertise->excerpt">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ route('public.expertises.index') }}" class="text-sm text-zinc-500 hover:text-zinc-900">← {{ __('Expertises') }}</a>
        <h1 class="mt-4 text-3xl font-bold tracking-tight">{{ $expertise->title }}</h1>
        @if($expertise->excerpt)
            <p class="mt-2 max-w-3xl text-zinc-600">{{ $expertise->excerpt }}</p>
        @endif
        @if($expertise->content)
            <div class="prose mt-6 max-w-none text-zinc-700">{!! nl2br(e($expertise->content)) !!}</div>
        @endif

        @if($expertise->services->isNotEmpty())
            <h2 class="mt-10 text-xl font-semibold">{{ __('Services associés') }}</h2>
            <div class="mt-4 grid gap-6 sm:grid-cols-2">
                @foreach($expertise->services as $service)
                    <a href="{{ route('public.services.show', $service->slug) }}" class="rounded-xl border border-zinc-200 p-6 hover:shadow-sm">
                        <h3 class="font-semibold">{{ $service->title }}</h3>
                        <p class="mt-2 text-sm text-zinc-600 line-clamp-2">{{ $service->excerpt }}</p>
                    </a>
                @endforeach
            </div>
        @endif

        @if($otherExpertises->isNotEmpty())
            <div class="mt-12 border-t border-zinc-200 pt-8">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-zinc-500">{{ __('Autres expertises') }}</h3>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($otherExpertises as $other)
                        <a href="{{ route('public.expertises.show', $other->slug) }}" class="rounded-full border border-zinc-200 px-3 py-1 text-sm hover:bg-zinc-50">{{ $other->title }}</a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.public>
