<template>
    <div class="items-table-row" id="items-row" item-list>
        <div class="item-name">
            <select-item></select-item>
        </div>
        <div class="item-description">
            <div class="form-group">
                <textarea class="form-control" rows="1" name="item-description[]"></textarea>
            </div>
        </div>
        <div class="item-unit-price">
            <div class="form-group">
                <input type="number" class="form-control" name="item-unit-price[]" placeholder="0.00">
            </div>
        </div>
        <div class="item-quantity">
            <div class="form-group">
                <input type="number" class="form-control" name="item-quantity[]" placeholder="0.00">
            </div>
        </div>
        <div class="item-total">
            <div class="form-group">
                <input type="number" class="form-control" name="item-total[]" placeholder="0.00" disabled>
            </div>
        </div>

    </div>

</template>

<script>
    export default {
        name: "Item"
    }
</script>

<style scoped>

</style>