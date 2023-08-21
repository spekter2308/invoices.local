<template>
    <div>
        <div v-if="editing" v-on="listeners">
            <button @click="showOptions" class="btn btn-link">Hide Customization Options</button>
            <div class="options_table">
                <div>
                    <div class="currency">
                        <label>Currency</label>
                        <select v-model="settings.currency">
                            <option value="$">$ USD</option>
                            <option value="€">€ EURO</option>
                            <option value="£">£ POUND SIGN</option>
                        </select>
                    </div>
                    <div>
                        <b-form-checkbox
                                id="checkbox-1"
                                v-model="settings.payment"
                                name="checkbox-1"
                        >
                            Show Payment
                        </b-form-checkbox>
                    </div>
                </div>
                <div>
                    <div class="date_language">
                        <label>Date Format</label>
                        <select v-model="settings.format">
                            <option value="dd.MM.yyyy">DD.MM.YYYY</option>а
                            <option value="dd/MM/yyyy">DD/MM/YYYY</option>
                            <option value="MM/dd/yyyy">MM/DD/YYYY</option>
                            <option value="dd-MM-yyyy">DD-MM-YYYY</option>
                            <option value="yyyy-MM-dd">YYYY-MM-DD</option>
                        </select>
                    </div>
                    <div class="date_language">
                        <label>Language</label>
                        <select v-model="settings.language">
                            <option value="english">English</option>
                            <option value="spain">Spain</option>
                            <option value="germany">Germany</option>
                        </select>
                    </div>
                </div>
                <div>
                    <b-form-checkbox
                            id="checkbox-2"
                            v-model="settings.tax"
                            name="checkbox-2"
                    >
                        Show Tax Column
                    </b-form-checkbox>
                </div>
            </div>
        </div>
        <div v-else>
            <button @click="hideOptions" class="btn btn-link">Show Customization Options</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CustomizationOptions",
        props: {
            defaultOptions: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                editing: false,

                settings: {
                    payment: this.defaultOptions.show_payment ? true : false,
                    tax: this.defaultOptions.show_tax ? true : false,
                    format: this.defaultOptions.date_format || "dd.MM.yyyy",
                    language: this.defaultOptions.language || 'english',
                    currency: this.defaultOptions.currency || '$',
                },
            }
        },
        computed: {
            listeners() {
                return {
                    change: this.invoiceSettings
                }
            }
        },
        methods: {
            invoiceSettings() {
                console.log(this.settings);
                this.$emit('sendinvoicesettings', this.settings);
            },
            showOptions() {
                this.editing = false;
            },
            hideOptions() {
                this.editing = true;
            }
        }
    }
</script>

<style>
    .options_table {
        margin-top: 20px;
        display: flex;
        justify-content: space-around;
        align-items: center;
    }
    .date_language {
        margin: 15px 0;
        display: block;
    }
    .date_language label {
        display: block;
        margin: 0;
        padding: 0;
    }

</style>
