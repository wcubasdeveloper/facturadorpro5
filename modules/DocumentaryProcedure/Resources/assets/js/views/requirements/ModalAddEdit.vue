<template>
    <el-dialog
        :title="title"
        :visible="visible"
        width="400px"
        @close="onClose"
        @open="onCreate"
    >
        <form autocomplete="off" @submit.prevent="onSubmit">
            <div class="form-body">
                <div class="form-group">
                    <label for="name">Nombre del requerimiento</label>
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
                <!--
                <div class="form-group">
                    <label >Sera necesario cargarlo al sistema?</label>
                    <el-checkbox
                        v-model="form.file"
                        :class="{ 'is-invalid': errors.description }"
                    ></el-checkbox>

                    <div v-if="errors.file" class="invalid-feedback">
                        {{ errors.file[0] }}
                    </div>
                </div>
                -->
                <div class="row text-center">
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
export default {
    props: {
        visible: {
            type: Boolean,
            required: true,
            default: false,
        },
        requirement: {
            type: Object,
            required: false,
            default: {},
        },
    },
    data() {
        return {
            form: {},
            title: "",
            errors: {},
            loading: false,
            basePath: "/documentary-procedure/requirements",
        };
    },
    methods: {
        onUpdate() {
            this.loading = true;
            let param = {
                ...this.form,
                'id':this.requirement.id,
            }
            this.$http
                .put(`${this.basePath}/update`, param)
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
            if (this.requirement) {
                this.onUpdate();
            } else {
                this.onStore();
            }
        },
        onClose() {
            this.$emit("update:visible", false);
        },
        onCreate() {
            if (this.requirement) {
                this.form = this.requirement;
                this.title = "Editar requerimiento";
            } else {
                this.title = "Crear requerimiento";
                this.form = {
                };
            }
        },
    },
};
</script>
