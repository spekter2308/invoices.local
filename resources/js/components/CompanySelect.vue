<template>
    <div>
        <h4>From</h4>
        <select class="custom-select" v-model="company" v-on="$listeners" @change="selectCompany">
            <option selected :value="NaN" disabled>Choose company ...</option>
            <option v-for="company of companies" :value="company">{{ company.name }}</option>
        </select>
        <br><br>
        <div class="form-group">
            <textarea name="company_address"
                      id="company_address"
                      class="form-control"
                      v-text="address" disabled>
            </textarea>
        </div>
    </div>
</template>


<script>
    export default {
        inheritProps: false,
        props: {
            companies: {
                type: Array,
                required: true
            },
            value: {
                type: Number,
                required: true
            }
        },
        data() {
            return {
                company: this.value,
                address: '',
            }
        },
        computed: {
            /*address() {
                return this.value.address && this.value
                    ? this.value.address
                    : ''
            }*/
        },
        created() {
            /*console.log(this.companies)*/
        },
        methods: {
            selectCompany(e) {
                this.address = this.company.address;
                this.$emit('input', this.company.id);
                this.$emit('sendinvoicenotes', this.company.invoice_notes);
            },
        }
    }
</script>
