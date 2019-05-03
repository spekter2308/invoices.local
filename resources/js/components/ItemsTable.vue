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

            <template v-for="(child, index) of children">
                <component :is="child" :key="child.index"></component>
            </template>

        <br>
        <button type="button" class="btn btn-primary" @click="addNewLine()">New Line</button>


    </div>
</template>

<script>
    import Item from './Item.vue';

    export default {
        data() {
            return {
                children: [Item],
            }
        },

        methods: {
            addNewLine() {
                this.children.push(Item);
            }
        }
    }
</script>

<style scoped>
    button {
        width: 100px;
    }
</style>