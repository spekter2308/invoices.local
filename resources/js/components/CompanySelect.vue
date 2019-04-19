<template>
    <div>
        <h4>From</h4>
        <select class="custom-select" v-model="selected">
            <option selected value="null" disabled>Choose company ...</option>
            <option v-for="(company, index) of companies" :key="company.id">{{ company.name }}</option>
        </select>
        <br><br>
       <company-address :data="company"></company-address>
    </div>
</template>


<script>
    import CompanyAddress from './CompanyAddress'

    export default {
        components: {
            'company-address': CompanyAddress
        },

        data() {
            return {
                companies: [],
                selected: null,
            };
        },

        methods: {
            fetch(page){
                axios.get(this.url(page)).then(this.refresh);
            }
        }
    }
</script>
