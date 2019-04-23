<template>
    <div>
        <h4>To</h4>
        <select class="custom-select" :value="value" @change="selectCustomer">
            <option selected value="">New Customer ...</option>
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
        computed: {
            editing() {
                return this.value !== ''
            },
            address() {
                return this.editing && this.value
                    ? this.value.address
                    : ''
            }
        },
        methods: {
            selectCustomer(e) {
                this.$emit('input', e.target.value)
            }
        }
    }
</script>