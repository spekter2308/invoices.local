import Vue from 'vue'
import VueRouter from 'vue-router'
import InvoiceIndex from '../components/InvoiceIndex.vue'

Vue.use(VueRouter)

const originalPush = VueRouter.prototype.push
VueRouter.prototype.push = function push(location, onResolve, onReject) {
    if (onResolve || onReject) return originalPush.call(this, location, onResolve, onReject)
    return originalPush.call(this, location).catch(err => err)
}

export default new VueRouter({
    routes: [
        {
            path: '/invoices', component: InvoiceIndex,
        },

    ],
    mode: 'history',
})