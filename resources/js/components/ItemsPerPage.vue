<template>
    <div>
        <b-dropdown drop text="" variant="primary" split-variant="outline-primary">
            <b-dropdown-item v-for="page in pages" v-bind:key="page" :value="page" @click="changeCount(page)">{{ page }}</b-dropdown-item>
        </b-dropdown>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        data() {
            return {
                pages: [10, 25, 50, 100],
            }
        },
        methods: {
            changeCount(page) {
                axios.get('/invoices',
                    {
                        params: {
                            product: this.product
                        }
                    }
                    ).then(response => console.log(response.data));
            }
        },

    }
</script>

<style scoped>

</style>