<template>
    <div>
        <div v-if="editing">
            <button @click="showOptions" class="btn btn-link">Hide Customization Options</button>
            <div class="options_table">
                <div>
                    <b-form-checkbox
                            id="checkbox-1"
                            v-model="status"
                            name="checkbox-1"
                            value="accepted"
                            unchecked-value="not_accepted"
                    >
                        I accept the terms and use
                    </b-form-checkbox>

                    <div>State: <strong>{{ status }}</strong></div>
                </div>
                <div>second column</div>
                <div>third column</div>
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
        data() {
            return {
                editing: false,
                status: 'not_accepted'
            }
        },
        methods: {
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
    }
</style>