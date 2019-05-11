<template>
    <div>
        <hr>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group row ">
                    <label for="time_frame" class="col-md-6 p-2">Time Frame</label>
                    <select class="form-control col-md-6" id="time_frame">
                        <option>All time</option>
                        <option>This Month</option>
                        <option>Last Month</option>
                        <option>This Quarter</option>
                        <option>Last Quarter</option>
                        <option>This Year</option>
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
        data(){
            return {
                date: new Date(),
                from:  new Date(),
                to: '',
                href: ''
            }
        },
        methods:{
            filterShow(){
                this.href = '?from=' + new Date(this.from).toJSON() + '&to=' + this.to.getTime();
            }
        }
    }
</script>

<style scoped>

</style>