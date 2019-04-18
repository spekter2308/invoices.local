<template>
    <div class="form-group">
            <textarea name="company_address"
                      id="company_address"
                      class="form-control"
                      v-text="address" disabled>
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

<style scoped>

</style>