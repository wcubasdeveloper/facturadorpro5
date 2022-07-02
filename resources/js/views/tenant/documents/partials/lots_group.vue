<template>
    <el-dialog
        :title="titleDialog"
        width="40%"
        :visible="showDialog"
        @open="create"
        :close-on-click-modal="false"
        :close-on-press-escape="false"
        append-to-body
        :show-close="false"
    >
        <div class="form-body">
            <div class="row">
                <div class="col-md-6">
                    <span>Si al seleccionar lotes la cantidad es mayor, el último lote quedara con la diferencia. </span>
                </div>
                <div class="col-md-6 text-right">
                    <h5>Cant. Pedida: {{quantity}}</h5>
                    <h5 v-bind:class="{ 'text-danger': (toAttend < 0) }">Por Atender: {{toAttend}}</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 pb-2">
                    <el-input placeholder="Buscar código ..."
                        v-model="search"
                        style="width: 100%;"
                        prefix-icon="el-icon-search"
                        @input="filter">
                    </el-input>
                </div>

                <div class="col-lg-12 col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <!--<th>Seleccionado</th>-->
                                <th width="145">Comprometer</th>
                                <th>codigo</th>
                                <th>Cantidad</th>
                                <th>Fecha vencimiento  <el-button icon="el-icon-d-caret" @click="orderData()" plain></el-button> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(row, index) in lotsGroupOrdered"
                                :key="index"
                                v-show="row.quantity > 0"
                            >
                                <!--<th align="center">
                                    <el-checkbox
                                        :disabled="quantityCompleted && row.checked == false"
                                        v-model="row.checked"
                                        @change="changeSelect($event, index)"
                                    ></el-checkbox>
                                </th>-->
                                <th>
                                    <el-input-number v-model="row.compromise_quantity" v-bind:class="{ 'text-danger': (row.compromise_quantity > row.quantity) }" ></el-input-number>
                                </th>
                                <th>{{ row.code }}</th>
                                <th class>{{ row.quantity }}</th>
                                <th class>{{ row.date_of_due }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="form-actions text-right pt-2">
            <el-button @click.prevent="close()">Cerrar</el-button>
            <el-button type="primary" @click="submit">Guardar</el-button>
        </div>
    </el-dialog>
</template>

<script>
export default {
    props: ["showDialog", "lots_group", "stock", "recordId", "quantity"],
    data() {
        return {
            titleDialog: "Lotes",
            loading: false,
            errors: {},
            form: {},
            states: ["Activo", "Inactivo", "Desactivado", "Voz", "M2m"],
            idSelected: null,
            search: '',
            lots_group_: [],
            orderT: 'desc'
        };
    },
    async created() {
      
    },
    computed: {
        lotsGroupOrdered() {
            return _.orderBy(this.lots_group_, 'date_of_due', this.orderT)
        },
        quantityCompleted(){
            return this.lots_group_.filter(x => x.checked == true).reduce((accum,item) => accum + Number(item.quantity), 0) >= this.quantity 
        },
        toAttend() {
            return this.quantity - this.lots_group_.filter(x => x.compromise_quantity > 0).reduce((accum,item) => accum + Number(item.compromise_quantity), 0)
        }
    },
    methods: {
        orderData() {
            this.orderT =  this.orderT == 'desc' ? 'asc' : 'desc'
        },
        filter(){

            if(this.search)
            {
                this.lots_group_ = _.filter(this.lots_group, x => x.code.toUpperCase().includes(this.search.toUpperCase()))
            }
            else{
                this.lots_group_ = this.lots_group
            }
        },
        changeSelect(event, index) {
            /*if(this.quantityCompleted) {
                this.$message.warning('La cantidad está completada');
            }*/

            this.lots_group_[index].checked = event
            this.idSelected = this.lots_group_.filter(x => x.checked == true).map( x => x.id);

            let sum = this.lots_group_.filter(x => x.checked == true).reduce((accum,item) => accum + Number(item.quantity), 0)

            if (sum >= this.quantity) {
                this.$message.warning('La cantidad está completada.');
            }

        },
        handleSelectionChange(val) {
            //this.$refs.multipleTable.clearSelection();
            let row = val[val.length - 1];
            this.multipleSelection = [row];
        },
        create() {
          this.filter()
        },

        async submit() {
            
            //validar cantidad comprometida igual a cantidad pedida
            let compromise_quantity = this.lots_group_.filter(x => x.compromise_quantity > 0).reduce((accum,item) => accum + Number(item.compromise_quantity), 0)
            if (compromise_quantity != this.quantity) {
                return this.$message.warning('La suma de cantidades comprometidas de los lotes debe der igual a la cantidad pedida.');
            }

            //validar cantridad comprometer en lote
            const successValid = this.lots_group_.filter(x => x.compromise_quantity > 0).filter( x => x.compromise_quantity > x.quantity )
            if(successValid.length) {
                return this.$message.warning('La cantidades comprometida de un lote no debe sobrepasar su capacidad.');
            }

            const lots_selecteds = this.lots_group_.filter(x => x.compromise_quantity > 0).map(item => {
                return {
                    id: item.id,
                    code: item.code,
                    compromise_quantity: item.compromise_quantity,
                    date_of_due: item.date_of_due,
                }
            })

            await this.$emit("addRowLotGroup", lots_selecteds);
            await this.$emit("update:showDialog", false);
        },

        clickCancel(item) {
            //this.lots.splice(index, 1);
            item.deleted = true;
            this.$emit("addRowLotGroup", this.lots);
        },

        async clickCancelSubmit() {
            this.$emit("addRowLotGroup", []);
            await this.$emit("update:showDialog", false);
        },
        close() {
            this.$emit("update:showDialog", false);
        },
    },
};
</script>
