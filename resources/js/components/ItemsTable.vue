<template>
    <div class="items-wrapper">

        <div v-if="this.tax" class="items-table-header-with-tax">
            <div class="item-name">
                {{ $t("message.item") }}
            </div>
            <div class="item-description">
                {{ $t("message.description") }}
            </div>
            <div class="item-unit-price" style="white-space: nowrap; padding-left: 10px">
                {{ $t("message.unit_price") }}
            </div>
            <div class="item-quantity" style="padding-left: 10px">
                {{ $t("message.quantity") }}
            </div>
            <div v-if="this.tax" class="item-tax" style="padding-left: 20px;">
                {{ $t("message.tax") }}
            </div>
            <div class="item-amount" >
                {{ $t("message.amount") }}
            </div>
        </div>

        <div v-else class="items-table-header">

            <div class="item-name">
                {{ $t("message.item") }}
            </div>
            <div class="item-description">
                {{ $t("message.description") }}
            </div>
            <div class="item-unit-price" style="white-space: nowrap; padding-left: 10px">
                {{ $t("message.unit_price") }}
            </div>
            <div class="item-quantity" style="padding-left: 10px">
                {{ $t("message.quantity") }}
            </div>
            <div class="item-amount" >
                {{ $t("message.amount") }}
            </div>
        </div>

           <template v-for="(el, index) in items">
              <div class="d-flex flex-row">
                  <template>
                      <div v-if="showDelete">
                          <span class="remove-item-button" @click="removeItem(index)">&times;</span>
                      </div>
                      <div v-else>
                          <span class="empty-item-button">&times;</span>
                      </div>
                  </template>
                  <TableItem
                          :table-items="name"
                          :is-dirty="isDirty"
                          :show-tax="showTax"
                          :key="index"
                          :table-item="el"
                  ></TableItem>
              </div>
           </template>
            <template v-if="payment">
                <div class="show-payment-for-invoice">
                    <div class="show-payment-head">
                        Payment
                    </div>
                    <div class="show-payment-body">
                        {{ paymentValue }}
                    </div>
                </div>
            </template>
        <br>
        <button type="button" class="btn" @click="addNewLine()">New Line</button>

    </div>
</template>

<script>
    import TableItem from './TableItem.vue';
    import axios from 'axios'

    let id = 1;

    export default {
        data() {
            return {
                name: [],
            }
        },
        props: {
            items: {
                type: Array
            },
            isDirty: {
                required: true,
                type: Boolean
            },
            tax: {
                required: true,
                type: [Boolean, Number]
            },
            payment: {
                required: true,
                type: [Boolean, Number]
            },
            paymentValue: {
                required: true,
                type: [Number, String]
            }
        },
        computed: {
            showDelete() {
                return this.items.length > 1;
            },
            showTax() {
                return this.tax;
            }
        },
        methods: {
            addNewLine() {
                this.items.push(
                    {
                        //id: id++,
                        item: null,
                        description: null,
                        quantity: 1,
                        unitprice: 1,
                        itemtax: 0,
                        dirty: false
                    }
                )
            },

            removeItem(index) {
                this.items.splice(index, 1);
            },

            selectedItems(e) {
                let items = {
                    name: this.item,
                    desc: this.description,
                    unitPrice: this.unitprice,
                    count: this.quantity,
                    itemTax: this.itemtax,
                    total: this.total
                }
                this.$emit('change', items)
            }
        },
        components: {
            TableItem
        },
        mounted() {
            axios.get('/items/select-items').then(response => (this.name = response.data));

            eventBus.$on('touch', _ => {
              /*  this.$v.items.$touch()
                this.$v.$touch()*/
                this.items.forEach(el => {
                    el.dirty = true
                })
            })
            eventBus.$on('reset', _ => {
              /*  this.$v.$reset()*/
                this.items.forEach(el => {
                })
            })
        }
    }
</script>

<style scoped>
    button {
        width: 100px;
    }
</style>
