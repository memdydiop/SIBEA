<x-layouts.public :title="__('Demander un devis')">
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight">{{ __('Demander un devis') }}</h1>
        <p class="mt-2 text-zinc-600">{{ __('Remplissez le formulaire, notre équipe vous répond sous 48h. Champs * obligatoires.') }}</p>

        @if($errors->any())
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('public.quote.store') }}" class="mt-8 space-y-6">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-sm font-medium">{{ __('Prénom *') }}</label>
                    <input name="first_name" value="{{ old('first_name') }}" required class="mt-1 w-full rounded border border-zinc-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium">{{ __('Nom *') }}</label>
                    <input name="last_name" value="{{ old('last_name') }}" required class="mt-1 w-full rounded border border-zinc-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium">{{ __('Société') }}</label>
                    <input name="company" value="{{ old('company') }}" class="mt-1 w-full rounded border border-zinc-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium">{{ __('Fonction') }}</label>
                    <input name="role" value="{{ old('role') }}" class="mt-1 w-full rounded border border-zinc-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium">{{ __('Email *') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded border border-zinc-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium">{{ __('Téléphone *') }}</label>
                    <input name="phone" value="{{ old('phone') }}" required class="mt-1 w-full rounded border border-zinc-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium">{{ __('Localisation') }}</label>
                    <input name="location" value="{{ old('location') }}" class="mt-1 w-full rounded border border-zinc-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium">{{ __('Type de prestation *') }}</label>
                    <select name="service_type" required class="mt-1 w-full rounded border border-zinc-300 px-3 py-2 text-sm">
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
                    <label class="text-sm font-medium">{{ __('Nature du projet') }}</label>
                    <input name="project_nature" value="{{ old('project_nature') }}" class="mt-1 w-full rounded border border-zinc-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium">{{ __('Budget indicatif') }}</label>
                    <select name="estimated_budget" class="mt-1 w-full rounded border border-zinc-300 px-3 py-2 text-sm">
                        <option value="">{{ __('Choisir') }}</option>
                        <option value="< 10M" @selected(old('estimated_budget')=='< 10M')>&lt; 10M</option>
                        <option value="10-50M" @selected(old('estimated_budget')=='10-50M')>10-50M</option>
                        <option value="50-100M" @selected(old('estimated_budget')=='50-100M')>50-100M</option>
                        <option value="> 100M" @selected(old('estimated_budget')=='> 100M')>&gt; 100M</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-medium">{{ __('Délai souhaité') }}</label>
                    <select name="desired_timeline" class="mt-1 w-full rounded border border-zinc-300 px-3 py-2 text-sm">
                        <option value="">{{ __('Choisir') }}</option>
                        <option value="Immédiat" @selected(old('desired_timeline')=='Immédiat')>Immédiat</option>
                        <option value="3 mois" @selected(old('desired_timeline')=='3 mois')>3 mois</option>
                        <option value="6 mois" @selected(old('desired_timeline')=='6 mois')>6 mois</option>
                        <option value="1 an" @selected(old('desired_timeline')=='1 an')>1 an</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-medium">{{ __('Description du projet *') }}</label>
                    <textarea name="description" rows="5" required class="mt-1 w-full rounded border border-zinc-300 px-3 py-2 text-sm">{{ old('description') }}</textarea>
                </div>
            </div>

            <label class="flex items-start gap-2 text-sm">
                <input type="checkbox" name="consent" value="1" required class="mt-1">
                <span>{{ __('J’accepte que mes données soient utilisées pour traiter ma demande (RGPD). *') }}</span>
            </label>

            <button type="submit" class="inline-flex rounded bg-zinc-900 px-6 py-3 text-sm font-semibold text-white hover:bg-zinc-800">{{ __('Envoyer la demande') }}</button>
        </form>
    </div>
</x-layouts.public>
