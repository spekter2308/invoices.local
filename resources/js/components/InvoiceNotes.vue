<template>
    <textarea name="invoice_notes" id="invoice_notes" cols="80" rows="6" v-text="notes"></textarea>
</template>

<script>
    export default {
        props: {
            notes: {
                type: String
            }
        },
    }
</script>

<style>

</style>