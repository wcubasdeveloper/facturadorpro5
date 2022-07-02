<template>
    <el-dialog
        :title="titleDialog"
        width="40%"
        :visible="showMigrate"
        @open="onOpened"
        :close-on-click-modal="false"
        :close-on-press-escape="false"
        append-to-body
        :show-close="false"
    >
        <div class="row">
            <div class="col-2">
                <el-select
                    v-model="filter.type"
                    @click="onFetchClients"
                    @change="onFetchClients"
                    :disabled="loading"
                >
                    <el-option
                        key="document"
                        value="document"
                        label="# de documento"
                    ></el-option>
                    <el-option key="name" value="name" label="Nombres"></el-option>
                </el-select>
            </div>
            <div class="col-5 form-group">
                <el-select
                    v-model="form.client_id"
                    filterable
                    remote
                    reserve-keyword
                    placeholder="Ingrese uno más caracteres"
                    :remote-method="onFindClients"
                    :loading="loading"
                >
                    <el-option
                        v-for="item in clients"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id"
                    >
                    </el-option>
                </el-select>
            </div>
            <div class="col-3 form-group">
                <el-date-picker
                    v-model="form.date_of_issue"
                    type="date"
                    style="width: 100%"
                    placeholder="Fecha de emisión"
                    value-format="yyyy-MM-dd"
                >
                </el-date-picker>
            </div>
            <div class="col-2 form-group">
                <el-button class="btn-block" @click="loadNv" type="primary">
                    <i class="fa fa-search"></i>
                </el-button>
            </div>
        </div>

        <div class="table-responsive pt-5" v-if="notes">
            <span>Seleccione una o más notas de venta para poder continuar</span>
            <div v-if="errors.notes_id" class="alert alert-warning" role="alert">
                {{ errors.notes_id[0] }}
            </div>
            <table class="table table-hover table-stripe">
                <thead>
                <tr>
                    <th></th>
                    <th>Nota</th>
                    <th>Fecha de emisión</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="dis in notes" :key="dis.id">
                    <td>
                        <el-switch
                            v-model="dis.selected"
                            @change="onFillSelectedNotes"
                        ></el-switch>
                    </td>
                    <td>
                        <span>{{ dis.number_full }}</span>
                    </td>
                    <td>{{ dis.date_of_issue | toDate }}</td>
                </tr>
                </tbody>
            </table>
            <div class="text-center">
                <el-button
                    v-if="form.selecteds.length > 0 && configuration.send_data_to_other_server === true"
                    type="primary"
                    :disabled="loading"
                    @click="onFetchNoteItems"
                >Migrar datos
                </el-button
                >
                <el-button :disabled="loading" @click="onClose">Cerrar</el-button>
            </div>
        </div>
    </el-dialog>
</template>

<script>
export default {
    props: [
        "showMigrate",
        'configuration'
    ],
    data() {
        return {
            titleDialog:'',
            loading: false,
            url: '',
            clients: [],
            filter: {
                type: "name",
                name: null,
            },
            form: {
                client_id: null,
                selecteds: [],
            },
            notes: [],
            errors: {},
        };
    },
    mounted() {
        this.titleDialog =  "Migracion a servidor"
        this.$http.post('/sale-notes/urlUpToOther').then(response => {
            this.url = response.data;
            this.titleDialog =  "Migracion a servidor "+this.url
        })


    },
    methods: {

        onFindClients(query) {
            this.filter.name = query;
            this.onFetchClients();
        },
        onFetchClients() {
            this.loading = true;
            this.notes = [];
            this.form.selecteds = [];
            const params = this.filter;
            this.$http
                .get("/customers/list", {params})
                .then((response) => {
                    this.clients = response.data.data;
                })
                .finally(() => (this.loading = false));
        },
        onFetchNoteItems() {
            if (this.form.selecteds.length === 0) {
                this.$message({
                    message: "Seleccione una o más notas de venta por favor",
                    type: "warning",
                });
                return;
            }
            this.loading = true;
            const data = {
                notes_id: this.form.selecteds,
            };
            this.sendToServer(this.form.selecteds)
            this.loading = false;


        },

        sendMessage(message,success){
            let type = 'error'
            if (success) {
                type = 'success';
                // this.$message.success(message,{dangerouslyUseHTMLString:false});
            } else {
                // this.$message.error(message,{dangerouslyUseHTMLString:false});
            }
            this.$notify({
                title: "",
                dangerouslyUseHTMLString: true,
                message: message,
                type: type,
                duration: 12000
            })
        },
        sendToServer(recordId) {
            this.$http.post('/sale-notes/UpToOther',{'sale_notes_id':recordId}).then(response => {
                let data = response.data;
                this.sendMessage(data.message,data.success)
                /*
                let temp = this;
                data.proccesed.forEach(function(a){
                   temp.sendMessage(a.message,a.success)
                })
                */


            }).catch(error => {
                if(
                    error.response!== undefined &&
                    error.response.status !== undefined &&
                    error.response.status.errors !== undefined &&
                    error.response.status === 422 ) {
                    this.errors = error.response.data.errors;
                } else {
                    console.log(error);
                }
            }).then(() => {
            });
        },

        loadNv() {
            const params = this.form;
            this.$http.post('/sale-notes/getUpToOther',{params})
                .then(response => {
                    this.notes = response.data;
                })
                .catch(error => {
                    console.error(error)
                })

        },
        onFillSelectedNotes() {
            this.form.selecteds = [];
            this.notes.map((d) => {
                if (d.selected) {
                    this.form.selecteds.push(d.id);
                }
            });
        },
        onOpened() {
            this.filter.type = "name";
            this.filter.name = null;
            this.form.client_id = null;
            this.onFetchClients();
        },
        onClose() {
            this.notes = [];
            this.$emit("update:showMigrate", false);
        },
        async submit() {
            await this.$emit("update:showMigrate", false);
        },

        clickCancel(item) {
            //this.lots.splice(index, 1);
            item.deleted = true;
            // this.$emit("addRowLotGroup", this.lots);
        },

        async clickCancelSubmit() {
            await this.$emit("update:showMigrate", false);
        },
        close() {
            this.$emit("update:showMigrate", false);
        }
    }
};
</script>
