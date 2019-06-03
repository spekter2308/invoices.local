<template>
    <div>
        <b-dropdown drop text="" variant="primary" split-variant="outline-primary">
            <b-dropdown-item :href="url + 'Paid'">Paid</b-dropdown-item>
            <b-dropdown-item :href="url + 'Paid'">Sent</b-dropdown-item>
            <b-dropdown-item :href="url + 'Paid'">Archive</b-dropdown-item>

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
        data(){
            return{
                selected: null,
                status: null,
            }
        },
        methods: {
            deleteInvoice() {
                console.log(JSON.stringify(this.$store.state.checkbox));
                axios.post('/invoices/multiDelete', {parameters: this.$store.state.checkbox}).then(response => location.reload());
            },
            changeStatus() {
                console.log(JSON.stringify(this.$store.state.checkbox));
        },
        computed:{

        }
    }
</script>

<style scoped>
    strong {
        color: #dc3545;
    }
</style>