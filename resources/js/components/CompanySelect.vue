<template>
    <div>
        <h4>From</h4>
        <div class="form-group">
            <select class="custom-select" :value="value" v-on="listeners">
                <option v-if="checkCurrentCompany" :value="checkCurrentCompany" selected>
                    {{ currentCompany.name }}
                </option>
                <option v-else selected :value="NaN" disabled>Choose company...</option>
                <option v-for="company of companies" :value="company.id">{{ company.name }}</option>
            </select>
        </div>
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
            currentCompany: {
                type: Object,
                required: true
            },
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
            checkCurrentCompany() {
                return Object.keys(this.currentCompany).length === 0 ? '' : this.currentCompany.id;
            },
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
