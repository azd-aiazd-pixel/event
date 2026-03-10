import './bootstrap';

// 1. Alpine.js
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
window.Alpine = Alpine;
Alpine.plugin(persist);
Alpine.start();

// 2. jQuery
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

// 3. Select2 (Nécessite jQuery)
import 'select2';
// CSS de Select2
import 'select2/dist/css/select2.min.css';

// 4. Chart.js
import Chart from 'chart.js/auto';
window.Chart = Chart;

// 5. Flatpickr
import flatpickr from "flatpickr";
import { French } from "flatpickr/dist/l10n/fr.js";
import 'flatpickr/dist/flatpickr.min.css';
window.flatpickr = flatpickr;
flatpickr.localize(French);