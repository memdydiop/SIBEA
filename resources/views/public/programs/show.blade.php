<x-layouts.public :title="$program->meta_title ?? $program->title" :metaDescription="$program->meta_description ?? $program->excerpt">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ route('public.programs.index') }}" class="text-sm text-zinc-500 hover:text-zinc-900">← {{ __('Programmes') }}</a>

        @if($program->cover_path)
            <img src="{{ $program->cover_path }}" alt="{{ $program->title }}" class="mt-6 aspect-[16/9] w-full rounded-xl object-cover">
        @endif

        <div class="mt-6 flex flex-wrap gap-2 text-xs uppercase tracking-wide text-zinc-500">
            @if($program->city)<span>{{ $program->city }}</span>@endif
            @if($program->municipality)<span>· {{ $program->municipality }}</span>@endif
            @if($program->district)<span>· {{ $program->district }}</span>@endif
            @if($program->total_area)<span>· {{ number_format((float)$program->total_area,2,',',' ') }} m²</span>@endif
            @if($program->total_lots)<span>· {{ $program->total_lots }} {{ __('lots au total') }}</span>@endif
            @if($program->published_at)<span>· {{ $program->published_at->format('d/m/Y') }}</span>@endif
        </div>

        <h1 class="mt-2 text-3xl font-bold tracking-tight">{{ $program->title }}</h1>

        @if($program->excerpt)
            <p class="mt-4 max-w-3xl text-lg text-zinc-600">{{ $program->excerpt }}</p>
        @endif

        @if($program->description)
            <div class="prose mt-6 max-w-none text-zinc-800">{!! nl2br(e($program->description)) !!}</div>
        @endif

        <div class="mt-10">
            <h2 class="text-xl font-semibold">{{ __('Lots du programme') }}</h2>
            <p class="mt-1 text-sm text-zinc-500">{{ __('Disponibilités en temps réel — contactez-nous pour réserver.') }}</p>

            <div class="mt-4 overflow-hidden rounded-xl border border-zinc-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-zinc-50 text-zinc-500">
                            <tr>
                                <th class="text-left px-4 py-3">{{ __('Référence') }}</th>
                                <th class="text-right px-4 py-3">{{ __('Surface') }}</th>
                                <th class="text-right px-4 py-3">{{ __('Prix') }}</th>
                                <th class="text-center px-4 py-3">{{ __('Statut') }}</th>
                                <th class="text-center px-4 py-3">{{ __('Viabilisé') }}</th>
                                <th class="text-left px-4 py-3">{{ __('Juridique') }}</th>
                                <th class="text-left px-4 py-3">{{ __('GPS') }}</th>
                                <th class="text-left px-4 py-3">{{ __('Plan') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200">
                            @forelse($lots as $lot)
                                <tr class="hover:bg-zinc-50">
                                    <td class="px-4 py-3 font-mono text-xs">{{ $lot->reference }}</td>
                                    <td class="px-4 py-3 text-right">{{ $lot->surface !== null ? number_format((float) $lot->surface, 2, ',', ' ').' m²' : '—' }}</td>
                                    <td class="px-4 py-3 text-right">{{ $lot->price !== null ? number_format((float) $lot->price, 2, ',', ' ').' FCFA' : '—' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @php $lotStatus = $lot->status instanceof \BackedEnum ? $lot->status->value : $lot->status; @endphp
                                        @if($lotStatus === 'disponible')
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">{{ $lot->status instanceof \BackedEnum ? $lot->status->label() : $lotStatus }}</span>
                                        @elseif($lotStatus === 'option')
                                            <span class="inline-flex items-center rounded-full bg-yellow-50 px-2 py-0.5 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">{{ $lot->status instanceof \BackedEnum ? $lot->status->label() : $lotStatus }}</span>
                                        @elseif($lotStatus === 'reserve')
                                            <span class="inline-flex items-center rounded-full bg-orange-50 px-2 py-0.5 text-xs font-medium text-orange-800 ring-1 ring-inset ring-orange-600/20">{{ $lot->status instanceof \BackedEnum ? $lot->status->label() : $lotStatus }}</span>
                                        @elseif($lotStatus === 'vendu')
                                            <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">{{ $lot->status instanceof \BackedEnum ? $lot->status->label() : $lotStatus }}</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-zinc-50 px-2 py-0.5 text-xs font-medium text-zinc-700">{{ $lotStatus }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center text-xs">@if($lot->is_viabilise)<span class="text-green-600">Oui</span>@else<span class="text-zinc-400">Non</span>@endif</td>
                                    <td class="px-4 py-3 text-xs">{{ $lot->juridical_status ?? '—' }}</td>
                                    <td class="px-4 py-3 text-xs font-mono">@if($lot->latitude && $lot->longitude){{ number_format((float)$lot->latitude,4) }},{{ number_format((float)$lot->longitude,4) }}@else — @endif</td>
                                    <td class="px-4 py-3 text-xs">@if($lot->plan_pdf_path)<a href="{{ $lot->plan_pdf_path }}" target="_blank" class="text-primary-600 underline">PDF</a>@else — @endif</td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="px-4 py-8 text-center text-zinc-500">{{ __('Aucun lot pour ce programme.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-8 flex gap-3">
            <a href="{{ route('public.quote.create') }}" class="inline-flex rounded bg-zinc-900 px-6 py-3 text-sm font-semibold text-white hover:bg-zinc-800">{{ __('Demander un devis / Réserver') }}</a>
            <a href="{{ route('public.contact') }}" class="inline-flex rounded border border-zinc-200 px-6 py-3 text-sm font-semibold hover:bg-zinc-50">{{ __('Contact') }}</a>
        </div>
    </div>
</x-layouts.public>
