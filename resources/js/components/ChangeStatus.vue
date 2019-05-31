<template>
    <div>
        <b-dropdown drop text="" variant="primary">
            <b-dropdown-item href="#">Paid</b-dropdown-item>
            <b-dropdown-item href="#">Sent</b-dropdown-item>
            <b-dropdown-item href="#">Archive</b-dropdown-item>

            <b-dropdown-divider></b-dropdown-divider>

            <b-dropdown-item-button>
                <strong>Delete</strong>
            </b-dropdown-item-button>
        </b-dropdown>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                selected: null
            }
        },
        methods: {
            updateStatus(){
                this.url = '/invoices/update/status?invoices=' + JSON.stringify(this.$store.state.checkbox) + '&status=';
            }
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