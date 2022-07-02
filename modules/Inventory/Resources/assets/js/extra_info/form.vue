<template>
    <el-dialog
        :close-on-click-modal="false"
        :close-on-press-escape="false"
        :show-close="false"
        :title="titleDialog"
        :visible="showColorDialog"
        append-to-body
        width="30%"
        @open="create">
        <div v-loading="loading">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div :class="{'has-danger': errors['color.name']}"
                         class="form-group">
                        <label class="control-label">Nombre</label>
                        <el-input v-model="ccolor.name"></el-input>
                        <small v-if="errors['color.name']"
                               class="form-control-feedback"
                               v-text="errors['color.name'][0]"></small>
                    </div>
                </div>
            </div>
        </div>

        <span slot="footer"
              class="dialog-footer">
            <div class="row">
                <div class="col-6">&nbsp;</div>
            <div class=" col-md-3">
                <el-button @click.prevent="save()">Guardar</el-button>
            </div>
            <div class="col-md-3">
                <el-button @click="clickClose">Cerrar</el-button>
            </div>
            </div>
        </span>
    </el-dialog>
</template>

<script>
import {mapActions, mapState} from "vuex/dist/vuex.mjs";

export default {
    props: [
        'resource'
    ],
    components: {},
    computed: {
        ...mapState([
            'records',
            'loading_submit',
            'config',
            'showColorDialog',
            'color',
        ]),
    },
    data() {
        return {
            titleDialog: 'Nuevo elemento',
            // resource: '${this.resource}',
            loading: false,
            showDialog: false,
            showDialogOptions: false,
            loading_form: false,
            errors: {},
            ccolor: {
                id:0,
                name:'',
            },
            form: {},
            devolution_reasons: [],
            company: null,
            establishments: [],
            establishment: null,
        }
    },
    created() {
        this.ccolor = this.color
        this.loadConfiguration()

        this.loading_form = true
        this.getRecord()

    },
    methods: {
        ...mapActions([
            'loadConfiguration'
        ]),

        create() {
            this.ccolor = this.color
            this.getRecord()
        },
        getRecord() {
            let id = 0
            if (this.ccolor.id !== undefined) {
                id = this.ccolor.id

            }
            this.$http.post(`/extra_info_items/${this.resource}/record/${id}`)
                .then((response) => {
                    this.ccolor = response.data
                })
                .catch((error) => {
                    console.error(error)
                })
                .then(() => {
                    if (this.ccolor.id !== undefined) {
                        this.titleDialog = `Editando el elemento ${this.ccolor.name}`
                    } else {
                        this.titleDialog = `Nuevo elemento`

                    }

                })

        },
        resetForm() {
        },
        changeEstablishment() {

        },
        addRow(row) {
            this.form.items.push(JSON.parse(JSON.stringify(row)));
        },
        clickRemoveItem(index) {

        },
        submit() {

        },
        save() {
            let id = 0
            if (this.ccolor.id !== undefined) {
                id = this.ccolor.id
            }

            let param = this.ccolor;
            this.$http.post(`/extra_info_items/${this.resource}/save/${id}`, {param})
                .then((response) => {
                    this.ccolor = response;
                })
                .catch((error) => {
                    console.error(error)
                })
                .then(()=>{
                    this.clickClose()
                })
            return null;
        },
        clickClose() {
            this.$store.commit('setLoadingSubmit',true);
            return this.$http
                .get(`/extra_info_items/${this.resource}/records`)
                .then(response => {
                    let data = response.data;
                    this.$store.commit('setRecords',  data.data)
                    this.$store.commit('setPagination', {
                        current_page: data.current_page,
                        total: data.total,
                        per_page: parseInt(data.per_page),

                    })

                })
                .catch(error => {
                })
                .then(() => {
                    this.$store.commit('setLoadingSubmit',false);
                    this.$store.commit('canShowColorDialog', false)
                    this.$store.commit('setColor', this.ccolor)

                });
        },
    }
}
</script>
