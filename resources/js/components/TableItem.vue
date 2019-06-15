<template>

   <div id="items-row">
       <div v-if="this.showTax" class="items-table-row-with-tax" item-list>
           <div class="item-name">
               <div class="form-group-table">
                   <select class="form-control" v-model="tableItem.item"
                           @blur="makeDirty">
                       <option v-for="item in tableItems">{{item.name}}</option>
                   </select>
                   <template v-if="isDirty && tableItem.dirty">
                       <small class="error-control" v-if="!itemRequired">Item name is required</small>
                   </template>
               </div>
           </div>
           <div class="item-description">
               <div class="form-group-table">
                <textarea class="form-control"
                          rows="1" name="item-description[]"
                          @blur="makeDirty"
                          v-model="tableItem.description">
                </textarea>
               </div>
           </div>
           <div class="item-unit-price">
               <div class="form-group-table">
                   <input type="number" class="form-control"
                          name="item-unit-price[]" placeholder="1.0"
                          min="1"
                          step="1"
                          @keypress="checkForFloats"
                          @blur="makeDirty"
                          v-model.number="tableItem.unitprice">
                   <template v-if="isDirty && tableItem.dirty">
                       <small v-if="!unitPriceFloat" class="error-control">Uniprice must be floating number</small>
                       <small v-if="!unitPriceRequired" class="error-control">Uniprice is required</small>
                   </template>
               </div>
           </div>
           <div class="item-quantity">
               <div class="form-group-table">
                   <input type="number" class="form-control"
                          min="1"
                          step="1"
                          @keypress="checkForIntegers"
                          name="item-quantity[]" placeholder="1"
                          @blur="makeDirty"
                          v-model.number="tableItem.quantity">
                   <template v-if="isDirty && tableItem.dirty">
                       <small v-if="!quantityRequired" class="error-control">Quantity is required</small>
                       <small v-if="!quantityInteger" class="error-control">Quantity must be integer number</small>
                   </template>
               </div>
           </div>
           <div class="item-tax">
               <div class="form-group-table">
                   <input type="number" class="form-control"
                          name="item-tax[]" placeholder="1">
               </div>
           </div>
           <div class="item-total">
               <div class="form-group-table">
                   <input type="number" class="form-control"
                          name="item-total[]" placeholder="0.00" disabled
                          :value="total">
               </div>
           </div>
       </div>

       <div v-else class="items-table-row" item-list>
           <div class="item-name">
               <div class="form-group-table">
                   <select class="form-control" v-model="tableItem.item"
                           @blur="makeDirty">
                       <option v-for="item in tableItems">{{item.name}}</option>
                   </select>
                   <template v-if="isDirty && tableItem.dirty">
                       <small class="error-control" v-if="!itemRequired">Item name is required</small>
                   </template>
               </div>
           </div>
           <div class="item-description">
               <div class="form-group-table">
                <textarea class="form-control"
                          rows="1" name="item-description[]"
                          @blur="makeDirty"
                          v-model="tableItem.description">
                </textarea>
                </div>
           </div>
           <div class="item-unit-price">
               <div class="form-group-table">
                   <input type="number" class="form-control"
                          name="item-unit-price[]" placeholder="1.0"
                          min="1"
                          step="1"
                          @keypress="checkForFloats"
                          @blur="makeDirty"
                          v-model.number="tableItem.unitprice">
                   <template v-if="isDirty && tableItem.dirty">
                       <small v-if="!unitPriceFloat" class="error-control">Uniprice must be floating number</small>
                       <small v-if="!unitPriceRequired" class="error-control">Uniprice is required</small>
                   </template>
               </div>
           </div>
           <div class="item-quantity">
               <div class="form-group-table">
                   <input type="number" class="form-control"
                          min="1"
                          step="1"
                          @keypress="checkForIntegers"
                          name="item-quantity[]" placeholder="1"
                          @blur="makeDirty"
                          v-model.number="tableItem.quantity">
                   <template v-if="isDirty && tableItem.dirty">
                       <small v-if="!quantityRequired" class="error-control">Quantity is required</small>
                       <small v-if="!quantityInteger" class="error-control">Quantity must be integer number</small>
                   </template>
               </div>
           </div>
           <div class="item-total">
               <div class="form-group-table">
                   <input type="number" class="form-control"
                          name="item-total[]" placeholder="0.00" disabled
                          :value="total">
               </div>
           </div>
       </div>
   </div>
</template>

<script>
    import { required, integer } from 'vuelidate/lib/validators'

    export default {
        name: "TableItem",

        props: {
            isDirty: {
                required: true,
                type: Boolean
            },
            tableItem: {
                type: Object,
                required: true
            },
            tableItems: {
                type: Array,
                required: true
            },
            showTax: {
                type: Boolean,
                required: true
            }
        },
        mounted() {
            eventBus.$on('touch', () => {
                this.tableItem.dirty = true
            })
        },
        watch: {
            correct(v) {
                this.tableItem.correct = this.correct
            }
        },
        computed: {
            correct() {
              return this.itemRequired
                  && this.quantityInteger
                  && this.quantityRequired
                  && this.unitPriceRequired
                  && this.unitPriceFloat
            },
            itemRequired() {
                return required(this.tableItem.item)
            },
            quantityRequired() {
                return required(this.tableItem.quantity)
            },
            unitPriceRequired() {
                return required(this.tableItem.unitprice)
            },
            quantityInteger() {
                return integer(this.tableItem.quantity)
            },
            unitPriceFloat() {
                return /[+-]?([0-9]*[.])?[0-9]+/.test(this.tableItem.unitprice)
            },
            total() {
                if (!this.tableItem.unitprice || !this.tableItem.quantity){
                    return 0;
                }
                return this.tableItem.unitprice * this.tableItem.quantity;
            }
        },
        methods: {
            makeDirty() {
                this.tableItem.dirty = true
            },
            checkForFloats(e) {
                const val = `${this.tableItem.unitprice}${e.key}`
                if ( this.tableItem.unitprice !== null && !/[+-]?([0-9]*[.])?[0-9]+/.test(val)) {
                    e.preventDefault()
                }
            },
            checkForIntegers(e) {
                const val = `${this.tableItem.quantity}${e.key}`

                if (! /^[1-9][0-9]*$/.test(val)) {
                    e.preventDefault()
                }
            }
        }
    }
</script>

<style scoped>

</style>