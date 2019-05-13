<template>
    <div>
        <hr>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group row ">
                    <label for="time_frame" class="col-md-6 p-2">Time Frame</label>
                    <select @change="getDate" v-model="selected" class="form-control col-md-6" id="time_frame">
                        <option value="1">All time</option>
                        <option value="2">This Month</option>
                        <option value="3">Last Month</option>
                        <option value="4">This Year</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <div class="col-6">
                        <div class="row text-right">
                            <label for="time_frame" class="col-4 p-2">From</label>
                            <datapicker v-model="from" format="MM/dd/yyyy" class="col-8"></datapicker>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row text-right">
                            <label for="time_frame" class="col-4 p-2">To</label>
                            <datapicker v-model="to" format="MM/dd/yyyy" class="col-8"></datapicker>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-3 text-right">
                <a @click="filterShow" :href="href" class="btn btn-primary">Show</a>
            </div>

        </div>
    </div>
</template>

<script>

    export default {
        props: {
            uri: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                selected: 1,
                from: null,
                to: null,
                href: ''
            }
        },
        methods: {
            filterShow() {
                this.href = '?from=' + new Date(this.from).toJSON() + '&to=' + new Date(this.to).toJSON();
            },
            getDate() {
                axios.post(this.uri, {selected: this.selected}).then(response => {
                        this.from = response.data.min_date;
                        this.to = response.data.max_date;
                    }
                );
            }
        },
        beforeMount() {
            this.getDate();
        }

    }
</script>

<style scoped>

</style>