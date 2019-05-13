import Vue from 'vue'
import VueRouter from 'vue-router'
import Invoices from '../components/Invoices-filter.vue'

Vue.use(VueRouter)

export default new VueRouter({
    routes: [
        {
            path: '/invoices',
            name: 'invoices',
            component: Invoices ,
        },
    ],
    mode: 'history',

})