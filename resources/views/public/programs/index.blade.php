<x-layouts.public :title="__('Programmes immobiliers')">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 sm:py-12">
        <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">{{ __('Lotissements') }}</p>
        <h1 class="mt-2 font-display text-[28px] font-bold tracking-tight text-primary-900 sm:text-[36px]">{{ __('Programmes immobiliers') }}</h1>
        <p class="mt-3 max-w-2xl text-[15px] leading-relaxed text-zinc-600">{{ __('Lotissements viabilisés — terrains et villas disponibles, titres fonciers sécurisés.') }}</p>

        @if($programs->isEmpty())
            <div class="mt-8 rounded-xl border border-dashed border-zinc-300 bg-zinc-50 p-12 text-center">
                <p class="text-sm text-zinc-500">{{ __('Aucun programme disponible pour le moment.') }}</p>
            </div>
        @else
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($programs as $program)
                    <a href="{{ route('public.programs.show', $program->slug) }}" class="group flex flex-col overflow-hidden rounded-xl border border-zinc-200 bg-white hover:shadow-sm transition">
                        <div class="aspect-[4/3] bg-zinc-100 overflow-hidden">
                            @if($program->cover_path)
                                <img src="{{ $program->cover_path }}" alt="{{ $program->title }}" class="h-full w-full object-cover group-hover:scale-[1.02] transition duration-300">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-zinc-100 text-xs font-semibold uppercase tracking-wide text-zinc-400">{{ $program->city ?? __('Lotissement') }}</div>
                            @endif
                        </div>
                        <div class="p-4">
                            <div class="text-xs uppercase tracking-wide text-zinc-500">
                                {{ $program->city ?? '—' }}
                                @if($program->municipality) · {{ $program->municipality }}@endif
                                @if($program->total_lots) · {{ $program->total_lots }} {{ __('lots') }}@endif
                            </div>
                            <div class="mt-1 font-semibold text-zinc-900 line-clamp-2 group-hover:text-primary-700">{{ $program->title }}</div>
                            @if($program->excerpt)
                                <div class="mt-1 line-clamp-2 text-sm text-zinc-600">{{ $program->excerpt }}</div>
                            @endif
                            <div class="mt-3 text-xs font-medium text-zinc-500">
                                @if($program->total_area){{ number_format((float)$program->total_area,0,',',' ') }} m² · @endif
                                @if($program->published_at){{ $program->published_at->format('d/m/Y') }}@endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-8">{{ $programs->links() }}</div>
        @endif
    </div>
</x-layouts.public>
