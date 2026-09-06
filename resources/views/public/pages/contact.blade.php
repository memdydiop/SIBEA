<x-layouts.public :title="__('Contact')">
    {{-- Hero CDC --}}
    <section class="relative overflow-hidden bg-primary-900 text-white">
        <div class="absolute inset-0">
            <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
            <div class="absolute inset-0 bg-primary-900/70"></div>
            <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px); background-size: 40px 40px;"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 sm:py-16">
            <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-accent">{{ __('Contact') }}</p>
            <h1 class="mt-3 font-display text-[32px] font-extrabold leading-tight sm:text-[40px]">{{ __('Contactez-nous') }}</h1>
            <p class="mt-3 max-w-2xl text-[16px] leading-relaxed text-white/80">{{ __('Une question, un projet ? Notre équipe vous répond sous 48h — étude et devis détaillés.') }}</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('public.quote.create') }}" class="inline-flex items-center rounded-sm bg-accent px-5 py-2.5 text-sm font-display font-semibold text-primary-900 hover:bg-accent-300 transition">{{ __('Demander un devis') }}</a>
                <a href="tel:{{ $contactPhone }}" class="inline-flex items-center rounded-sm border border-white/20 bg-transparent px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/10 transition">{{ $contactPhone }}</a>
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8 sm:py-12">
        {{-- Stats sobres --}}
        <div class="grid grid-cols-3 gap-4 rounded-xl border border-zinc-200 bg-white p-4 sm:p-5 max-w-xl">
            <div class="text-center">
                <div class="font-display text-xl font-bold text-primary-900">48h</div>
                <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Réponse') }}</div>
            </div>
            <div class="text-center border-x border-zinc-200">
                <div class="font-display text-xl font-bold text-primary-900">BTP</div>
                <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Expertise') }}</div>
            </div>
            <div class="text-center">
                <div class="font-display text-xl font-bold text-accent">SIBEA</div>
                <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Territoire') }}</div>
            </div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            <div class="space-y-4">
                <div class="rounded-xl border border-border bg-surface p-6 shadow-[0_1px_2px_rgba(11,31,51,0.06)]">
                    <div class="font-display text-sm font-semibold text-primary-900">{{ __('Coordonnées') }}</div>
                    <div class="mt-3 space-y-2 text-sm leading-relaxed text-zinc-600">
                        <div class="flex items-start gap-2"><span class="text-zinc-400">📍</span><span>{{ $contactAddress }}</span></div>
                        <div class="flex items-start gap-2"><span class="text-zinc-400">✉️</span><a href="mailto:{{ $contactEmail }}" class="text-primary-700 hover:text-accent hover:underline">{{ $contactEmail }}</a></div>
                        <div class="flex items-start gap-2"><span class="text-zinc-400">📞</span><a href="tel:{{ $contactPhone }}" class="text-primary-700 hover:text-accent hover:underline">{{ $contactPhone }}</a></div>
                    </div>
                </div>
                <div class="rounded-xl bg-primary-900 p-6 text-white">
                    <div class="font-display text-sm font-semibold text-white">{{ __('Demander un devis') }}</div>
                    <p class="mt-2 text-sm leading-relaxed text-white/70">{{ __('Obtenez une étude détaillée sous 48h. Notre équipe BTP mono-entreprise vous accompagne de l’étude à la réception.') }}</p>
                    <a href="{{ route('public.quote.create') }}" class="mt-4 inline-flex items-center rounded-sm bg-accent px-4 py-2 text-sm font-display font-semibold text-primary-900 hover:bg-accent-300 transition">{{ __('Faire une demande') }}</a>
                </div>
            </div>
            <div class="rounded-xl border border-border bg-surface p-6 shadow-[0_1px_2px_rgba(11,31,51,0.06)]">
                <h3 class="font-display text-[16px] font-semibold text-primary-900">{{ __('Écrivez-nous') }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-zinc-600">{{ __('Pour un devis, utilisez le formulaire dédié — champs guidés, réponse garantie sous 48h.') }}</p>
                <div class="mt-6 rounded-lg bg-zinc-50 p-4 ring-1 ring-zinc-200">
                    <div class="text-xs font-semibold uppercase tracking-wide text-zinc-500">{{ __('Avantages') }}</div>
                    <ul class="mt-2 space-y-1.5 text-sm text-zinc-700">
                        <li class="flex gap-2"><span class="text-accent">✓</span> {{ __('Étude technique détaillée') }}</li>
                        <li class="flex gap-2"><span class="text-accent">✓</span> {{ __('Devis gratuit sous 48h') }}</li>
                        <li class="flex gap-2"><span class="text-accent">✓</span> {{ __('Accompagnement mono-entreprise') }}</li>
                    </ul>
                </div>
                <a href="{{ route('public.quote.create') }}" class="mt-6 inline-flex w-full justify-center rounded-sm bg-primary-900 px-4 py-3 text-sm font-display font-semibold text-white hover:bg-primary-800 transition">{{ __('Aller au formulaire devis →') }}</a>
                <p class="mt-3 text-center text-xs text-zinc-500">{{ __('RGPD : vos données sont utilisées uniquement pour traiter votre demande.') }}</p>
            </div>
        </div>
    </div>
</x-layouts.public>
