<template>
    <div class="items-wrapper">

        <div class="items-table-header">
            <div class="item-name">
                Item
            </div>
            <div class="item-description">
                Description
            </div>
            <div class="item-unit-price" style="white-space: nowrap">
                Unit Price
            </div>
            <div class="item-quantity">
                Quantity
            </div>
            <div class="item-amount">
                Amount
            </div>
        </div>

           <!-- <template v-for="(child, index) of children">
                <component :is="child" :key="child.index" @input="selectedItems"></component>
            </template>-->
           <template v-for="(i, index) in $v.items.$each.$iter">
               <TableItem  :key="index" :table-item="items[index]" :validation-item="i"></TableItem>
               <template v-if="i.$error">
                   <h4 v-if="!i.item.required" class="error">Item name is required</h4>
                   <h4 v-if="!i.quantity.required" class="error">quantity is required</h4>
                   <h4 v-if="!i.quantity.integer" class="error">quantity must be integer number</h4>
                   <h4 v-if="!i.unitprice.required" class="error">uniprice is required</h4>
                   <h4 v-if="!i.unitprice.float" class="error">uniprice must be floating number</h4>
               </template>
           </template>
        <br>
        <button type="button" class="btn btn-primary" @click="addNewLine()">New Line</button>


    </div>
</template>

<script>
    import { required, integer, numeric } from 'vuelidate/lib/validators'
    import TableItem from './TableItem.vue';

    let id = 1;

    export default {
        props: {
            bus: {
                type: Object,
                required: true
            },
            items: {
                type: Array
            }
        },
        watch: {
            '$v.$anyError': {
                handler(v) {
                    this.$emit('validation-status-changed', v)
                },
                immediate: true
            }
        },
        methods: {
            addNewLine() {
                this.items.push(
                    {
                        id: id++,
                        description: null,
                        item: null,
                        quantity: 1,
                        unitprice: 1
                    }
                )
            },

            selectedItems(e) {
                let items = {
                    name: this.item,
                    desc: this.description,
                    unitPrice: this.unitprice,
                    count: this.quantity,
                    total: this.total
                }
                this.$emit('change', items)
            }
        },
        components: {
            TableItem
        },
        validations: {
            items: {
                required,
                $each: {
                    item: {
                        required
                    },
                    quantity: {
                        required,
                        integer
                    },
                    unitprice: {
                        required,
                        float: v => /[+-]?([0-9]*[.])?[0-9]+/.test(v)
                    },
                    description: {

                    }
                }
            }
        },
        mounted() {
            this.bus.$on('touch', _ => {
                this.$v.$touch()
            })
            this.bus.$on('reset', _ => {
                this.$v.$reset()
            })
        }
    }
</script>

<style scoped>
    button {
        width: 100px;
    }
</style>