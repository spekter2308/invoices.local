<template>
    <div>
        <b-dropdown drop text="" variant="primary" split-variant="outline-primary">
            <b-dropdown-item v-for="status in statuses" v-bind:key="status" :value="status" @click="changeStatus(status)">{{ status }}</b-dropdown-item>

            <b-dropdown-divider></b-dropdown-divider>

            <b-dropdown-item-button @click="deleteInvoice">
                <strong>Delete</strong>
            </b-dropdown-item-button>
        </b-dropdown>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        data() {
            return {
                statuses: ['Paid', 'Partial', 'Sent', 'Archive'],
            }
        },
        methods: {
            deleteInvoice() {
                //console.log(JSON.stringify(this.$store.state.checkbox));
                axios.post('/invoices/multiDelete', {parameters: this.$store.state.checkbox}).then(response => location.reload());
            },
            changeStatus(status) {
                //console.log(JSON.stringify(this.$store.state.checkbox));
                //console.log(JSON.stringify(status));
                axios.post('/invoices/multi-update/status',
                    {params: {
                        ids: this.$store.state.checkbox,
                        status: status}
                    }).then(response => location.reload());
            }
        },

    }
</script>

<style scoped>
    strong {
        color: #dc3545;
    }
</style>