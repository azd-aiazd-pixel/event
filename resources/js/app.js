import './bootstrap';


import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

window.Alpine = Alpine;
Alpine.plugin(persist);



import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

import select2 from 'select2';
select2();
import 'select2/dist/css/select2.min.css';

import Chart from 'chart.js/auto';
window.Chart = Chart;

import flatpickr from "flatpickr";
import { French } from "flatpickr/dist/l10n/fr.js";
import 'flatpickr/dist/flatpickr.min.css';
window.flatpickr = flatpickr;
flatpickr.localize(French);

Alpine.start();