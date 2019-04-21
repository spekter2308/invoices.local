<template>
    <textarea name="" id="" cols="30" rows="10" v-text="notes"></textarea>
</template>

<script>
    export default {
        props: {
            notes: {
                type: String
            }
        },
    }
</script>

<style>

</style>