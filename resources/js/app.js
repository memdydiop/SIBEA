

//import 'simplebar'; // or "
import SimpleBar from 'simplebar';
import 'simplebar/dist/simplebar.css';

// You will need a ResizeObserver polyfill for browsers that don't support it! (iOS Safari, Edge, ...)
import ResizeObserver from 'resize-observer-polyfill';
window.ResizeObserver = ResizeObserver;

import Alpine from 'alpinejs';

// Livewire 4 auto-injecte et démarre son propre Alpine sur les pages qui
// rendent des composants Livewire (admin). Ne démarrer notre copie que si
// aucune instance globale n'existe déjà, sinon double initialisation du DOM
// (« Detected multiple instances of Alpine running »). Les pages vitrines
// publiques sans composant Livewire utilisent cette copie.
if (! window.Alpine) {
    window.Alpine = Alpine;
    Alpine.start();
}