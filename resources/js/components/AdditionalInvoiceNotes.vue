<template>
    <div class="invoice-notes">
        <div class="notes-wrapper">
            <a @click="show" class="btn btn-secondary">Add note</a>

            <div v-if="isHidden" class="notes-input-wrapper">
                <div class="notes-input">
                    <label>Notes</label>
                    <textarea rows="1" cols="20" v-model="note"></textarea>
                </div>

                <button class="btn btn-primary" @click="saveNote">Add</button>
            </div>
        </div>

    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        props: {
            invoiceId: {
                type: Number
            }
        },
        data() {
            return {
                isHidden: false,
                note: '',
            }
        },
        methods: {
            show() {
                this.isHidden = true;
            },
            saveNote() {
                if (this.note.match(/[a-zA-Z0-9]/i)) {
                    const invoiceNote = {
                        invoice_id: this.invoiceId,
                        notes: this.note
                    }
                    axios.post('/notes/save', invoiceNote).then(response => {
                        this.note = ''
                        location.reload()
                    }).catch(error => console.log(error))
                }
                this.note = ''
                this.isHidden = false
            }
        }
    }
</script>

<style scoped>
    .invoice-notes {
        display: inline-block;
    }
    .notes-wrapper {
        display: flex;
        align-items: center;
    }
    .notes-input-wrapper {
        margin-left: 40px;
        display: flex;
        align-items: center;
        line-height: 20px;
    }
    .notes-input {
        display: flex;
        flex-direction: column;
    }
    .notes-input label {
        margin: 0;
    }
    .notes-input-wrapper button {
        text-align: center;
        margin: 0 20px 0;
    }

</style>