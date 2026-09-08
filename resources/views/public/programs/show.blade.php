<x-layouts.public :title="$program->meta_title ?? $program->title" :metaDescription="$program->meta_description ?? $program->excerpt">
    <div class="bg-background">
        {{-- Breadcrumb --}}
        <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
            <a href="{{ route('public.programs.index') }}" class="inline-flex items-center gap-1 text-sm text-zinc-500 hover:text-zinc-900"><span>←</span> {{ __('Programmes') }}</a>
        </div>

        {{-- Hero image 16/9 --}}
        @if($program->cover_path ?? $program->cover_image ?? null)
            <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="aspect-[16/9] overflow-hidden rounded-xl bg-zinc-100 sm:aspect-[2/1]">
                    <img src="{{ $program->cover_path ?? $program->cover_image }}" alt="{{ $program->title }}" class="h-full w-full object-cover">
                </div>
            </div>
        @endif

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 sm:py-10">
            {{-- Eyebrow --}}
            <div class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-wide text-primary-700">
                <span class="rounded-full bg-primary-50 px-2.5 py-1 text-primary-700">{{ __('Lotissement') }}</span>
                @if($program->city)<span class="text-zinc-500">{{ $program->city }}</span>@endif
                @if($program->municipality)<span class="text-zinc-400">·</span><span class="text-zinc-500">{{ $program->municipality }}</span>@endif
                @if($program->district)<span class="text-zinc-400">·</span><span class="text-zinc-500">{{ $program->district }}</span>@endif
            </div>

            <h1 class="mt-3 font-display text-[28px] font-bold tracking-tight text-primary-900 sm:text-[36px]">{{ $program->title }}</h1>

            @if($program->excerpt)
                <p class="mt-3 max-w-3xl text-[17px] leading-relaxed text-zinc-600">{{ $program->excerpt }}</p>
            @endif

            {{-- Stats sobres CDC 3 colonnes — harmonisé avec réalisations --}}
            <div class="mt-6 grid max-w-xl grid-cols-3 gap-4 rounded-xl border border-zinc-200 bg-white p-4 sm:p-5">
                <div class="text-center">
                    <div class="font-display text-xl font-bold text-primary-900">{{ $program->total_lots ?? '—' }}</div>
                    <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Lots') }}</div>
                </div>
                <div class="text-center border-x border-zinc-200">
                    <div class="font-display text-xl font-bold text-primary-900 truncate px-1">@if($program->total_area){{ number_format((float) $program->total_area, 0, ',', ' ') }} m²@else —@endif</div>
                    <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Surface') }}</div>
                </div>
                <div class="text-center">
                    <div class="font-display text-xl font-bold text-accent">{{ $lots->count() }}</div>
                    <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Disponibles') }}</div>
                </div>
            </div>

            @if($program->description)
                <div class="prose mt-6 max-w-none text-zinc-800">{!! nl2br(e($program->description)) !!}</div>
            @endif

            <div class="mt-4 flex flex-wrap gap-2 text-xs text-zinc-500">
                @if($program->total_lots)<span class="rounded-full bg-zinc-100 px-2.5 py-1">{{ $program->total_lots }} {{ __('lots') }}</span>@endif
                @if($program->published_at)<span class="rounded-full bg-zinc-100 px-2.5 py-1">{{ $program->published_at->format('d/m/Y') }}</span>@endif
            </div>

            <div class="mt-10">
                <h2 class="font-display text-[18px] font-semibold text-primary-900">{{ __('Lots du programme') }} <span class="text-sm font-normal text-zinc-500">· {{ $lots->count() }}</span></h2>
                <p class="mt-1 text-sm text-zinc-500">{{ __('Disponibilités en temps réel.') }}</p>

                <div class="mt-4 overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-[0_1px_2px_rgba(11,31,51,0.06)]">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-zinc-50 text-zinc-500">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium">{{ __('Référence') }}</th>
                                    <th class="px-4 py-3 text-right font-medium">{{ __('Surface') }}</th>
                                    <th class="px-4 py-3 text-right font-medium">{{ __('Prix') }}</th>
                                    <th class="px-4 py-3 text-center font-medium">{{ __('Statut') }}</th>
                                    <th class="px-4 py-3 text-center font-medium">{{ __('Viabilisé') }}</th>
                                    <th class="px-4 py-3 text-left font-medium">{{ __('Juridique') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200">
                                @forelse($lots as $lot)
                                    <tr class="hover:bg-zinc-50">
                                        <td class="px-4 py-3 font-mono text-xs font-medium">{{ $lot->reference }}</td>
                                        <td class="px-4 py-3 text-right">{{ $lot->surface !== null ? number_format((float) $lot->surface, 2, ',', ' ').' m²' : '—' }}</td>
                                        <td class="px-4 py-3 text-right">{{ $lot->price !== null ? number_format((float) $lot->price, 2, ',', ' ').' FCFA' : '—' }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @php $lotStatus = $lot->status instanceof \BackedEnum ? $lot->status->value : $lot->status; @endphp
                                            @if($lotStatus === 'disponible')<span class="rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 ring-1 ring-green-600/20">{{ __('disponible') }}</span>
                                            @elseif($lotStatus === 'reserve')<span class="rounded-full bg-orange-50 px-2 py-0.5 text-xs font-medium text-orange-800 ring-1 ring-orange-600/20">{{ __('réservé') }}</span>
                                            @elseif($lotStatus === 'vendu')<span class="rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-700 ring-1 ring-red-600/10">{{ __('vendu') }}</span>
                                            @else<span class="rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium">{{ $lotStatus }}</span>@endif
                                        </td>
                                        <td class="px-4 py-3 text-center text-xs">@if($lot->is_viabilise){{ __('Oui') }}@else{{ __('Non') }}@endif</td>
                                        <td class="px-4 py-3 text-xs">{{ $lot->juridical_status ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-4 py-8 text-center text-zinc-500">{{ __('Aucun lot.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($relatedPrograms->isNotEmpty())
                <div class="mt-12 border-t border-zinc-200 pt-8">
                    <div class="flex items-end justify-between">
                        <h3 class="font-display text-[18px] font-semibold text-primary-900">{{ __('Programmes similaires') }}</h3>
                        <a href="{{ route('public.programs.index') }}" class="hidden text-sm font-semibold text-primary-700 hover:text-accent sm:inline-flex">{{ __('Tout voir →') }}</a>
                    </div>
                    <div class="mt-6 grid gap-6 sm:grid-cols-3">
                        @foreach($relatedPrograms as $rel)
                            <a href="{{ route('public.programs.show', $rel->slug) }}" class="group flex flex-col overflow-hidden rounded-xl border border-border bg-surface shadow-[0_1px_2px_rgba(11,31,51,0.06)] hover:shadow-md transition">
                                <div class="aspect-[4/3] overflow-hidden bg-neutral-100 relative">
                                    @if($rel->cover_path)
                                        <img src="{{ $rel->cover_path }}" alt="{{ $rel->title }}" class="h-full w-full object-cover group-hover:scale-[1.03] transition duration-300">
                                    @else
                                        <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
                                    @endif
                                    <div class="absolute left-3 top-3 flex gap-1.5">
                                        @if($rel->city)<span class="rounded-full bg-white/90 px-2 py-0.5 text-[11px] font-semibold text-primary-900 backdrop-blur">{{ $rel->city }}</span>@endif
                                        @if($rel->total_lots)<span class="rounded-full bg-accent px-2 py-0.5 text-[11px] font-semibold text-primary-900">{{ $rel->total_lots }} {{ __('lots') }}</span>@endif
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="text-[11px] font-semibold uppercase tracking-wide text-neutral-500">{{ $rel->city ?? __('Lotissement') }} @if($rel->municipality)· {{ Str::limit($rel->municipality, 15) }}@endif</div>
                                    <div class="mt-1 font-display text-[15px] font-semibold text-primary-900 line-clamp-2 group-hover:text-accent transition">{{ $rel->title }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-10 flex flex-wrap gap-3">
                <a href="{{ route('public.quote.create') }}" class="inline-flex items-center justify-center rounded-sm bg-accent px-6 py-3 text-sm font-display font-semibold text-primary-900 hover:bg-accent-300 transition">{{ __('Réserver / Devis') }}</a>
                <a href="{{ route('public.contact') }}" class="inline-flex items-center justify-center rounded-sm border border-zinc-300 bg-white px-6 py-3 text-sm font-semibold text-zinc-700 hover:bg-zinc-50 transition">{{ __('Nous contacter') }}</a>
            </div>
        </div>
    </div>
</x-layouts.public>
