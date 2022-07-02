<template>
    <div class="card" v-if="!form.token_false">
        <div class="card-header bg-info">
            <h3 class="my-0">Consulta RUC/DNIe</h3>
        </div>
        <div class="card-body">
            <form autocomplete="off"
                  @submit.prevent="submit">
                <div class="form-body">
                    <div class="row">
                        <!--
                        <div class="col-md-12">
                            <div :class="{'has-danger': errors.token_false}"
                                 class="form-group">
                                <el-checkbox v-model="form.token_false">Usar consulta interna</el-checkbox>
                                <br>
                                <small v-if="errors.token_false"
                                       class="form-control-feedback"
                                       v-text="errors.token_false[0]"></small>
                            </div>
                        </div>
                        -->
                        <div v-if="!form.token_false"
                             class="col-md-12">
                            <div :class="{'has-danger': errors.url_apiruc}"
                                 class="form-group">
                                <label class="control-label">
                                    URL
                                </label>
                                <el-input v-model="form.url_apiruc"></el-input>
                                <small
                                    v-if="errors.url_apiruc"
                                    class="form-control-feedback"
                                    v-text="errors.url_apiruc[0]"
                                ></small>
                            </div>
                        </div>
                        <div v-if="!form.token_false"
                             class="col-md-12">
                            <div :class="{'has-danger': errors.token_apiruc}"
                                 class="form-group">
                                <label class="control-label">Token</label>
                                <el-input v-model="form.token_apiruc"></el-input>
                                <small
                                    v-if="errors.token_apiruc"
                                    class="form-control-feedback"
                                    v-text="errors.token_apiruc[0]"
                                ></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions text-right pt-2">
                    <el-button :loading="loading_submit"
                               native-type="submit"
                               type="primary">Guardar
                    </el-button>
                </div>
            </form>
        </div>
    </div>
</template>


<script>
import {mapActions, mapState} from "vuex";

export default {
    computed: {
        ...mapState([
            'config',
        ]),
    },
    data() {
        return {
            loading_submit: false,
            resource: "configurations",
            errors: {},
            form: {
                url_apiruc: null,
                token_apiruc: null,
                token_false: false
            },
        };
    },
    created(){
        this.loadConfiguration()
    },
    mounted() {
         this.initForm();

         this.$http.get(`/${this.resource}/apiruc`).then(response => {
             this.form = response.data
            // this.form.url_apiruc = response.data.url_apiruc;
            // this.form.token_apiruc = response.data.token_apiruc;

            if (this.form.token_apiruc == 'false') {
                this.form.token_false = true;
            }

         });
    },
    methods: {

        ...mapActions([
            'loadConfiguration',
        ]),
        initForm() {
            this.errors = {};
            this.form = {
                url_apiruc: null,
                token_apiruc: null,
                token_false: false
            };
        },
        submit() {
            this.loading_submit = true;
            if (this.form.token_false == true) {
                this.form.token_apiruc = 'false';
            }
            this.$http
                .post(`/${this.resource}/apiruc`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    } else {
                        console.log(error);
                    }
                })
                .then(() => {
                    this.loading_submit = false;
                });
        },
    }
};
</script>

