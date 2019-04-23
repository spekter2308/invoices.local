<template>
    <div>
        <h4>From</h4>
        <select class="custom-select" v-model="company" @change="selectCompany">
            <option selected :value="emptyObject" disabled>Choose company ...</option>
            <option v-for="company of companies" :value="company">{{ company.name }}</option>
        </select>
        <br><br>
        <div class="form-group">
            <textarea name="company_address"
                      id="company_address"
                      class="form-control"
                      v-text="address">
            </textarea>
        </div>
    </div>
</template>


<script>

    export default {
        props: {
            companies: {
                type: Array,
                required: true
            },
            value: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                company: this.value,
                address: '',
                emptyObject: new Object(),
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
                if(Object.keys(this.company).length !== 0){
                    this.address = this.company.address;
                } else {
                    this.address = '';
                }
                this.$emit('input', this.company);
            },
        }
    }
</script>
