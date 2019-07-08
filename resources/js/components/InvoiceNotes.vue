<template>

    <div class="form-group">
        <label for="invoiceNotes">{{ $t("message.invoice_notes") }}</label>
        <textarea class="form-control" id="invoiceNotes" rows="5" v-model="enterednotes"></textarea>
    </div>

</template>

<script>
    export default {
        props: {
            notes: {
                type: String
            }
        },

        data() {
            return {
                enterednotes: this.notes
            }
        },
        /*watch: {
            enterednotes(val) {
                const notes = {
                    invoiceNotes: this.enterednotes,
                }
                this.$emit('input', notes)
            },
        }*/
    }
</script>

<style>

</style>