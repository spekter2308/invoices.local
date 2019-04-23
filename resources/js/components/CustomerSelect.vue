<template>
    <div>
        <h4>To</h4>
        <select class="custom-select"  v-model="customer" @change="selectCustomer">
            <option selected :value="emptyObject">New Customer ...</option>
            <option v-for="customer of customers" :value="customer">{{ customer.name }}</option>
        </select>
        <br><br>
        <div class="form-group" v-if="editing">
            <input name="customer_name" id="customer_name" class="form-control">
        </div>

        <div class="form-group">
            <textarea name="customer_address"
                      id="customer_address"
                      class="form-control"
                      v-text="address">
            </textarea>
        </div>

    </div>
</template>
<script>
    export default {
        props: {
            customers: {
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
                customer: this.value,
                address: '',
                editing: true,
                emptyObject: new Object(),
            }
        },
        computed: {
            /*editing() {
                return this.value === ''
            },*/
            /*address() {
                return this.editing && this.value
                    ? this.value.address
                    : ''
            }*/
        },
        methods: {
            selectCustomer(e) {
                if(Object.keys(this.customer).length === 0){
                    this.editing = true;
                    this.address = '';
                } else {
                    this.editing = false;
                    this.address = this.customer.address;
                }
                this.$emit('input', this.customer)
            },
        }
    }
</script>