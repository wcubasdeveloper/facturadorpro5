<template>
    <el-dialog
        v-if="OfficeNotNull"
        :title="title"
        :visible="visible"
        @close="onClose"
        @open="onCreate"
    >
        <form autocomplete="off"
              @submit.prevent="onSubmit">
            <div class="form-body row">
                <div class="form-group col-md-12">
                    <label for="name">
                        Nombre del estado
                    </label>
                    <input
                        id="name"
                        v-model="form.name"
                        :class="{ 'is-invalid': errors.name }"
                        class="form-control"
                        type="text"
                    />
                    <div v-if="errors.name"
                         class="invalid-feedback">
                        {{ errors.name[0] }}
                    </div>
                </div>

                <div class="form-group col-md-5">
                    <label for="color">
                        Color de fondo<br>
                    </label>
                    <el-color-picker
                        id="color"
                        v-model="form.color"
                        :color-format="'true'"
                        :predefine="predefineColors"
                        :size="'medium'"
                        show-alpha
                    >
                    </el-color-picker>

                    <div v-if="errors.color"
                         class="invalid-feedback">
                        {{ errors.name[0] }}
                    </div>

                </div>
                <div class="form-group col-md-7">

                    <label
                        :style="'background-color:'+ form.color+
                                             ';font-size: 12px;'"
                        class=" badge"
                    > Texto de prueba</label>
                </div>
                <!--

                <div class="form-group col-md-6">
                    <label>Mostrar Estado</label>
                    <el-switch v-model="form.active"></el-switch>
                </div>
                -->
                <div class="row text-center col-md-12">
                    <div class="col-6">
                        <el-button
                            :disabled="loading"
                            :loading="loading"
                            class="btn-block"
                            native-type="submit"
                            type="primary"
                        >Guardar
                        </el-button
                        >
                    </div>
                    <div class="col-6">
                        <el-button class="btn-block"
                                   @click="onClose">Cancelar
                        </el-button>
                    </div>
                </div>
            </div>
        </form>
    </el-dialog>
</template>

<script>
import {mapActions, mapState} from "vuex";

export default {
    props: {
        visible: {
            type: Boolean,
            required: true,
            default: false,
        },/*
        parent_offices: {
            type: Array,
            required: false,
            default: [],
        }*/
        /*
        office: {
            type: Object,
            required: false,
            default: {},
        },
        */
    },
    computed: {
        ...mapState([
            'statusDocumentary',
            'office',
            'workers',
        ]),
        OfficeNotNull: function () {
            return this.office !== null;

        },
        hasParent: function () {
            return this.office !== undefined &&
                this.office.parent !== undefined &&
                this.office.parent.id > 0;


        },
    },
    created() {
        this.loadWorkers()


    },
    mounted() {
    },
    data() {
        return {
            form: {
                color: "#FFFFFF",
            },
            title: "",
            errors: {},
            loading: false,
            basePath: "/documentary-procedure/status",
            predefineColors: [
                '#FF4500',
                '#FF8C00',
                '#FFD700',
                '#90EE90',
                '#00CED1',
                '#1E90FF',
                '#C71585',
            ],
        };
    },
    methods: {
        ...mapActions([
            'loadWorkers',
        ]),
        onUpdate() {
            this.loading = true;
            this.$http
                .put(`${this.basePath}/${this.office.id}/update`, this.form)
                .then((response) => {
                    this.$message({
                        message: response.data.message,
                        type: "success",
                    });
                    this.$emit("onUpdateItem", response.data.data);
                    this.onClose();
                })

                .catch((error) => {
                    this.axiosError(error);
                })
                .finally(() => {
                    this.loading = false;
                    this.errors = {};
                })
        },
        onStore() {
            this.loading = true;
            this.$http
                .post(`${this.basePath}/store`, this.form)
                .then((response) => {
                    this.$message({
                        message: response.data.message,
                        type: "success",
                    });
                    this.$emit("onAddItem", response.data.data);
                    this.onClose();
                })
                .catch((error) => {
                    this.axiosError(error);
                })
                .finally(() => {
                    this.loading = false;
                    this.errors = {};
                })
        },
        onSubmit() {
            if (this.office && this.office.id) {

                this.onUpdate();
            } else {
                this.onStore();
            }
        },
        onClose() {
            this.$emit("update:visible", false);
            this.$store.commit('setOffice', {})
        },
        onCreate() {
            if (this.office && this.office.id) {
                this.form = this.office;
                this.title = "Editar Estado";
            } else {
                this.title = "Crear Estado";
                this.form = {
                    name: '',
                    color: "#FFFFFF",
                };
            }
        },
    },
};
</script>
