

//import 'simplebar'; // or "
import SimpleBar from 'simplebar';
import 'simplebar/dist/simplebar.css';

// You will need a ResizeObserver polyfill for browsers that don't support it! (iOS Safari, Edge, ...)
import ResizeObserver from 'resize-observer-polyfill';
window.ResizeObserver = ResizeObserver;

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();