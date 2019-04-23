<template>
    <div>
        <h4>From</h4>
        <select class="custom-select" :value="value" @change="onSelect">
            <option value="" selected disabled>Choose company ...</option>
            <option v-for="company of companies" :value="company">{{ company.name }}</option>
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
        props: {
            companies: {
                type: Array,
                required: true
            },
            value: {
                type: Object,
                required: true
            }
        },

        computed: {
            address() {
                return this.value.address && this.value
                    ? this.value.address
                    : ''
            }
        },
        methods: {
            onSelect(e) {
                //this.$emit('input', e.target.value.invoice_notes);
                this.$emit('input', e.target.value);
            },
        }
    }
</script>
