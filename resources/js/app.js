import './bootstrap';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import ApexCharts from 'apexcharts';

window.Alpine = Alpine;
window.ApexCharts = ApexCharts;

Alpine.plugin(focus);
Alpine.start();
