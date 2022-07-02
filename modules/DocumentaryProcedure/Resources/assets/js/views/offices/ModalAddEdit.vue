<template>
    <el-dialog
        v-if="OfficeNotNull"
        :title="title"
        :visible="visible"
        @close="onClose"
        @open="onCreate"
    >
        <form autocomplete="off" @submit.prevent="onSubmit">
            <div class="form-body row">
                <div class="form-group col-md-12">
                    <label for="name">
                        Nombre de la etapa
                    </label>
                    <input
                        id="name"
                        v-model="form.name"
                        :class="{ 'is-invalid': errors.name }"
                        class="form-control"
                        type="text"
                    />
                    <div v-if="errors.name" class="invalid-feedback">
                        {{ errors.name[0] }}
                    </div>
                </div>
                <div class="form-group col-md-12">
                <label for="description">Descripción</label>
                    <input
                        id="description"
                        v-model="form.description"
                        :class="{ 'is-invalid': errors.description }"
                        class="form-control"
                        type="text"
                    />
                    <div v-if="errors.description" class="invalid-feedback">
                        {{ errors.description[0] }}
                    </div>
                </div>





                <div class="form-group col-md-3">
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
                <div class="form-group col-md-3">

                    <label
                        class=" badge"
                        :style="'background-color:'+ form.color+
                                             ';font-size: 12px;'"
                    > Texto de prueba</label>
                </div>


                <div class="form-group col-md-6">
                    <label>Activo</label>
                    <el-switch v-model="form.active"></el-switch>
                </div>
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
                        <el-button class="btn-block" @click="onClose">Cancelar</el-button>
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
            'offices',
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
                color: "#fff",
            },
            title: "",
            errors: {},
            loading: false,
            basePath: "/documentary-procedure/offices",
            predefineColors: [
                '#ff4500',
                '#ff8c00',
                '#ffd700',
                '#90ee90',
                '#00ced1',
                '#1e90ff',
                '#c71585',
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
                .finally(() => {
                    this.loading = false;
                    this.errors = {};
                })
                .catch((error) => {
                    this.axiosError(error);
                });
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
                .finally(() => {
                    this.loading = false;
                    this.errors = {};
                })
                .catch((error) => {
                    this.axiosError(error);
                });
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
            this.color = null;
            if (this.office && this.office.id) {
                this.form = this.office;
                this.title = "Editar Etapa";
            } else {
                this.title = "Crear Etapa";
                this.form = {
                    active: true,
                };
            }
        },
    },
};
</script>
