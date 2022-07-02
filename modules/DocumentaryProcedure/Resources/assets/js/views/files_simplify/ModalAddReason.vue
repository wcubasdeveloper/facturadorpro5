<template>
    <div>
        <el-dialog
            :closeOnClickModal="false"
            :title="title"
            :visible="visible"
            @close="onClose"
            @open="onCreate"
        >

            <div class="form-body row">

                <div
                    :class="{ 'has-danger': errors.reason }"
                    class="form-group col-sm-12 col-md-12">
                    <label>
                        Motivo
                    </label>
                    <el-input v-model="form.reason"
                              type="textarea"
                              :rows="2"
                              placeholder="Motivo"></el-input>
                    <small
                        v-if="errors.reason"
                        class="form-control-feedback"
                        v-text="errors.reason[0]"
                    ></small>
                </div>

                <div class="row text-center col-12 p-t-20">
                    <div class="col-6">
                        <el-button class="btn-block"
                                   @click="onClose">Cancelar
                        </el-button>
                    </div>
                    <div
                        class="col-6">
                        <el-button
                            :loading="loading"
                            class="btn-block"
                            native-type="submit"
                            type="primary"
                            @click="sendArchive"
                        >Guardar
                        </el-button
                        >
                    </div>
                </div>


            </div>

        </el-dialog>
    </div>
</template>

<script>
import moment from "moment";
import {mapActions, mapState} from "vuex";

export default {
    components: {},
    props: {
        id: {
            type: Number,
            required: true,
            default: 0,
        },
        visible: {
            type: Boolean,
            required: true,
            default: false,
        },
    },
    data() {
        return {
            headers: headers_token,
            errors: {},
            form: {
                reason: '',
            },
            loading: false,
            title: '',
        };
    },
    created() {
    },
    mounted() {
        // this.onInitializeForm();
    },
    computed: {
        ...mapState([
            'offices',
            'files',
            'file',
            'actions',
            'documentTypes',
            'processes',
            'customers',
            'statusDocumentary',
            'sellers',

        ]),
    },
    methods: {
        ...mapActions([
            'loadOffices',
            'loadActions',
            'loadCustomers',
            'loadProcesses',
            'loadDocumentTypes',
            'loadFiles',
        ]),
        sendArchive() {
            this.$http
                .post(`/documentary-procedure/files_simplify/archive/` + this.id, this.form)
                .then((response) => {
                    this.$message({
                        type: "success",
                        message: response.data.message,
                    });

                })
                .catch((error) => {
                    this.axiosError(error)
                })
                .finally(() => {
                    this.addRow();
                });
        },
        addRow() {
            this.$emit("addArchive", this.guide);
            this.onClose()
        },
        onClose() {
            this.$emit("update:visible", false);
        },

        onCreate() {
            this.title = "Añade un motivo de archivado"
            this.loading = false;


        },
    },
};
</script>
