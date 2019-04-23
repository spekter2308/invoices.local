<template>
    <div>
        <h4>To</h4>
        <select name="customer_selected" class="custom-select" v-model="selected" @change="selectCustomer">
            <option selected value="">New Customer ...</option>
            <option v-for="customer of customers" v-bind:value="customer">{{ customer.name }}</option>
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
        props: ['customers'],

        data() {
            return {
                selected: '',
                address: '',
                editing: true,
            };
        },

        methods: {
            selectCustomer() {
                if(this.selected == '') {
                    this.address = '';
                    this.hide();
                } else {
                    this.address = this.selected.address;
                    this.show();
                }
            },
            hide() {
                this.editing = true;
            },
            show() {
                this.editing = false;
            }
        }
    }
</script>