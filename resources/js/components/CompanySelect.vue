<template>
    <div>
        <h4>From</h4>
        <select class="custom-select" :value="value" v-on="listeners">
            <option selected :value="NaN" disabled>Choose company ...</option>
            <option v-for="company of companies" :value="company.id">{{ company.name }}</option>
        </select>
        <br><br>
        <div class="form-group">
            <textarea name="company_address"
                      id="company_address"
                      class="form-control"
                      v-text="address" disabled>
            </textarea>
        </div>
    </div>
</template>


<script>
    export default {
        inheritProps: false,
        props: {
            companies: {
                type: Array,
                required: true
            },
            value: {
                type: Number,
                required: true
            }
        },
        data() {
            return {
                address: '',
            }
        },
        computed: {
            listeners() {
                return {
                    ...this.$listeners,
                    input: v => {
                        this.$emit('input', parseInt(v.target.value))
                    },
                    change: this.selectCompany
                }
            }
        },
        watch: {
          value(v) {
              if ( Number.isNaN(v) ) {
                  this.address = ''
              }
          }
        },
        methods: {
            selectCompany(e) {
                const id = e.target.value
                const company = this.companies.find(el => el.id === parseInt(id))
                this.address = company.address;
                this.$emit('sendinvoicenotes', company.invoice_notes);
            },
        }
    }
</script>
