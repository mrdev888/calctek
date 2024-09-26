

require('./bootstrap');

window.Vue = require('vue').default;


Vue.component('calculator-component', require('./components/CalculatorComponent.vue').default);

Vue.component('pagination', require('laravel-vue-pagination'));

const app = new Vue({
    el: '#app',
});
