<template>
    <div class="items-table-row" id="items-row" item-list>
        <div class="item-name">
            <select class="form-control" name="item-name[]" v-model="tableItem.item"
                    @blur="validationItem.item.$touch()">
                <option></option>
                <option>Days</option>
                <option>Hours</option>
                <option>Product</option>
                <option>Service</option>
                <option>Expense</option>
                <option>Discount</option>
            </select>
        </div>
        <div class="item-description">
            <div class="form-group">
                <textarea class="form-control"
                          rows="1" name="item-description[]"
                          v-model="tableItem.description">
                </textarea>
            </div>
        </div>
        <div class="item-unit-price">
            <div class="form-group">
                <input type="number" class="form-control"
                       name="item-unit-price[]" placeholder="1.0"
                       min="1"
                       step="1"
                       @keypress="checkForFloats"
                       @blur="validationItem.unitprice.$touch()"
                       v-model.number="tableItem.unitprice">
            </div>
        </div>
        <div class="item-quantity">
            <div class="form-group">
                <input type="number" class="form-control"
                       min="1"
                       step="1"
                       @keypress="checkForIntegers"
                       name="item-quantity[]" placeholder="1"
                       @blur="validationItem.quantity.$touch()"
                       v-model.number="tableItem.quantity">
            </div>
        </div>
        <div class="item-total">
            <div class="form-group">
                <input type="number" class="form-control"
                       name="item-total[]" placeholder="0.00" disabled
                       :value="total">
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "TableItem",
        props: {
            tableItem: {
                type: Object,
                required: true
            },
            validationItem: {
                type: Object,
                required: true
            }
        },
        computed: {
            total() {
                if (!this.tableItem.unitprice || !this.tableItem.quantity){
                    return 0;
                }
                return this.tableItem.unitprice * this.tableItem.quantity;
            }
        },
        methods: {
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