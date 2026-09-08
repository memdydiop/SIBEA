<x-layouts.public :title="__('Demande envoyée')">
 {{-- Hero succès primary-900 --}}
 <section class="relative overflow-hidden bg-primary-900 text-white">
 <div class="absolute inset-0">
 <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
 <div class="absolute inset-0 bg-primary-900/70"></div>
 <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px); background-size: 40px 40px;"></div>
 </div>
 <div class="relative mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8 sm:py-20 text-center">
 <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-accent text-primary-900 text-xl font-bold shadow">✓</div>
 <p class="mt-6 font-display text-xs font-semibold uppercase tracking-[0.18em] text-accent">{{ __('Succès') }}</p>
 <h1 class="mt-3 font-display text-[32px] font-extrabold leading-tight sm:text-[40px]">{{ __('Demande envoyée !') }}</h1>
 <p class="mt-3 text-[16px] leading-relaxed text-white/80">{{ __('Merci :name, votre demande a bien été enregistrée.', ['name' => $quote->full_name]) }}</p>
 <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-sm font-medium text-white backdrop-blur">
 <span class="text-white/60">{{ __('Référence') }} :</span>
 <span class="font-mono font-semibold text-accent">{{ $quote->reference }}</span>
 </div>
 <p class="mt-4 text-sm text-white/60">{{ __('Notre équipe vous recontactera sous 48h à l’adresse :email.', ['email' => $quote->email]) }}</p>
 <div class="mt-8 flex flex-wrap justify-center gap-3">
 <a href="{{ route('home') }}" class="inline-flex items-center rounded-sm bg-accent px-6 py-3 text-sm font-display font-semibold text-primary-900 hover:bg-accent-300 transition">{{ __('Retour accueil') }}</a>
 <a href="{{ route('public.projects.index') }}" class="inline-flex items-center rounded-sm border border-white/20 bg-transparent px-6 py-3 text-sm font-semibold text-white hover:bg-white/10 transition">{{ __('Voir nos réalisations') }}</a>
 </div>
 </div>
 </section>

 <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
 <div class="rounded-xl border border-border bg-surface p-6 shadow-[0_1px_2px_rgba(11,31,51,0.06)]">
 <h2 class="font-display text-sm font-semibold text-primary-900">{{ __('Et ensuite ?') }}</h2>
 <ul class="mt-3 space-y-2 text-sm leading-relaxed text-zinc-600">
 <li class="flex gap-2"><span class="text-accent font-bold">1.</span> {{ __('Notre équipe analyse votre demande sous 24h.') }}</li>
 <li class="flex gap-2"><span class="text-accent font-bold">2.</span> {{ __('Un expert SIBEA vous recontacte pour affiner l’étude.') }}</li>
 <li class="flex gap-2"><span class="text-accent font-bold">3.</span> {{ __('Vous recevez un devis détaillé — mono-entreprise, sans intermédiaire.') }}</li>
 </ul>
 </div>
 <div class="mt-6 flex flex-wrap justify-center gap-3 text-sm">
 <a href="{{ route('public.expertises.index') }}" class="text-primary-700 hover:text-accent font-medium">{{ __('Découvrir nos expertises →') }}</a>
 <span class="text-zinc-300">·</span>
 <a href="{{ route('public.contact') }}" class="text-primary-700 hover:text-accent font-medium">{{ __('Nous contacter →') }}</a>
 </div>
 </div>
</x-layouts.public>
