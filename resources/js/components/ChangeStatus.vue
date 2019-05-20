<template>
    <div>
        <li class="nav-item dropdown">
            <button @click="updateStatus" class="btn-dropdown nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" :href="url + 1">Paid</a>
                <a class="dropdown-item" :href="url + 2">Sent</a>
            </div>
        </li>

    </div>
</template>

<script>
    export default {
        data(){
            return{
                url: null
            }
        },
        methods: {
            updateStatus(){
                this.url = '/invoices/update/status?invoices=' + JSON.stringify(this.$store.state.checkbox) + '&status=';
            }
        },
        computed:{

        }
    }
</script>

<style scoped>
    li {
        list-style: none;
        height: 20px;
    }
    button {
        padding: 0 0 0 8px;
        margin: 0;
    }
    .dropdown-menu {
        min-width: 40px;
    }
</style>