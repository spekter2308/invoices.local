<template>
<div class="container" style="min-width: 1350px; max-width: 1250px;">
    <div class="row justify-content-center">
        <div class="col-md-12">


            <div class="card">
                <div class="card-header">
                    <div class="level">
                       <span class="flex">
                           <h1>List of Invoices</h1>
                           <button class="btn btn-link" @click="showFilter = !showFilter">Show filter</button>
                       </span>
                        <a href="/invoices/create" class="btn btn-primary">New Invoice</a>
                    </div>
                    <a style="float: right;" @click="clearFilters" class="btn btn-primary">Clear filters</a>
                    <!--filter by date part-->
                    <div v-show="showFilter">
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group row ">
                                    <label for="time_frame" class="col-md-6 p-2">Time Frame</label>
                                    <select @change="getDate" v-model="periodDate" class="form-control col-md-6" id="time_frame">
                                        <option value="1">All time</option>
                                        <option value="2">This Month</option>
                                        <option value="3">Last Month</option>
                                        <option value="4">This Year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-6">
                                        <div class="row text-right">
                                            <label for="time_frame" class="col-4 p-2">From</label>
                                            <datapicker v-model="dateFrom" format="MM/dd/yyyy" class="col-8"></datapicker>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row text-right">
                                            <label for="time_frame" class="col-4 p-2">To</label>
                                            <datapicker v-model="dateTo" format="MM/dd/yyyy" class="col-8"></datapicker>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-3 text-right">
                                <a @click="filterDateShow" class="btn btn-primary m-2 spinner" style="color: white;">Show</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="level">
                    <div class="flex">
                        <h5 class="mr-3">Status:</h5>
                        <a @click="getResultByStatus('All')" href="#" class="pd-1 border-right">All</a>
                        <a @click="getResultByStatus('Late')" href="#" class="pd-1 pdl-1 border-right">Late</a>
                        <a @click="getResultByStatus('Draft')" href="#" class="pd-1 pdl-1 border-right">Draft</a>
                        <a @click="getResultByStatus('Sent')" href="#" class="pd-1 pdl-1 border-right">Sent</a>
                        <a @click="getResultByStatus('Viewed')" href="#" class="pd-1 pdl-1 border-right">Viewed</a>
                        <a @click="getResultByStatus('Paid')" href="#" class="pd-1 pdl-1 border-right">Paid</a>
                        <a @click="getResultByStatus('Partial')" href="#" class="pd-1 pdl-1 border-right">Partial</a>
                        <a @click="getResultByStatus('Archive')" href="#" class="pd-1 pdl-1 border-right">Archived</a>
                    </div>

                    <autocomplete :search="search"
                                  placeholder="Search"
                                  aria-label="Search"
                                  auto-select
                                  @submit="searchSubmit"
                    ></autocomplete>
                </div>

                <table class="table">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>
                                <change-status></change-status>
                            </th>
                            <th class="click number" scope="col" @click="getResultsBySort('number')">
                                Invoices
                            </th>
                            <th class="click customer" scope="col" @click="getResultsBySort('customer')">Customer</th>
                            <th class="click company" scope="col" @click="getResultsBySort('company')">Company</th>
                            <th class="click invoice_date" scope="col" @click="getResultsBySort('invoice_date')">Date</th>
                            <th class="click diffdays" scope="col" @click="getResultsBySort('diffdays')">Days</th>
                            <th class="click subtotal" scope="col" @click="getResultsBySort('subtotal')">Total</th>
                            <th class="click balance" scope="col" @click="getResultsBySort('balance')">Balance</th>
                            <th scope="col">Status</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="Object.keys(invoices).length !== 0">
                        <template v-for="(invoice, index) in invoices.data">
                            <tr>
                                <td>
                                    <div class="form-check-invoice">
                                        <input class="form-check-input" name="action_several[]" :value="invoice.id" v-model="$store.state.checkbox" type="checkbox" :id="'check-' + invoice.id">
                                        <label :for="'check-' + invoice.id"></label>
                                    </div>
                                </td>
                                <td scope="row">
                                    <div class="dropdown">
                                        <button class="btn-select dropdown-toggle bg-white" type="button"
                                                id="dropdownMenuButton"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <ul class="action-list list-group list-group-flush">
                                                <li class="list-group-item"><i class="far fa-edit"></i><a
                                                        :href="'/invoices/' + invoice.id + '/edit'">Edit</a></li>
                                                <li class="list-group-item"><i class="far fa-copy"></i><a :href="'/invoices/duplicate/' + invoice.id">Duplicate</a></li>
                                                <li class="list-group-item"><i class="far fa-paper-plane"></i><a
                                                        :href="'/invoice-mail/create/' + invoice.id ">Send</a></li>
                                                <li class="list-group-item"><i class="far fa-money-bill-alt"></i><a :href="'/invoices/record-payment/' + invoice.id">RecordPayment</a>
                                                </li>
                                                <li class="list-group-item"><i class="far fa-file-pdf"></i><a
                                                        :href="'/invoices/pdf/' + invoice.id">Download</a> <a
                                                        target="_blank"
                                                        :href="'/invoices/pdf/' + invoice.id + '/print'">Print PDF</a></li>
                                                <li class="list-group-item show-status-list"><i class="fas fa-sync-alt"></i><a href="#">Change
                                                    Status to:<i class="fas icon-rigth fa-caret-right"></i></a>
                                                    <ul class="list-status list-group list-group-flush">
                                                        <li class="list-group-item"><a :href="'/invoices/mark-as-paid/' + invoice.id">Paid</a></li>
                                                        <li class="list-group-item"><a :href="'/invoices/unit-update/status/' + invoice.id + '?status=Draft'">Draft</a></li>
                                                        <li class="list-group-item"><a :href="'/invoices/unit-update/status/' + invoice.id + '?status=Partial'">Partial</a></li>
                                                        <li class="list-group-item"><a :href="'/invoices/unit-update/status/' + invoice.id + '?status=Sent'">Sent</a></li>
                                                    </ul>
                                                </li>
                                                <li class="list-group-item"><i class="far fa-file-archive"></i><a :href="'/invoices/unit-update/status/' + invoice.id + '?status=Archive'">Archive</a></li>
                                                <li class="list-group-item"><i class="far fa-trash-alt"></i><a :href="'/invoices/destroy/' + invoice.id">Delete</a></li>
                                            </ul>
                                        </div>
                                        <a :href="'/invoices/' + invoice.id" :id="`popover-1-${invoice.id}`">{{ invoice.number }}</a>
                                        <b-popover v-if="invoice.notes.length" :target="`popover-1-${invoice.id}`" triggers="hover" placement="top">
                                            <p v-for="note in invoice.notes">{{ note.notes }}</p>
                                        </b-popover>
                                    </div>
                                </td>
                                <td>
                                    <a @click=getResultsByCustomer(invoice.customer.id) href="#">{{ invoice.customer.name }}</a>
                                </td>
                                <td>
                                    <a @click="getResultByCompany(invoice.company.id)" href="#">
                                        <span v-if="invoice.company.short_name">{{ invoice.company.short_name }}</span>
                                        <span v-else>{{ invoice.company.name}}</span>
                                    </a>
                                </td>
                                <td>{{ $moment(invoice.invoice_date).format("DD-MM-YYYY") }}</td>
                                <td v-if="invoice.status != 'Paid'">
                                    <span v-if="$moment.utc(invoice.due_date).diff($moment.utc(), 'days') >= 0" style="color: green">
                                        {{ Math.round(Math.abs($moment.utc(invoice.due_date).diff($moment.utc(), 'hours') / 24)) }}
                                    </span>
                                    <span v-else style="color: red">
                                        {{ Math.round(Math.abs($moment.utc(invoice.due_date).diff($moment.utc(), 'hours') / 24)) }}
                                    </span>
                                </td>
                                <td v-else>{{ Math.round(Math.abs($moment.utc(invoice.due_date).diff($moment.utc(), 'hours') / 24)) }}</td>
                                <td v-if="invoice.settings.show_tax">{{ invoice.total }}</td>
                                <td v-else>{{ invoice.subtotal }}</td>
                                <td>{{ invoice.balance + ' ' + invoice.settings.currency }}</td>
                                <td>
                                    <span v-if="invoice.status == 'Paid'" style="color: green">{{ invoice.status }}</span>
                                    <span v-else>{{ invoice.status }}</span>
                                </td>
                                <td style="display: flex; flex-direction: row; justify-content: space-around; min-width: 190px;">
                                    <a v-if="invoice.status != 'Paid'" :href="'/invoices/mark-as-paid/' + invoice.id" class="btn btn-sm btn-success">Mark Paid</a>
                                    <a v-else :href="'/invoices/mark-as-unpaid/' + invoice.id" class="btn btn-sm btn-success">Mark Unpaid</a>
                                    <a :href="'/invoices/duplicate/' + invoice.id" class="btn btn-sm btn-primary">Duplicate</a>
                                </td>
                            </tr>
                        </template>

                            <tr>
                                <td colspan="1"></td>
                                <td colspan="3" align="center">
                                    <pagination :data="invoices" @pagination-change-page="getResults"></pagination>
                                </td>
                                <td colspan="2">
                                    <items-per-page style="max-width: 80px;"
                                        @clicked="getItemsPerPage"
                                    ></items-per-page>
                                </td>
                                <td>
                                    <div>{{ finance.allTotalUsd }}</div>
                                    <div>{{ finance.allTotalEuro }}</div>
                                    <div>{{ finance.allTotalPound }}</div>
                                </td>
                                <td>
                                    <div>{{ finance.allBalanceUsd }}$</div>
                                    <div>{{ finance.allBalanceEuro }}€</div>
                                    <div>{{ finance.allBalancePound }}£</div>
                                </td>
                                <td colspan="2"></td>
                            </tr>

                        </template>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</template>
