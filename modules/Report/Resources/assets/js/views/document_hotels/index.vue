<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Reporte hoteles</h3>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Nombres y Apellidos</th>
                        <th>T. Documento</th>
                        <th>Número</th>
                        <th>Ocupación</th>
                        <th>Edad</th>
                        <th>E. civil</th>
                        <th>Nacionalidad</th>
                        <th>Procedencia</th>
                        <th>Comprobante</th>
                        <th>Productos</th>

                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.identity_document_type_description }}</td>
                        <td>{{ row.number }}</td>
                        <td>{{ row.ocupation }}</td>
                        <td>{{ row.age }}</td>
                        <td>{{ row.civil_status }}</td>
                        <td>{{ row.nacionality }}</td>
                        <td>{{ row.origin }}</td>
                        <td>{{ row.document }}</td>
                        <td>

                            <template
                                v-if="row.items.length > 0 ">


                                <button
                                    class="btn waves-effect waves-light btn-xs btn-primary"
                                    type="button"
                                    @click.prevent="clickProductsItems(row.items,row.room?row.room.name:'')"

                                >
                                    <i class="fa fa-database"></i>
                                </button>
                            </template>
                        </td>

                    </tr>

                </data-table>


            </div>
        </div>
        <item-modal
            :from_documents="true"
            :item="recordItem"
            :room_name="room_name"
            :showDialog.sync="showItemModal"
        ></item-modal>
    </div>
</template>

<script>
import ItemModal from '../report_hotels/modal_item_info.vue'

import DataTable from '../../components/DataTableDocumentHotel.vue'

export default {
    components: {
        DataTable,
        ItemModal,
    },
    data() {
        return {
            resource: 'reports/document-hotels',
            form: {},
            recordItem: {},
            showItemModal: false,
            room_name: '',

        }
    },
    async created() {
    },
    methods: {


        clickProductsItems(row, room) {
            this.recordItem = row
            this.showItemModal = true
            this.room_name = room
        },
        checkProductDebts(row) {
            let str = '';
            let deb = 0;
            row.forEach(function (a, b) {
                if (a.payment_status !== 'PAID') {
                    deb += a.item.total;
                }
            });
            if (deb != 0) {
                str = `Debe ${deb} en producto(s)`;
            }

            return str;
        }

    }
}
</script>
