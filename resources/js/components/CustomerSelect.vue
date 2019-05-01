<template>
    <div>
        <h4>To</h4>
        <select class="custom-select"  v-model="customer" @change="toggleActive">
            <option selected :value="emptyObj">New Customer ...</option>
            <option v-for="customer of customers" :value="customer" :key="customer.id">{{ customer.name
                }}</option>
        </select>
        <br><br>

        <div class="form-group" v-if="editing">
            <input class="form-control" v-model="enteredname">
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
                customer: {},
                address: '',
                editing: true,
                emptyObj: {},
                enteredname: '',
                enteredaddress: '',
                newUser: {  }
            }
        },
        watch: {
            enteredname(val) {
                const user = {
                    name: this.enteredname,
                    address: this.enteredaddress,
                }
                this.$emit('input', user)
            },
            enteredaddress(val) {
                const user = {
                    name: this.enteredname,
                    address: this.enteredaddress,
                }
                this.$emit('input', user)
            },
            customer: {
                handler:function (val) {
                    if (this.editing) {
                       // this.editing = true;
                        const user = {
                            name: this.enteredname,
                            address: this.enteredaddress,
                        }
                        this.$emit('input', user)
                    } else {
                        //this.editing = false;
                        this.address = this.customer.address;
                        this.$emit('input', this.customer.id)
                    }
                },
                //immediate: true
            }
        },
        computed: {
          isEmpty() {
              return Object.keys(this.customer).length === 0
          }
        },
        methods: {
            toggleActive(v) {
                if (this.isEmpty) {
                    this.editing = true
                } else {
                    this.editing = false
                }
            },
            selectCustomer(e) {
                if(this.isEmpty ){
                    this.editing = true;
                    const user = {
                        name: this.enteredname,
                        address: this.enteredaddress,
                    }
                    this.$emit('input', user)
                } else {
                    this.editing = false;
                    this.address = this.customer.address;
                    this.$emit('input', this.customer.id)
                }
            }
        }
    }
</script>