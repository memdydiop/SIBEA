<?php

use Livewire\Component;
use Livewire\Attributes\Title;

new #[Title('Appearance settings')] class extends Component {
 //
}; ?>

<section class="w-full">
 @include('partials.settings-heading')

 <flux:heading level="2" class="sr-only">{{ __('Appearance settings') }}</flux:heading>

  <x-pages::settings.layout :heading="__('Appearance')" :subheading="__('Light mode only — dark mode disabled')">
  <flux:callout variant="info" icon="information-circle">{{ __('Le mode sombre a été désactivé. L’application reste en thème clair.') }}</flux:callout>
  </x-pages::settings.layout>
</section>
