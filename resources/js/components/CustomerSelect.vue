<template>
    <div>
        <h4>To</h4>
        <select class="custom-select"  v-model="customer" @change="selectCustomer">
            <option selected :value="emptyObj">New Customer ...</option>
            <option v-for="customer of customers" :value="customer" :key="customer.id">{{ customer.name
                }}</option>
        </select>
        <br><br>

        <div class="form-group" v-if="editing">
            <input class="form-control" v-model="name">
        </div>

        <div class="form-group" v-else>
            <textarea name=""
                      class="form-control"
                      v-text="address" disabled>
            </textarea>
        </div>

        <div class="form-group" v-if="editing">
            <textarea name="" class="form-control" v-model="enteredaddress">
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
                type: [Object, Number],
                required: true
            }
            /*newname: {
                type: String
            }*/
        },
        data() {
            return {
                customer: this.value,
                address: '',
                editing: true,
                emptyObj: new Object(),
                name: '',
                enteredaddress: '',
                newUser: {  }
            }
        },
        computed: {
            newname() {
                this.newUser.name = this.name;
            },
            newaddress() {
                this.newUser.address = this.enteredaddress;
            }
        },

        methods: {
            selectCustomer(e) {
                if(Object.keys(this.customer).length === 0){
                    this.editing = true;
                    this.$emit('input', this.newUser)
                } else {
                    this.editing = false;
                    this.address = this.customer.address;
                    this.$emit('input', this.customer.id)
                }
            }
        }
    }
</script>