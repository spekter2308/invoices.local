<template>

    <div class="form-group">
        <label for="invoiceNotes">{{ $t("message.invoice_notes") }}</label>
        <textarea class="form-control" id="invoiceNotes" rows="5" :value="notes" @input="updateNotes($event.target.value)"
        ></textarea>
    </div>

</template>

<script>
    import { required } from 'vuelidate/lib/validators'

    export default {
        props: {
            notes: {
                type: String
            }
        },
        data() {
            return {

            }
        },
        methods: {
            updateNotes(notes) {
                this.$emit('input', notes)
            }
        }
    }
</script>

<style>

</style>