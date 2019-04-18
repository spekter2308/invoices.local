<template>
    <div>
        <h4>From</h4>
        <select class="custom-select" v-model="selected">
            <option selected value="null" disabled>Choose company ...</option>
            <option v-for="company of companies" v-bind:value="company.address">{{ company.name }}</option>
        </select>
        <br><br>
       <company-address :address="selected"></company-address>
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
                companies: this.$attrs.companies,
                selected: null,
            };
        },
    }
</script>
