<x-layouts.public :title="$program->meta_title ?? $program->title" :metaDescription="$program->meta_description ?? $program->excerpt">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 sm:py-10">
        <a href="{{ route('public.programs.index') }}" class="text-sm text-zinc-500 hover:text-zinc-900">← {{ __('Programmes') }}</a>

        @if($program->cover_path ?? $program->cover_image)
            <div class="mt-4 aspect-[16/9] overflow-hidden rounded-xl bg-zinc-100 sm:aspect-[2/1]">
                <img src="{{ $program->cover_path ?? $program->cover_image }}" alt="{{ $program->title }}" class="h-full w-full object-cover">
            </div>
        @endif

        <p class="mt-6 text-xs font-semibold uppercase tracking-wide text-primary-700">{{ __('Lotissement') }}</p>
        <h1 class="mt-2 text-3xl font-bold tracking-tight text-zinc-900">{{ $program->title }}</h1>

        <div class="mt-2 flex flex-wrap gap-2 text-sm text-zinc-600">
            @if($program->city)<span>{{ $program->city }}</span>@endif
            @if($program->municipality)<span>· {{ $program->municipality }}</span>@endif
            @if($program->district)<span>· {{ $program->district }}</span>@endif
            @if($program->total_area)<span>· {{ number_format((float)$program->total_area,0,',',' ') }} m²</span>@endif
        </div>

        @if($program->excerpt)
            <p class="mt-4 max-w-3xl text-zinc-600">{{ $program->excerpt }}</p>
        @endif

        @if($program->description)
            <div class="prose mt-6 max-w-none text-zinc-800">{!! nl2br(e($program->description)) !!}</div>
        @endif

        <div class="mt-4 flex flex-wrap gap-2 text-xs text-zinc-500">
            @if($program->total_lots)<span class="rounded-full bg-zinc-100 px-2.5 py-1">{{ $program->total_lots }} {{ __('lots') }}</span>@endif
            @if($program->published_at)<span class="rounded-full bg-zinc-100 px-2.5 py-1">{{ $program->published_at->format('d/m/Y') }}</span>@endif
        </div>

        <div class="mt-10">
            <h2 class="text-lg font-semibold text-zinc-900">{{ __('Lots du programme') }} <span class="text-sm font-normal text-zinc-500">· {{ $lots->count() }}</span></h2>
            <p class="mt-1 text-sm text-zinc-500">{{ __('Disponibilités en temps réel.') }}</p>

            <div class="mt-4 overflow-hidden rounded-xl border border-zinc-200">
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
                                    <td class="px-4 py-3 text-right">{{ $lot->surface !== null ? number_format((float)$lot->surface, 2, ',', ' ').' m²' : '—' }}</td>
                                    <td class="px-4 py-3 text-right">{{ $lot->price !== null ? number_format((float)$lot->price, 2, ',', ' ').' FCFA' : '—' }}</td>
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

        <div class="mt-8 flex gap-3">
            <a href="{{ route('public.quote.create') }}" class="rounded bg-zinc-900 px-6 py-3 text-sm font-semibold text-white hover:bg-zinc-800">{{ __('Réserver / Devis') }}</a>
            <a href="{{ route('public.contact') }}" class="rounded border border-zinc-200 px-6 py-3 text-sm font-semibold hover:bg-zinc-50">{{ __('Contact') }}</a>
        </div>
    </div>
</x-layouts.public>
