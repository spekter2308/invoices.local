<template>
    <div>
        <b-button variant="primary" :id="setId" @click="$bvModal.show(setId)">Edit</b-button>

        <b-modal :id="setId" hide-footer>
            <template slot="modal-title">
                Edit
            </template>
            <div class="d-block text-center">
                <form method="post" :action="setAction">
                    <b-input id="edit_name" :value="name" type="text"></b-input>
                    <b-button variant="success" type="submit" class="mt-3">Save</b-button>
                </form>
            </div>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: {
            id: {
                required: true,
                type: String,
            },
            name: {
                required: true,
                type: String,
            }
        },
        data() {
            return {
            }
        },
        computed: {
            setId() {
                return 'modal-edit-' + this.id;
            },
            setAction() {
                return '/invoices/save/select-item/' + this.id;
            }
        }
    }
</script>

<style scoped>

</style>