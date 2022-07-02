<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">
                Producto Fabricado
            </h3>
        </div>
        <div class="tab-content">
            <form autocomplete="off"
                  @submit.prevent="submit">
                <div class="form-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div :class="{'has-danger': errors.item_id}"
                                 class="form-group">
                                <label class="control-label">Producto </label>
                                <el-select
                                    v-model="form.item_id"
                                    :loading="loading_search"
                                    :remote-method="searchRemoteItems"
                                    filterable
                                    remote
                                    @change="changeItem"
                                >
                                    <el-option
                                        v-for="option in items"
                                        :key="option.id"
                                        :label="option.description"
                                        :value="option.id"
                                    ></el-option>
                                </el-select>
                                <small
                                    v-if="errors.item_id"
                                    class="form-control-feedback"
                                    v-text="errors.item_id[0]"
                                ></small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div
                                :class="{'has-danger': errors.warehouse_id}"
                                class="form-group"
                            >
                                <label class="control-label">Almacén</label>
                                <el-select v-model="form.warehouse_id"
                                           filterable>
                                    <el-option
                                        v-for="option in warehouses"
                                        :key="option.id"
                                        :label="option.description"
                                        :value="option.id"
                                    ></el-option>
                                </el-select>
                                <small
                                    v-if="errors.warehouse_id"
                                    class="form-control-feedback"
                                    v-text="errors.warehouse_id[0]"
                                ></small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div :class="{'has-danger': errors.quantity}"
                                 class="form-group">
                                <label class="control-label">Cantidad</label>
                                <el-input-number
                                    v-model="form.quantity"
                                    :controls="false"
                                    :min="0"
                                    :precision="precision"
                                ></el-input-number>
                                <small
                                    v-if="errors.quantity"
                                    class="form-control-feedback"
                                    v-text="errors.quantity[0]"
                                ></small>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div
                                :class="{'has-danger': errors.inventory_transaction_id}"
                                class="form-group"
                            >
                                <label class="control-label">Motivo traslado</label>
                                <input
                                    class="form-control"
                                    readonly
                                    type="text"
                                    value="Ingreso de producción"
                                />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div :class="{'has-danger': errors.name}"
                                 class="form-group">
                                <label class="control-label">Número de Ficha</label>
                                <el-input v-model="form.name"></el-input>
                                <small v-if="errors.name"
                                       class="form-control-feedback"
                                       v-text="errors.name[0]"></small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div :class="{'has-danger': errors.comment}"
                                 class="form-group">
                                <label class="control-label">Comentario</label>
                                <el-input v-model="form.comment"></el-input>
                                <small v-if="errors.comment"
                                       class="form-control-feedback"
                                       v-text="errors.comment[0]"></small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div
                                :class="{'has-danger': errors.production_order}"
                                class="form-group"
                            >
                                <label class="control-label">Orden de producción</label>
                                <input
                                    v-model="form.production_order"
                                    class="form-control"
                                    placeholder="Orden de producción"
                                    type="text"
                                />

                                <small
                                    v-if="errors.production_order"
                                    class="form-control-feedback"
                                    v-text="errors.production_order[0]"
                                ></small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div :class="{'has-danger': errors.machine_id}"
                                 class="form-group">
                                <label class="control-label">
                                    Maquina
                                </label>
                                <el-select v-model="form.machine_id">
                                    <el-option
                                        v-for="option in machines"
                                        :key="option.id"
                                        :label="option.name"
                                        :value="option.id"></el-option>
                                </el-select>

                                <small v-if="errors.machine_id"
                                       class="form-control-feedback"
                                       v-text="errors.machine_id[0]"></small>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div :class="{'has-danger': errors.lot_code}"
                                 class="form-group">
                                <label class="control-label">
                                    Lote
                                </label>
                                <input
                                    v-model="form.lot_code"
                                    class="form-control"
                                    placeholder="Lote"
                                    type="text"
                                />

                                <small v-if="errors.lot_code"
                                       class="form-control-feedback"
                                       v-text="errors.lot_code[0]"></small>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div :class="{'has-danger': errors.agreed}"
                                 class="form-group">
                                <label class="control-label">
                                    Conformes
                                </label>
                                <el-input-number
                                    v-model="form.agreed"
                                    :controls="false"
                                    :min="0"
                                    :precision="precision"
                                ></el-input-number>

                                <small v-if="errors.agreed"
                                       class="form-control-feedback"
                                       v-text="errors.agreed[0]"></small>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div :class="{'has-danger': errors.imperfect}"
                                 class="form-group">
                                <label class="control-label">
                                    Defectuosos
                                </label>


                                <el-input-number
                                    v-model="form.imperfect"
                                    :controls="false"
                                    :min="0"
                                    :precision="precision"
                                ></el-input-number>


                                <small v-if="errors.imperfect"
                                       class="form-control-feedback"
                                       v-text="errors.imperfect[0]"></small>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div :class="{'has-danger': errors.item_extra_data}"
                                 class="form-group">
                                <label class="control-label">
                                    Color
                                </label>
                                <el-select v-model="form.item_extra_data.color"
                                           :disable="item === undefined || item.colors === undefined ||item.colors.length < 1"
                                           filterable>
                                    <el-option
                                        v-for="option in item.colors"

                                        :key="option.id"
                                        :label="option.color_name"
                                        :value="option.id"
                                    ></el-option>
                                </el-select>
                                <small v-if="errors.item_extra_data"
                                       class="form-control-feedback"
                                       v-text="errors.item_extra_data[0]"></small>
                            </div>
                        </div>

                        <hr>
                        <div class="col-12 mt-3">
                                    <div
                                        class="form-group">
                                        <label class="control-label">
                                            Producción
                                        </label>
                                    </div>
                        </div>

                        <div class="col-sm-12 col-md-4 ">
                            <div class="row">


                                <div class="col-6">
                                    <div :class="{'has-danger': errors.date_start}"
                                         class="form-group">
                                        <label class="control-label">
                                            Fecha de inicio
                                        </label>
                                        <el-date-picker v-model="form.date_start"
                                                        :clearable="false"
                                                        format="dd/MM/yyyy"
                                                        type="date"
                                                        value-format="yyyy-MM-dd"></el-date-picker>
                                        <small v-if="errors.date_start"
                                               class="form-control-feedback"
                                               v-text="errors.date_start[0]"></small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div :class="{'has-danger': errors.time_start}"
                                         class="form-group">
                                        <label class="control-label">Hora de Inicio</label>
                                        <el-time-picker v-model="form.time_start"
                                                        dusk="time_start"
                                                        placeholder="Seleccionar"
                                                        value-format="HH:mm:ss"></el-time-picker>
                                        <small v-if="errors.time_start"
                                               class="form-control-feedback"
                                               v-text="errors.time_start[0]"></small>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="col-sm-12 col-md-4 ">
                            <div class="row">
                                <div class="col-6">
                                    <div :class="{'has-danger': errors.date_end}"
                                         class="form-group">
                                        <label class="control-label">
                                            Fecha de Finalización
                                        </label>
                                        <el-date-picker v-model="form.date_end"
                                                        :clearable="false"
                                                        format="dd/MM/yyyy"
                                                        type="date"
                                                        value-format="yyyy-MM-dd"></el-date-picker>
                                        <small v-if="errors.date_end"
                                               class="form-control-feedback"
                                               v-text="errors.date_end[0]"></small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div :class="{'has-danger': errors.time_end}"
                                         class="form-group">
                                        <label class="control-label">Hora de finalización</label>
                                        <el-time-picker v-model="form.time_end"
                                                        dusk="time_end"
                                                        placeholder="Seleccionar"
                                                        value-format="HH:mm:ss"></el-time-picker>
                                        <small v-if="errors.time_end"
                                               class="form-control-feedback"
                                               v-text="errors.time_end[0]"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div
                                :class="{'has-danger': errors.production_collaborator}"
                                class="form-group"
                            >
                                <label class="control-label">Colaborador de producción</label>
                                <input
                                    class="form-control"
                                    v-model="form.production_collaborator"
                                    type="text"
                                    value="Colaborador de produccion"
                                />
                            </div>
                        </div>

                        <hr>
                        <div class="col-12 mt-3">
                                    <div
                                        class="form-group">
                                        <label class="control-label">
                                            Mezcla
                                        </label>
                                    </div>
                        </div>


                        <div class="col-sm-12 col-md-4 ">
                            <div class="row">
                                <div class="col-6">
                                    <div :class="{'has-danger': errors.mix_date_start}"
                                         class="form-group">
                                        <label class="control-label">
                                            Fecha de inicio
                                        </label>
                                        <el-date-picker v-model="form.mix_date_start"
                                                        :clearable="false"
                                                        format="dd/MM/yyyy"
                                                        type="date"
                                                        value-format="yyyy-MM-dd"></el-date-picker>
                                        <small v-if="errors.mix_date_start"
                                               class="form-control-feedback"
                                               v-text="errors.mix_date_start[0]"></small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div :class="{'has-danger': errors.mix_time_start}"
                                         class="form-group">
                                        <label class="control-label">Hora de Inicio</label>
                                        <el-time-picker v-model="form.mix_time_start"
                                                        dusk="time_start"
                                                        placeholder="Seleccionar"
                                                        value-format="HH:mm:ss"></el-time-picker>
                                        <small v-if="errors.mix_time_start"
                                               class="form-control-feedback"
                                               v-text="errors.mix_time_start[0]"></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-4 ">
                            <div class="row">
                                <div class="col-6">
                                    <div :class="{'has-danger': errors.mix_date_end}"
                                         class="form-group">
                                        <label class="control-label">
                                            Fecha de Finalización
                                        </label>
                                        <el-date-picker v-model="form.mix_date_end"
                                                        :clearable="false"
                                                        format="dd/MM/yyyy"
                                                        type="date"
                                                        value-format="yyyy-MM-dd"></el-date-picker>
                                        <small v-if="errors.mix_date_end"
                                               class="form-control-feedback"
                                               v-text="errors.mix_date_end[0]"></small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div :class="{'has-danger': errors.mix_time_end}"
                                         class="form-group">
                                        <label class="control-label">Hora de finalización</label>
                                        <el-time-picker v-model="form.mix_time_end"
                                                        dusk="time_end"
                                                        placeholder="Seleccionar"
                                                        value-format="HH:mm:ss"></el-time-picker>
                                        <small v-if="errors.mix_time_end"
                                               class="form-control-feedback"
                                               v-text="errors.mix_time_end[0]"></small>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-12 col-md-4">
                            <div
                                :class="{'has-danger': errors.mix_collaborator}"
                                class="form-group"
                            >
                                <label class="control-label">Colaborador de Mezcla</label>
                                <input
                                    class="form-control"
                                    v-model="form.mix_collaborator"
                                    type="text"
                                    value="Colaborador de Mezcla"
                                />
                            </div>
                        </div>
                                <div class="col-sm-12 col-md-3">
                                    <label class="control-label">
                                        Ficha Informativa
                                        <el-tooltip
                                            class="item"
                                            content="No se contabilizará el stock"
                                            effect="dark"
                                            placement="top-start">
                                            <i class="fa fa-info-circle"></i>
                                        </el-tooltip>
                                    </label>
                                    <div class="form-group"
                                         :class="{'has-danger': errors.informative}">
                                        <el-switch
                                            v-model="form.informative"
                                            active-text="Si"
                                            inactive-text="No"></el-switch>
                                        <small class="form-control-feedback"
                                               v-if="errors.informative" v-text="errors.informative[0]">

                                        </small>
                                    </div>
                                </div>

                        <div class="col-sm-12 col-md-9" v-if="form.informative">
                            <div :class="{'has-danger': errors.proccess_type}"
                                 class="form-group">
                                <label class="control-label">
                                    Tipo de proceso
                                </label>
                                <el-input v-model="form.proccess_type"></el-input>
                                <small v-if="errors.proccess_type"
                                       class="form-control-feedback"
                                       v-text="errors.proccess_type[0]"></small>
                            </div>
                        </div>



                    </div>

                </div>

                <div class="form-actions text-right mt-4">
                    <el-button
                        :loading="loading_submit"
                        native-type="submit"
                        type="primary"
                    >Guardar
                    </el-button>
                </div>
                <div v-if="supplies.length > 0"
                     class="col-12 col-md-12 mt-3">
                    <h3 class="my-0">Lista de materiales</h3>

                    <div class="col-md-12 mt-3 table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Cantidad</th>
                                <th class="text-center">Almacen</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="row in supplies">
                                <th>{{ row.individual_item.description }}</th>
                                <th>{{ row.quantity }}</th>
                                <th>

                                    <el-select v-model="row.warehouse_id"
                                               filterable>
                                        <el-option
                                            v-for="option in warehouses"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id"
                                        ></el-option>
                                    </el-select>
                                </th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


            </form>
        </div>
    </div>
