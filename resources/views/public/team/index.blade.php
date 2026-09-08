<x-layouts.public :title="__('Notre équipe')">
 {{-- Hero CDC --}}
 <section class="relative overflow-hidden bg-primary-900 text-white">
 <div class="absolute inset-0">
 <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
 <div class="absolute inset-0 bg-primary-900/70"></div>
 <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px); background-size: 40px 40px;"></div>
 </div>
 <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 sm:py-16">
 <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-accent">{{ __('Équipe') }}</p>
 <h1 class="mt-3 font-display text-[32px] font-extrabold leading-tight sm:text-[40px]">{{ __('Notre équipe') }}</h1>
 <p class="mt-3 max-w-2xl text-[16px] leading-relaxed text-white/80">{{ __('Des professionnels qualifiés, organisés en directions, départements et équipes opérationnelles — mono-entreprise, pas de filiales.') }}</p>
 <div class="mt-6 flex flex-wrap gap-3">
 <a href="{{ route('public.quote.create') }}" class="inline-flex items-center rounded-sm bg-accent px-5 py-2.5 text-sm font-display font-semibold text-primary-900 hover:bg-accent-300 transition">{{ __('Nous confier un projet') }}</a>
 <a href="{{ route('public.contact') }}" class="inline-flex items-center rounded-sm border border-white/20 bg-transparent px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/10 transition">{{ __('Nous contacter') }}</a>
 </div>
 </div>
 </section>

 <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 sm:py-12">
 {{-- Stats --}}
 <div class="grid max-w-xl grid-cols-3 gap-4 rounded-xl border border-zinc-200 bg-white p-4 sm:p-5">
 <div class="text-center">
 <div class="font-display text-xl font-bold text-primary-900">{{ $employees->total() ?? $employees->count() }}</div>
 <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Collaborateurs') }}</div>
 </div>
 <div class="text-center border-x border-zinc-200">
 <div class="font-display text-xl font-bold text-primary-900">{{ $teams->count() }}</div>
 <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Équipes') }}</div>
 </div>
 <div class="text-center">
 <div class="font-display text-xl font-bold text-accent">SIBEA</div>
 <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Mono-entreprise') }}</div>
 </div>
 </div>

 <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
 @forelse($employees as $emp)
 <div class="flex flex-col overflow-hidden rounded-xl border border-border bg-surface shadow-[0_1px_2px_rgba(11,31,51,0.06)] hover:shadow-md transition">
 <div class="aspect-[4/3] bg-neutral-100 overflow-hidden relative flex items-center justify-center bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700">
 @if(!empty($emp->photo ?? $emp->avatar ?? null))
 <img src="{{ $emp->photo ?? $emp->avatar }}" alt="{{ $emp->full_name }}" class="h-full w-full object-cover">
 @else
 <span class="font-display text-4xl font-extrabold text-white/20">{{ Str::upper(Str::substr($emp->full_name, 0, 2)) }}</span>
 @endif
 <div class="absolute left-3 top-3">
 <span class="rounded-full bg-white/90 px-2 py-0.5 text-[11px] font-semibold text-primary-900 backdrop-blur">{{ $emp->department?->name ?? __('Équipe SIBEA') }}</span>
 </div>
 </div>
 <div class="flex flex-1 flex-col p-5">
 <div class="font-display text-[16px] font-semibold leading-tight text-primary-900">{{ $emp->full_name }}</div>
 <div class="text-sm text-zinc-600">{{ $emp->job_title }}</div>
 <div class="mt-2 flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wide text-neutral-500">
 <span>{{ $emp->registration_number }}</span>
 @if($emp->department)<span>· {{ $emp->department->name }}</span>@endif
 </div>
 </div>
 </div>
 @empty
 <div class="col-span-full rounded-xl border border-dashed border-zinc-300 bg-zinc-50 p-12 text-center">
 <p class="text-sm text-zinc-500">{{ __('Aucun membre d’équipe publié.') }}</p>
 </div>
 @endforelse
 </div>

 <div class="mt-8">{{ $employees->links() }}</div>

 @if($teams->isNotEmpty())
 <div class="mt-12">
 <div class="flex items-end justify-between gap-4">
 <div>
 <h2 class="font-display text-[22px] font-bold tracking-tight text-primary-900">{{ __('Nos équipes opérationnelles') }}</h2>
 <p class="mt-1 text-sm text-zinc-500">{{ __('Organisation Directions · Départements · Équipes — mono-entreprise sans filiales.') }}</p>
 </div>
 </div>
 <div class="mt-6 grid gap-4 sm:grid-cols-2">
 @foreach($teams as $team)
 <div class="rounded-xl border border-border bg-surface p-5 shadow-[0_1px_2px_rgba(11,31,51,0.06)] hover:shadow-md transition">
 <div class="flex items-start justify-between gap-3">
 <div class="font-display text-[16px] font-semibold text-primary-900">{{ $team->name }}</div>
 <span class="shrink-0 rounded-full bg-primary-50 px-2 py-0.5 text-[11px] font-semibold text-primary-700">{{ $team->code }}</span>
 </div>
 @if($team->description)
 <div class="mt-2 text-sm leading-relaxed text-zinc-600 line-clamp-2">{{ $team->description }}</div>
 @endif
 <div class="mt-3 flex items-center gap-2 text-xs text-zinc-500">
 <span class="inline-flex items-center gap-1 rounded-full bg-zinc-50 px-2.5 py-1 ring-1 ring-zinc-200">{{ __('Chef') }}: {{ $team->leader?->full_name ?? '—' }}</span>
 <span class="inline-flex items-center gap-1 rounded-full bg-accent/20 px-2.5 py-1 font-semibold text-primary-900">{{ $team->members->count() }} {{ __('membres') }}</span>
 </div>
 </div>
 @endforeach
 </div>
 </div>
 @endif
 </div>
</x-layouts.public>
