<template>
    <div class="form-group">
            <textarea name="customer_address"
                      id="customer_address"
                      class="form-control"
                      v-text="address">
            </textarea>
    </div>
</template>

<script>
    export default {
        props: ['address'],


        data() {
            return {

            }
        }
    }
</script>

<style>

</style>