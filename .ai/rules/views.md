---
paths:
  - 'resources/views/**'
---

# Views

## Flux UI components exclusively
Build all UI with `<flux:*>` components (input, button, heading, modal, sidebar, navlist, menu, etc.). Do not introduce another UI library or hand-rolled components for these primitives.

## JSON string-key localization
Wrap all translatable strings as full-sentence JSON keys via __('Full sentence'). Do not use dotted lang keys.
