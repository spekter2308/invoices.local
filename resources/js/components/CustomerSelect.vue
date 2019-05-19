<template>
    <div>
        <h4>To</h4>
        <div class="form-group">
            <select class="custom-select"  v-model="customer" @change="toggleActive">
                <option selected :value="emptyObj">New Customer ...</option>
                <option v-for="customer of customers"
                        :value="customer"
                        :key="customer.id">{{ customer.name }}</option>
            </select>
        </div>
        <div class="form-group" v-if="editing">
            <input class="form-control" v-model="enteredname" @blur="$v.enteredname.$touch()">
            <template v-if="$v.enteredname.$error">
                <small class="error-control" v-if="!$v.enteredname.required"
                >Please type name</small>
            </template>
        </div>

        <div class="form-group" v-else>
            <textarea name=""
                      class="form-control"
                      v-text="address" disabled>
            </textarea>
        </div>

        <div class="form-group" v-if="editing">
            <textarea class="form-control" v-model="enteredaddress" @blur="$v.enteredaddress.$touch()">
            </textarea>
            <template v-if="$v.enteredaddress.$error">
                <small class="error-control" v-if="!$v.enteredaddress.required"
                >Please type address</small>
            </template>
        </div>

    </div>
</template>
<script>
    import { requiredIf, required } from 'vuelidate/lib/validators'
    export default {
        inheritAttrs: false,
        props: {
            currentCustomer: {
                type: Object,
                required: true,
            },
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
                customer: this.currentCustomer || {},
                address: this.currentCustomer.address || '',
                editing: this.isEditing(),
                emptyObj: {},
                enteredname: null,
                enteredaddress: null,
                newUser: {}
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
                handler: function (val) {
                    if (this.editing) {
                        // this.editing = true;
                        const user = {
                            name: this.enteredname,
                            address: this.enteredaddress,
                        }
                        this.$emit('input', user)
                    } else {
                        //const customer = this.customers.find(el => el.id === parseInt(val))
                        this.address = this.customer.address
                        this.$emit('input', this.customer.id)
                    }
                },
                //immediate: true
            }
        },
        computed: {
            isEmpty() {
                return Object.keys(this.customer).length === 0
            },

        },
        validations: {
            enteredname: {
                required
            },
            enteredaddress: {
                /*        required: requiredIf(function() {
                            return this.isEmpty
                        })*/
            required
            }
        },
        mounted() {
            eventBus.$on('update', _ => {
                this.editing = true;
                this.customer = {}
            })
        },
        methods: {
            toggleActive(v) {
                if (this.isEmpty) {
                    this.editing = true
                } else {
                    this.editing = false
                }
            },
            isEditing() {
                return this.currentCustomer.id ? false : true;
            }
        }
    }
</script>
