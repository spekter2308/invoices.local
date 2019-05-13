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
                          :is-dirty="isDirty"
                          :key="index"
                          :table-item="el"
                  ></TableItem>
              </div>
                         </template>
        <br>
        <button type="button" class="btn" @click="addNewLine()">New Line</button>

    </div>
</template>

<script>
    import TableItem from './TableItem.vue';

    let id = 1;

    export default {
        props: {
            items: {
                type: Array
            },
            isDirty: {
                required: true,
                type: Boolean
            }
        },
        computed: {
            showDelete() {
                return this.items.length > 1;
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
                    total: this.total
                }
                this.$emit('change', items)
            }
        },
        components: {
            TableItem
        },
        /*validations: {
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
        },*/
        mounted() {
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