<template>
    <div>
        <b-button variant="primary" :id="setId" @click="$bvModal.show(setId)">Edit</b-button>

        <b-modal centered  :id="setId" hide-footer>
            <template slot="modal-title">
                Edit
            </template>
            <div class="d-block text-right">
                <form method="post" :action="setAction">
                    <input type="hidden" name="_token" :value="token_csrf">
                    <b-input min="3" :required="true" id="edit_name" :value="name" name="name" type="text"></b-input>
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
                token_csrf: window.Laravel.csrfToken
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