<template>
    <div>
        <h4>From</h4>
        <select name="company_selected" class="custom-select" v-model="selected" @change="selectCompany">
            <option value="" selected disabled>Choose company ...</option>
            <option v-for="company of companies" v-bind:value="company">{{ company.name }}</option>
        </select>
        <br><br>
        <div class="form-group">
            <textarea name="company_address"
                      id="company_address"
                      class="form-control"
                      v-text="address">
            </textarea>
        </div>
    </div>
</template>


<script>

    export default {
        props: ['companies'],

        data() {
            return {
                selected: '',
                address: '',
            };
        },

        methods: {
            selectCompany() {
                this.address = this.selected.address;
                //this.invoiceNotes = this.selected.invoice_notes;
                this.$emit('invoicenotes', this.selected.invoice_notes);
            },
        }
    }
</script>
