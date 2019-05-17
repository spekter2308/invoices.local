/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./main');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */


// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('flash', require('./components/Flash.vue').default);
Vue.component('company-select', require('./components/CompanySelect.vue').default);
Vue.component('customer-select', require('./components/CustomerSelect.vue').default);
Vue.component('invoice-notes', require('./components/InvoiceNotes.vue').default);
Vue.component('invoice-form', require('./components/InvoiceForm.vue').default);
Vue.component('items-table', require('./components/ItemsTable.vue').default);
Vue.component('datapicker', require('vuejs-datepicker').default);
Vue.component('invoices-filter', require('./components/Invoices-filter.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs. 
 */


/*import Vue from 'vue'*/
import Vuelidate from 'vuelidate'
import router from './routes'


Vue.use(Vuelidate)
Vue.use(router)

window.eventBus = new Vue()
const app = new Vue({
    el: '#app',
    router,
    data() {
        return {}
    }
});

