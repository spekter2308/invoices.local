<template>
    <div>
        <h4>To</h4>
        <select class="custom-select" v-model="selected" @change="onChange($event)">
            <option selected value="">New Customer ...</option>
            <option v-for="customer of customers" v-bind:value="customer.address">{{ customer.name }}</option>
        </select>
        <br><br>
        <div class="form-group" v-if="editing">
            <input name="customer_name" id="customer_name" class="form-control">
        </div>

        <customer-address :address="selected"></customer-address>

    </div>
</template>


<script>
    import CustomerAddress from './CustomerAddress'

    export default {
        components: {
            'customer-address': CustomerAddress
        },

        data() {
            return {
                customers: this.$attrs.customers,
                selected: '',
                editing: true
            };
        },

        methods: {
            onChange(event) {
                if(event.target.value == ''){
                    this.hide();
                } else {
                    this.show();
                }
            },
            hide() {
                this.editing = true
            },
            show() {
                this.editing = false
            }
        }
    }
</script>