</template>

<script>


export default {

    prop: ['id'],
    computed: {},
    data() {
        return {
            resource: 'production',
            loading_submit: false,
            errors: {},
            item: {


            },
            supplies: {},
            form: {
                items: [],
                informative:false,
                item_extra_data: {
                    color:null
                },

            },
            loading_search: false,
            warehouses: [],
            precision: 2,
            items: [],
            machines: [],
        }
    },
    created() {

        this.getTable();
        this.initForm()
    },
    methods: {
        initForm() {
            this.form = {
                id: this.id,
                item_id: null,
                warehouse_id: null,
                quantity: 0,
                informative:false,


                agreed:0,
                imperfect:0,
                lot_code:null,
                item_extra_data: {
                    color:null
                },

            }
            this.supplies = {};

        },
        getTable() {
            this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    let data = response.data
                    this.warehouses = data.warehouses
                    this.items = data.items
                    this.machines = data.machines
                })

        },
        async searchRemoteItems(search) {
            this.loading_search = true;
            this.items = [];
            await this.$http.post(`/${this.resource}/search_items`, {'search': search})
                .then(response => {
                    this.items = response.data.items
                })
            this.loading_search = false;
        },
        async submit() {

            if (this.form.quantity < 1) {
                return this.$message.error('La cantidad debe ser mayor a 0');
            }

            this.loading_submit = true

            this.form.supplies = this.supplies
            await this.$http.post(`/${this.resource}/create`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
                        this.initForm()
                        window.location.href = '/production'

                    } else {
                        this.$message.error(response.data.message)
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data
                    } else {
                        console.log(error)
                    }
                })
                .finally(() => {
                    this.loading_submit = false
                })
        },
        changeItem() {
            let item = _.find(this.items, {'id': this.form.item_id})
            this.form.item_extra_data= {}
            this.form.item_extra_data.color = null
            this.item = item
            this.supplies = item.supplies

        },


    }
}
</script>
