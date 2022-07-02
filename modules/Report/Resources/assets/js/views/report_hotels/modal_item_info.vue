<template>
    <el-dialog
        :append-to-body="true"
        :title="titleDialog"
        :visible="showDialog"
        center
        width="30%"
        @close="close"
        @open="create">
        <div
            class="form-body col-12 row table-responsive">
            <table v-if="getLeng(item)>0" class="table">
                <thead>
                <tr>
                    <th>Nombre de producto</th>
                    <th v-if="from_documents === false">Estado de pago</th>
                    <th>
                        Total
                        <!--
                        <a
                            class="text-center font-weight-bold text-center text-info"
                            href="#"
                            style="font-size:18px"
                            @click.prevent="clickAddFee"
                        >[+]</a>
                        -->
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(row, index) in item"
                    :key="index">
                    <td>{{ getDescription(row) }}</td>
                    <td v-if="from_documents === false">{{ getPaymentStatus(row)  }}</td>
                    <td>{{ getTotal(row) }}</td>
                </tr>
                </tbody>
            </table>


        </div>
        <div class="form-actions text-right pt-2">
            <el-button @click.prevent="close()">Cerrar</el-button>
        </div>
    </el-dialog>
</template>
<script>
import {mapActions, mapState} from "vuex";

export default {
    props: [
        'from_documents',
        'item',
        'room_name',
        'showDialog',
    ],
    computed: {
        ...mapState([
            'config',
        ]),
    },
    data() {
        return {
            titleDialog: '-',
        }
    },
    created() {
        this.loadConfiguration()

    },
    mounted() {

    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        getLeng(item) {
            let total = 0;
            if (item !== undefined && item.length > 0) {
                total = item.length
            }
            return total;
        },

        getTotal(row) {
            let str = '';

            if (row.item && row.item.total) {
                str = row.item.total
            }else if (row.total){
                str = row.total

            }
            return str;
        },
        getPaymentStatus(row) {
            let str = '';
            if (row.payment_status) {
                str = row.payment_status === "PAID" ? "Pagado" : "Debe"
            }
            return str;
        },
        getDescription(row) {
            let str = '';
            if (row.item && row.item.item && row.item.item.description) {
                str = row.item.item.description;
            }else if (row.item && row.item && row.item.description) {
                str = row.item.description;
            }
            return str;
        },
        close() {
            this.$emit('update:showDialog', false)
        },
        create() {
            this.titleDialog = '';
            if (this.item !== undefined) {
                this.titleDialog = `Productos de Habitación: ${this.room_name}`;
            }
        },

    }
}
</script>
