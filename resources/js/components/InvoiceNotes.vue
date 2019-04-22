<template>
    <div>
        <h5>Invoice Notes</h5>
        <textarea name="invoiceNotes" id="" cols="80" rows="8" v-text="notes"></textarea>
    </div>
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