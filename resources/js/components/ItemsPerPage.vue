<template>
    <multiselect @close="updatePageCount" v-model="selectedPerPage" track-by="perPage" label="perPage" :options="options" :noOptions="selectedPerPage" :searchable="false" :allow-empty="false" :showLabels="false">
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
                selectedPerPage: { perPage: 100 },
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
                this.$emit('clicked', this.selectedPerPage);
            }
        }
    }
</script>


<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>