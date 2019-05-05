<template>
    <div class="items-table-row" id="items-row" item-list>
        <div class="item-name">
            <select class="form-control" name="item-name[]" v-model="item">
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
                          v-model="description">
                </textarea>
            </div>
        </div>
        <div class="item-unit-price">
            <div class="form-group">
                <input type="number" class="form-control"
                       name="item-unit-price[]" placeholder="0.00"
                       v-model.number="unitprice">
            </div>
        </div>
        <div class="item-quantity">
            <div class="form-group">
                <input type="number" class="form-control"
                       name="item-quantity[]" placeholder="0.00"
                       v-model.number="quantity">
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
        name: "Item",

        props: {
            /*items: {
                type: Array,
                required: true
            },*/
            /*value: {
                type: String,
                required: true
            }*/
        },

        data() {
            return {
                item: '',
                description: '',
                unitprice: null,
                quantity: null
            }
        },

        computed: {
            total() {
                if (!this.unitprice || !this.quantity){
                    return 0;
                }
                return this.unitprice * this.quantity;
            }
        },

    }
</script>

<style scoped>

</style>