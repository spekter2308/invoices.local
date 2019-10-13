<template>
    <multiselect @close="updatePageCount" v-model="value" track-by="perPage" label="perPage" :options="options" :noOptions="value" :searchable="false" :allow-empty="false" :showLabels="false">
        <template slot-scope="{ option }"><strong>{{ option.perPage }}</strong></template>
    </multiselect>
</template>

<script>
    import axios from 'axios'
    import Multiselect from 'vue-multiselect'

    export default {
        components: {
            Multiselect
        },
        data () {
            return {
                value: { perPage: 100 },
                options: [
                    { perPage: 10 },
                    { perPage: 25 },
                    { perPage: 100 },
                    { perPage: 200 },
                    { perPage: 500 }
                ]
            }
        },
        methods: {
            updatePageCount() {
                axios.get('/invoices', { params: {perPage: this.value.perPage} }).then(response => {

                    }
                );
            }
        }
    }
</script>


<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>