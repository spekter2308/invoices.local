<template>
    <div class="dropdown">
        <span class="className" ref="someName"></span>

        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <ul class="action-list list-group list-group-flush">
                <li class="list-group-item show-status-list"><i class="fas fa-sync-alt"></i><a href="#">Change Status
                    to:<i class="fas icon-rigth fa-caret-right"></i></a>
                    <ul class="list-status list-group list-group-flush">
                        <li class="list-group-item"><a href="#">Paid</a></li>
                        <li class="list-group-item"><a href="#">Partial</a></li>
                        <li class="list-group-item"><a href="#">Sent</a></li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>
</template>

<script>
    export default {
        mounted() {

        }
    }
</script>

<style scoped>

</style>