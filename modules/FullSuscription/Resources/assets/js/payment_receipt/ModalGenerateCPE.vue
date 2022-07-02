<template>
    <div>
        <el-dialog
            :close-on-click-modal="false"
            :close-on-press-escape="false"
            :visible="show"
            title="Generar comprobante desde múltiples notas"
            @close="onClose"
            @open="onOpened"
        >
            <div class="row">
                <div class="col-2">
                    <el-select
                        v-model="filter.type"
                        :disabled="loading"
                        @click="onFetchClients"
                    >
                        <el-option
                            key="document"
                            label="# de documento"
                            value="document"
                        ></el-option>
                        <el-option key="name"
                                   label="Nombres"
                                   value="name"></el-option>
                    </el-select>
                </div>
                <div class="col-5 form-group">
                    <el-select
                        v-model="form.client_id"
                        :loading="loading"
                        :remote-method="onFindClients"
                        filterable
                        placeholder="Ingrese uno más caracteres"
                        remote
                        reserve-keyword
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
                        placeholder="Fecha de emisión"
                        style="width: 100%"
                        type="date"
                        value-format="yyyy-MM-dd"
                    >
                    </el-date-picker>
                </div>
                <div class="col-2 form-group">
                    <el-button class="btn-block"
                               type="primary"
                               @click="onFindNotes">
                        <i class="fa fa-search"></i>
                    </el-button>
                </div>
            </div>
            <div v-if="notes"
                 class="table-responsive pt-5">
                <span>Seleccione una o más Recibos de pago para poder continuar</span>
                <div v-if="errors.notes_id"
                     class="alert alert-warning"
                     role="alert">
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
                    <tr v-for="dis in notes"
                        :key="dis.id">
                        <td>
                            <el-switch
                                v-model="dis.selected"
                                @change="onFillSelectedNotes"
                            ></el-switch>
                        </td>
                        <td>
                <span>{{ dis.series }}</span
                >-
                            <span>{{ dis.number | pad(0, 3) }}</span>
                        </td>
                        <td>{{ dis.date_of_issue | toDate }}</td>
                    </tr>
                    </tbody>
                </table>
                <div class="text-center">
                    <el-button
                        v-if="form.selecteds.length > 0"
                        :disabled="loading"
                        type="primary"
                        @click="onFetchNoteItems"
                    >Generar CPE
                    </el-button
                    >
                    <el-button :disabled="loading"
                               @click="onClose">Cerrar
                    </el-button>
                </div>
            </div>
        </el-dialog>
    </div>
</template>

<script>
export default {
    props: {
        show: {
            required: true,
            type: Boolean,
        },
    },
    data() {
        return {
            title: "",
            loading: false,
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
            group_items_generate_document: false
        };
    },
    methods: {
        onFetchNoteItems() {
            if (this.form.selecteds.length === 0) {
                this.$message({
                    message: "Seleccione una o más Recibos de pago por favor",
                    type: "warning",
                });
                return;
            }
            this.loading = true;

            if (this.group_items_generate_document) {

                const data = {
                    notes_id: this.form.selecteds,
                };

                this.$http
                    .post("/sale-notes/items", data)
                    .then((response) => {
                        const notes = [];
                        this.notes.map((d) => {
                            if (d.selected) {
                                notes.push(`${d.series}-${d.number}`);
                            }
                        });
                        const items = response.data.data;
                        const data = [];
                        items.map((i) => {
                            const it = {
                                id: i.item_id,
                                quantity: 0,
                            };
                            items.map((ite) => {
                                if (ite.item_id === it.id) {
                                    it.quantity = it.quantity + parseFloat(ite.quantity);
                                }
                            });
                            const itemIsDuplicated = data.find((item) => item.id === it.id);
                            if (itemIsDuplicated) {
                                itemIsDuplicated.quantity = it.quantity;
                            } else {
                                data.push(it);
                            }
                        });
                        const client = this.clients.find((c) => c.id === this.form.client_id);
                        localStorage.setItem("client", JSON.stringify(client));
                        localStorage.setItem("itemsForNotes", JSON.stringify(data));
                        localStorage.setItem("notes", JSON.stringify(notes));
                        this.onClose();
                        window.location.href = "/documents/create";
                    })
                    .catch((error) => this.axiosError(error))
                    .finally(() => (this.loading = false));

            } else {
                this.processNotGroupItems()
            }
        },
        processNotGroupItems() {

            const data = {
                notes_id: this.form.selecteds,
                select_all: true
            };

            this.$http.post("/sale-notes/items", data)
                .then((response) => {

                    const notes = [];
                    this.notes.map((d) => {
                        if (d.selected) {
                            notes.push(`${d.series}-${d.number}`);
                        }
                    });

                    const items = response.data.data;

                    const client = this.clients.find((c) => c.id === this.form.client_id);
                    localStorage.setItem("client", JSON.stringify(client));
                    localStorage.setItem("itemsNotGroupForNotes", JSON.stringify(items));
                    localStorage.setItem("notes", JSON.stringify(notes));
                    this.onClose();
                    window.location.href = "/documents/create";
                })
                .catch((error) => this.axiosError(error))
                .finally(() => (this.loading = false));


        },
        onFillSelectedNotes() {
            this.form.selecteds = [];
            this.notes.map((d) => {
                if (d.selected) {
                    this.form.selecteds.push(d.id);
                }
            });
        },
        onFindNotes() {
            this.form.selecteds = [];
            this.loading = true;
            const params = this.form;
            this.$http
                .get(`/sale-notes/list-by-client`, {params})
                .then((response) => {
                    this.notes = response.data.data.map((d) => {
                        d.selected = false;
                        return d;
                    });
                })
                .finally(() => (this.loading = false));
        },
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
        onOpened() {
            this.filter.type = "name";
            this.filter.name = null;
            this.form.client_id = null;
            this.onFetchClients();
            this.getConfigGroupItems();
        },
        getConfigGroupItems() {

            this.$http
                .get("/sale-notes/config-group-items")
                .then((response) => {
                    // console.log(response)
                    this.group_items_generate_document = response.data.group_items_generate_document
                })

        },
        onClose() {
            this.notes = [];
            this.$emit("update:show", false);
        },
    },
};
</script>
