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
Vue.component('invoice-index', require('./components/InvoiceIndex.vue').default);
Vue.component('invoice-show', require('./components/InvoiceShow.vue').default);
Vue.component('items-table', require('./components/ItemsTable.vue').default);
Vue.component('datapicker', require('vuejs-datepicker').default);
Vue.component('invoices-filter', require('./components/Invoices-filter.vue').default);
Vue.component('change-status', require('./components/ChangeStatus').default);
Vue.component('edit-item', require('./components/EditSelectItem').default);
Vue.component('payment-details', require('./components/PaymentDetails').default);
Vue.component('customization-options', require('./components/CustomizationOptions').default);
Vue.component('items-per-page', require('./components/ItemsPerPage').default);
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('invoice-notes', require('./components/AdditionalInvoiceNotes.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs. 
 */


/*import Vue from 'vue'*/
import Vuelidate from 'vuelidate'
import router from './routes'
import store from './store'
import BootstrapVue from 'bootstrap-vue'
import VueI18n from 'vue-i18n'
import VueMoment from 'vue-moment'

Vue.use(Vuelidate)
Vue.use(router)
Vue.use(BootstrapVue)
Vue.use(VueI18n)
Vue.use(VueMoment)

window.eventBus = new Vue()

const messages = {
    en: {
        message: {
            invoice: 'INVOICE',
            from: 'From',
            to: 'To',
            invoice_number: 'Invoice',
            invoice_date: 'Invoice Date',
            due_date: 'Due Date',
            item: 'Item',
            description: 'Description',
            unit_price: 'Unit Price',
            quantity: 'Quantity',
            tax: 'Tax',
            amount: 'Amount',
            invoice_notes: 'Invoice Notes',
            subtotal: 'Subtotal',
            total: 'Total',
            amount_paid: 'Amount Paid',
            balance_due: 'Balance Due'
        }
    },
    gr: {
        message: {
            invoice: 'RECHNUNG',
            from: 'Von',
            to: 'An',
            invoice_number: 'Rechnung',
            invoice_date: 'Datum',
            due_date: 'Zahlungsfrist',
            item: 'Artikel',
            description: 'Beschreibung',
            unit_price: 'Einzelpreis',
            quantity: 'Anzahl',
            tax: 'Steuer',
            amount: 'Betrag',
            invoice_notes: 'ANMERKUNGEN',
            subtotal: 'Zwischensumme',
            total: 'Summe',
            amount_paid: 'Betrag gezahlt',
            balance_due: 'Fälliger Betrag'
        }
    },
    sp: {
        message: {
            invoice: 'FACTURA',
            from: 'De',
            to: 'Para',
            invoice_number: 'Factura',
            invoice_date: 'Fecha',
            due_date: 'Fecha de Vencimiento',
            item: 'Artículo',
            description: 'Descripción',
            unit_price: 'Precio Unitario',
            quantity: 'Cantidad',
            tax: 'Impuesto',
            amount: 'Importe',
            invoice_notes: 'NOTAS',
            subtotal: 'Subtotal',
            total: 'Total',
            amount_paid: 'Monto Pagado',
            balance_due: 'Factura Total'
        }
    }
}

const i18n = new VueI18n({
    locale: 'en',
    messages,
})

const app = new Vue({
    i18n,
    el: '#app',
    router,
    store,
    data() {
        return {
            invoiceSettings: {},
        }
    },
    methods: {
        getInvoicesSettings(variable) {
            this.invoiceSettings = variable;
        }
    }
});