<script>
    import axios from 'axios'
    import Autocomplete from '@trevoreyre/autocomplete-vue'

    export default {
        name: "InvoiceIndex",
        components: {
            Autocomplete
        },
        data() {
            return {
                spinnerVisible: false,
                periodDate: 1,
                dateFrom: null,
                dateTo: null,
                showFilter: false,
                invoices: {},
                filters: {
                    status: 'All'
                },
                finance: {},
                itemsPerPage: 100,
                orderBy: false,
                sortedHead: '',
                sortedHeadName: '',
                results: []
            }
        },
        beforeMount() {
            var dateFrom = this.$route.query.from,
                dateTo = this.$route.query.to;

            if (dateFrom && dateTo) {
                this.dateFrom = dateFrom;
                this.dateTo = dateTo;
            } else {
                this.getDate();
            }
        },
        beforeRouteUpdate (to, from, next) {
            if (to.query.order) {
                this.orderBy = JSON.parse(to.query.order);
            }
            if (this.sortedHead) {
                this.sortedHead.innerHTML = `${this.sortedHeadName}`;
            }
            this.sortedHead = this.$el.querySelector('.' + to.query.sortby);
            if (this.sortedHead) {
                this.sortedHeadName = this.sortedHead.innerText;
            }
            if (this.sortedHead === null) {
                this.sortedHead = this.$el.querySelector('.number');
                this.sortedHeadName = this.sortedHead.innerText;
            }
            if (this.orderBy && this.sortedHeadName) {
                this.sortedHead.innerHTML = `${this.sortedHeadName}  <i class="fa fa-caret-down" aria-hidden="true"></i>`;
            } else {
                this.sortedHead.innerHTML = `${this.sortedHeadName}  <i class="fa fa-caret-up" aria-hidden="true"></i>`;
            }

            this.filters = {};

            if(to.query.page) {
                this.filters.page = to.query.page;
            }
            if (to.query.per_page) {
                this.filters.per_page = to.query.per_page;
            }
            if (to.query.result) {
                this.filters.result = to.query.result;
            }
            if (to.query.byuser) {
                this.filters.byuser = to.query.byuser;
            }
            if (to.query.bycompany) {
                this.filters.bycompany = to.query.bycompany;
            }
            if (to.query.status) {
                this.filters.status = to.query.status;
            }
            if (to.query.sortby && to.query.order) {
                this.filters.sortby = to.query.sortby;
                this.filters.order = JSON.parse(to.query.order);
            }
            if (to.query.from) {
                this.filters.from = to.query.from;
                this.dateFrom = to.query.from;
            }
            if (to.query.to) {
                this.filters.to = to.query.to;
                this.dateTo = to.query.to;
            }

            axios.get('/api' + to.fullPath)
                .then(response => {
                    //this.$router.push({ path: 'invoices', query: { page: page} })
                    this.invoices = response.data.invoices;
                    this.finance = response.data.finance;
                    this.spinnerVisible = false
                    next();
                }).catch(error => {throw error});
        },
        mounted() {
            var page = this.getParameterByName('page');
            if (this.getParameterByName('per_page')) {
                this.filters.per_page = this.getParameterByName('per_page');
            }
            if (this.getParameterByName('result')) {
                this.filters.result = this.getParameterByName('result');
            }
            if (this.getParameterByName('byuser')) {
                this.filters.byuser = this.getParameterByName('byuser');
            }
            if (this.getParameterByName('bycompany')) {
                this.filters.bycompany = this.getParameterByName('bycompany');
            }
            if (this.getParameterByName('status')) {
                this.filters.status = this.getParameterByName('status');
            }
            if (this.getParameterByName('from')) {
                this.filters.from = this.getParameterByName('from');
                this.dateFrom = this.getParameterByName('from');
            }
            if (this.getParameterByName('to')) {
                this.filters.to = this.getParameterByName('to');
                this.dateTo = this.getParameterByName('to');
            }
            if (this.getParameterByName('sortby') && this.getParameterByName('order')) {
                this.filters.sortby = this.getParameterByName('sortby');
                this.filters.order = this.getParameterByName('order');
                this.orderBy = JSON.parse(this.getParameterByName('order'));
                this.filters.order = this.orderBy;

                this.sortedHead = this.$el.querySelector('.' + this.filters.sortby);
                this.sortedHeadName = this.sortedHead.innerText;
                if (this.orderBy && this.sortedHead != '') {
                    this.sortedHead.innerHTML = `${this.sortedHeadName}  <i class="fa fa-caret-down" aria-hidden="true"></i>`;
                } else {
                    this.sortedHead.innerHTML = `${this.sortedHeadName}  <i class="fa fa-caret-up" aria-hidden="true"></i>`;
                }
            }

            if (this.filters.sortby === undefined) {
                this.filters.sortby = 'number';
                this.orderBy = true;
                this.filters.order = this.orderBy;

                this.sortedHead = this.$el.querySelector('.number');
                this.sortedHeadName = this.sortedHead.innerText;
                if (this.orderBy && this.sortedHead != '') {
                    this.sortedHead.innerHTML = `${this.sortedHeadName}  <i class="fa fa-caret-down" aria-hidden="true"></i>`;
                } else {
                    this.sortedHead.innerHTML = `${this.sortedHeadName}  <i class="fa fa-caret-up" aria-hidden="true"></i>`;
                }
            }

            this.getResults(page);
            axios.get('/api' + this.$route.fullPath)
                .then(response => {
                    //this.$router.push({ path: 'invoices', query: { page: page} })
                    this.invoices = response.data.invoices;
                    this.finance = response.data.finance;
                    this.spinnerVisible = false
                }).catch(error => {throw error});
        },
        methods: {
            search(input) {
                return new Promise(resolve => {
                    if (input.length < 3) {
                        return resolve([])
                    }
                    axios.get('/api/invoices/search?search=' + input).then(response => response.data).then(data => {resolve(data)

                       /* .filter(param => {
                            return param.toLowerCase().startsWith(input.toLowerCase())
                        }))*/
                    })
                })
            },
            searchSubmit(result) {
                var page = 1;
                this.filters.result = result;
                delete this.filters.byuser;
                delete this.filters.bycompany;

                this.getResults(page);
            },
            getItemsPerPage(variable) {
                this.itemsPerPage = variable.perPage;
                this.filters.per_page = this.itemsPerPage;

                this.getResults();
            },
            getDate() {
                axios.post('/invoices/get/date', {periodDate: this.periodDate}).then(response => {
                        this.dateFrom = response.data.min_date;
                        this.dateTo = response.data.max_date;
                    }
                );
            },
            getParameterByName(name, url) {
                if (!url) url = window.location.href;
                name = name.replace(/[\[\]]/g, '\\$&');
                var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                    results = regex.exec(url);
                if (!results) return null;
                if (!results[2]) return '';
                return decodeURIComponent(results[2].replace(/\+/g, ' '));
            },
            getResultsByCustomer(id) {
                event.preventDefault();
                this.filters.byuser = id;
                this.getResults();
            },
            getResultByCompany(id) {
                event.preventDefault();
                this.filters.bycompany = id;
                this.getResults();
            },
            getResultByStatus(status) {
                event.preventDefault();
                this.filters.status = status;
                this.getResults();
            },
            getResultsBySort(sortParam) {
                event.preventDefault();
                if (this.sortedHead != '') {
                    this.sortedHead.innerHTML = `${this.sortedHeadName}`;
                }
                this.filters.sortby = sortParam;
                this.orderBy = !this.orderBy;
                this.filters.order = this.orderBy;
                this.sortedHead = this.$el.querySelector(`.${sortParam}`);
                this.sortedHeadName = this.sortedHead.innerText;
                /*if (this.orderBy && this.sortedHead != '') {
                    this.sortedHead.innerHTML = `${this.sortedHeadName}  <i class="fa fa-caret-down" aria-hidden="true"></i>`;
                } else {
                    this.sortedHead.innerHTML = `${this.sortedHeadName}  <i class="fa fa-caret-up" aria-hidden="true"></i>`;
                }*/
                this.getResults();
            },
            filterDateShow() {
                try {
                    this.spinnerVisible = true
                    this.filters.from = new Date(this.dateFrom).toJSON();
                    this.filters.to = new Date(this.dateTo).toJSON();
                    this.getResults();
                } catch (e) {
                    this.spinnerVisible = false
                }
            },
            clearFilters() {
                event.preventDefault();
                if (this.sortedHead != '') {
                    this.sortedHead.innerHTML = `${this.sortedHeadName}`;
                }
                this.filters = {};
                this.getResults();
            },
            getResults(page = 1) {
                if (page =='' || page === null || page === undefined) {
                    page = 1;
                }
                this.filters.page = page;
                this.$router.push({path: 'invoices', query: Object.assign({}, this.filters) });
                //this.$router.push({path: 'invoices', params: Object.assign({}, this.filters), page: page });

                //axios.get('/api/invoices?' + this.url + '&page=' + page)
               /*axios.get('/api' + this.$route.fullPath)
                    .then(response => {
                        //this.$router.push({ path: 'invoices', query: { page: page} })
                        this.invoices = response.data.invoices;
                        this.finance = response.data.finance;
                        this.spinnerVisible = false
                    }).catch(error => {throw error});*/
            },
        },
        watch: {
            spinnerVisible(v) {
                if (v === true) {
                    this.getShowButton.innerHTML = `Show <div disabled="true" class="spinner-border spinner-border-sm" role="status" aria-hidden="true">`
                } else {
                    this.getShowButton.innerHTML = `Show <div class="" role="status" aria-hidden="true">`
                }
            },
        },
        computed: {
            getShowButton() {
                return document.querySelector('.spinner');
            }
        }
    }
</script>


<style scoped>
 .click:hover {
     cursor: pointer;
 }

 .table tbody tr:first-child td {
     border-top: none;
 }
</style>
