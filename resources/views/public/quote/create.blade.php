<x-layouts.public :title="__('Demander un devis')">
    {{-- Hero CDC --}}
    <section class="relative overflow-hidden bg-primary-900 text-white">
        <div class="absolute inset-0">
            <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
            <div class="absolute inset-0 bg-primary-900/70"></div>
            <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px); background-size: 40px 40px;"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 sm:py-16">
            <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-accent">{{ __('Devis gratuit') }}</p>
            <h1 class="mt-3 font-display text-[32px] font-extrabold leading-tight sm:text-[40px]">{{ __('Demander un devis') }}</h1>
            <p class="mt-3 max-w-2xl text-[16px] leading-relaxed text-white/80">{{ __('Remplissez le formulaire, notre équipe vous répond sous 48h. Étude technique mono-entreprise — champs * obligatoires.') }}</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('public.contact') }}" class="inline-flex items-center rounded-sm border border-white/20 bg-transparent px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/10 transition">{{ __('Nous contacter') }}</a>
                <a href="{{ route('public.projects.index') }}" class="inline-flex items-center rounded-sm bg-white/10 px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/20 transition">{{ __('Voir nos réalisations') }}</a>
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8 sm:py-12">
        {{-- Stats sobres --}}
        <div class="grid grid-cols-3 gap-4 rounded-xl border border-zinc-200 bg-white p-4 sm:p-5">
            <div class="text-center">
                <div class="font-display text-xl font-bold text-primary-900">48h</div>
                <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Réponse') }}</div>
            </div>
            <div class="text-center border-x border-zinc-200">
                <div class="font-display text-xl font-bold text-primary-900">{{ __('Gratuit') }}</div>
                <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Étude') }}</div>
            </div>
            <div class="text-center">
                <div class="font-display text-xl font-bold text-accent">SIBEA</div>
                <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('BTP') }}</div>
            </div>
        </div>

        @if($errors->any())
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <div class="font-semibold">{{ __('Veuillez corriger les erreurs :') }}</div>
                <ul class="mt-2 list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('public.quote.store') }}" class="mt-8 rounded-xl border border-border bg-surface p-6 shadow-[0_1px_2px_rgba(11,31,51,0.06)] sm:p-8">
            @csrf
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="text-sm font-medium text-zinc-700">{{ __('Prénom *') }}</label>
                    <input name="first_name" value="{{ old('first_name') }}" required class="mt-1.5 w-full rounded-lg border border-zinc-200 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none transition">
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-700">{{ __('Nom *') }}</label>
                    <input name="last_name" value="{{ old('last_name') }}" required class="mt-1.5 w-full rounded-lg border border-zinc-200 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none transition">
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-700">{{ __('Société') }}</label>
                    <input name="company" value="{{ old('company') }}" class="mt-1.5 w-full rounded-lg border border-zinc-200 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none transition">
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-700">{{ __('Fonction') }}</label>
                    <input name="role" value="{{ old('role') }}" class="mt-1.5 w-full rounded-lg border border-zinc-200 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none transition">
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-700">{{ __('Email *') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="mt-1.5 w-full rounded-lg border border-zinc-200 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none transition">
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-700">{{ __('Téléphone *') }}</label>
                    <input name="phone" value="{{ old('phone') }}" required class="mt-1.5 w-full rounded-lg border border-zinc-200 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none transition">
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-700">{{ __('Localisation') }}</label>
                    <input name="location" value="{{ old('location') }}" placeholder="{{ __('Ville / quartier') }}" class="mt-1.5 w-full rounded-lg border border-zinc-200 bg-white px-3.5 py-2.5 text-sm shadow-sm placeholder:text-zinc-400 focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none transition">
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-700">{{ __('Type de prestation *') }}</label>
                    <select name="service_type" required class="mt-1.5 w-full rounded-lg border border-zinc-200 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none transition">
                        <option value="">{{ __('Choisir') }}</option>
                        <option value="Bâtiment" @selected(old('service_type')=='Bâtiment')>Bâtiment</option>
                        <option value="Génie civil" @selected(old('service_type')=='Génie civil')>Génie civil</option>
                        <option value="VRD" @selected(old('service_type')=='VRD')>VRD</option>
                        <option value="Énergie" @selected(old('service_type')=='Énergie')>Énergie</option>
                        <option value="Second œuvre" @selected(old('service_type')=='Second œuvre')>Second œuvre</option>
                        <option value="Aménagement" @selected(old('service_type')=='Aménagement')>Aménagement</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-700">{{ __('Nature du projet') }}</label>
                    <input name="project_nature" value="{{ old('project_nature') }}" placeholder="{{ __('Ex. Construction villa R+1') }}" class="mt-1.5 w-full rounded-lg border border-zinc-200 bg-white px-3.5 py-2.5 text-sm shadow-sm placeholder:text-zinc-400 focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none transition">
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-700">{{ __('Budget indicatif') }}</label>
                    <select name="estimated_budget" class="mt-1.5 w-full rounded-lg border border-zinc-200 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none transition">
                        <option value="">{{ __('Choisir') }}</option>
                        <option value="< 10M" @selected(old('estimated_budget')=='< 10M')>&lt; 10M</option>
                        <option value="10-50M" @selected(old('estimated_budget')=='10-50M')>10-50M</option>
                        <option value="50-100M" @selected(old('estimated_budget')=='50-100M')>50-100M</option>
                        <option value="> 100M" @selected(old('estimated_budget')=='> 100M')>&gt; 100M</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-zinc-700">{{ __('Délai souhaité') }}</label>
                    <select name="desired_timeline" class="mt-1.5 w-full rounded-lg border border-zinc-200 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none transition">
                        <option value="">{{ __('Choisir') }}</option>
                        <option value="Immédiat" @selected(old('desired_timeline')=='Immédiat')>Immédiat</option>
                        <option value="3 mois" @selected(old('desired_timeline')=='3 mois')>3 mois</option>
                        <option value="6 mois" @selected(old('desired_timeline')=='6 mois')>6 mois</option>
                        <option value="1 an" @selected(old('desired_timeline')=='1 an')>1 an</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-zinc-700">{{ __('Description du projet *') }}</label>
                    <textarea name="description" rows="5" required placeholder="{{ __('Décrivez votre projet : surface, contraintes, attentes…') }}" class="mt-1.5 w-full rounded-lg border border-zinc-200 bg-white px-3.5 py-2.5 text-sm shadow-sm placeholder:text-zinc-400 focus:border-primary-300 focus:ring-2 focus:ring-primary-100 focus:outline-none transition">{{ old('description') }}</textarea>
                </div>
            </div>

            <label class="mt-6 flex items-start gap-3 rounded-lg bg-zinc-50 p-4 ring-1 ring-zinc-200">
                <input type="checkbox" name="consent" value="1" required class="mt-0.5 h-4 w-4 rounded border-zinc-300 text-primary-600 focus:ring-accent">
                <span class="text-sm leading-relaxed text-zinc-600">{{ __('J’accepte que mes données soient utilisées pour traiter ma demande (RGPD). *') }}</span>
            </label>

            <button type="submit" class="mt-6 inline-flex w-full justify-center rounded-sm bg-accent px-6 py-3 text-sm font-display font-semibold text-primary-900 hover:bg-accent-300 transition sm:w-auto">{{ __('Envoyer la demande →') }}</button>
            <p class="mt-3 text-xs text-zinc-500">{{ __('Réponse garantie sous 48h ouvrées.') }}</p>
        </form>
    </div>
</x-layouts.public>
