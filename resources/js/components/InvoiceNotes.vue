<template>

    <div class="form-group">
        <label for="invoiceNotes">{{ $t("message.invoice_notes") }}</label>
        <textarea class="form-control" id="invoiceNotes" rows="5" v-text="notes"></textarea>
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