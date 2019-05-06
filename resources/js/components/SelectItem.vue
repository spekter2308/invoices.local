<template>
    <div>
        <select class="form-control" name="item-name[]">
            <option v-for="item in info.data">{{item.name}}</option>
        </select>
    </div>

</template>

<script>
    import axios from 'axios'
    import {required, integer} from 'vuelidate/lib/validators'

    export default {
        data() {
            return {
                info:[]
            };
        },
        mounted() {
            axios
                .get('/invoices/get/select-item')
                .then(response => (this.info = response));
        }
    }
</script>

<style scoped>

</style>