<template>
    <div v-if="isSuccess" class="success alert-success alert-flash" role="success" v-show="show">
        <strong>Success!</strong> {{ body }}
    </div>
    <div v-else class="alert alert-danger alert-flash" role="alert" v-show="show">
        <strong>Alert!</strong> {{ body }}
    </div>
</template>

<script>
    export default {
        props: ['message', 'showSuccess'],
        data() {
            return {
                body: '',
                show: false
            }
        },
        created() {
            if(this.message) {
                this.flash(this.message);
            } else if (this.showSuccess) {
                this.flash(this.showSuccess)
            }
            /*window.events.$on('flash', message => {
                this.flash(message);
            });*/
        },
        methods: {
            flash(message) {
                this.body = message;
                this.show = true;
                this.hide();
            },
            hide() {
                setTimeout(() => {
                    this.show = false;
                }, 4000);
            }
        },
        computed: {
            isSuccess() {
                return this.showSuccess ? true : false;
            }
        }
    }
</script>

<style>
    .alert-flash{
        position: fixed;
        right: 55px;
        bottom: 55px;
    }
</style>