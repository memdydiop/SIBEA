<x-layouts.public :title="__('Programmes')">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight">{{ __('Programmes immobiliers') }}</h1>
        <p class="mt-2 text-zinc-600">{{ __('Découvrez nos programmes et lotissements — terrains et villas disponibles.') }}</p>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($programs as $program)
                <a href="{{ route('public.programs.show', $program->slug) }}" class="overflow-hidden rounded-xl border border-zinc-200 bg-white hover:shadow-sm">
                    @if($program->cover_path)
                        <img src="{{ $program->cover_path }}" alt="{{ $program->title }}" class="aspect-[4/3] w-full object-cover">
                    @else
                        <div class="aspect-[4/3] bg-zinc-100"></div>
                    @endif
                    <div class="p-4">
                        <div class="text-xs uppercase tracking-wide text-zinc-500">{{ $program->location ?? __('Localisation à préciser') }} @if($program->total_lots) · {{ $program->total_lots }} {{ __('lots') }} @endif</div>
                        <div class="mt-1 font-semibold">{{ $program->title }}</div>
                        @if($program->excerpt)
                            <div class="text-sm text-zinc-600 line-clamp-2 mt-1">{{ $program->excerpt }}</div>
                        @endif
                    </div>
                </a>
            @empty
                <p class="text-sm text-zinc-500">{{ __('Aucun programme disponible.') }}</p>
            @endforelse
        </div>

        <div class="mt-8">{{ $programs->links() }}</div>
    </div>
</x-layouts.public>
