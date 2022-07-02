<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Guias de remisión</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <a :href="`/${resource}/create`" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
                <a href="#" @click.prevent="showModalGenerateCPE = true" class="btn btn-custom btn-sm  mt-2 mr-2">Generar comprobante desde múltiples guías</a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha Emisión</th>
                        <th>Cliente</th>
                        <th>Número</th>
                        <th>Estado</th>
                        <th class="text-center">Fecha Envío</th>
                        <th class="text-center">N° Comprobante</th>
                        <th class="text-center">Descargas</th>
                        <th class="text-center">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" :class="{'text-danger': (row.state_type_id === '11')}">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.customer_name }} <br /> <small>{{ row.customer_number }}</small></td>
                        <td>{{ row.number }}</td>
                        <td>
                            <span class="badge bg-secondary text-white" :class="{'bg-secondary': (row.state_type_id === '01'), 'bg-info': (row.state_type_id === '03'), 'bg-success': (row.state_type_id === '05'), 'bg-secondary': (row.state_type_id === '07'), 'bg-dark': (row.state_type_id === '09')}">{{row.state_type_description}}</span>
                        </td>
                        <td class="text-center">{{ row.date_of_shipping }}</td>

                        <td class="text-center">
                            <template v-for="(row,index) in row.documents">
                                <label class="d-block" :key="index">{{ row.description }}</label>
                            </template>
                        </td>

                        <td class="text-center">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickDownload(row.download_external_xml)">XML</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickDownload(row.download_external_pdf)">PDF</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickDownload(row.download_external_cdr)" v-if="row.has_cdr">CDR</button>
                        </td>
                        <td class="text-center">
                            <button v-if="row.btn_generate_document" type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                @click.prevent="onGenerateDocument(row.id)">Generar comprobante</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickOptions(row.id)">Opciones</button>
                            <button v-if="showSentSunat(row)" type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="sendSunat(row.id)">Enviar a Sunat</button>
                        </td>
                    </tr>
                </data-table>
            </div>
        </div>
            <dispatch-options :showDialog.sync="showDialogOptions"
                              :recordId="recordId"
                              :showClose="true"></dispatch-options>

        <FormGenerateDocument
            :showDialog.sync="showDialogGenerateDocument"
            :recordId="recordId"
            :showClose="true"
            :showGenerate="true"
            :configuration="configuration"
        ></FormGenerateDocument>
        <ModalGenerateCPE :show.sync="showModalGenerateCPE"></ModalGenerateCPE>
    </div>
</template>

<script>
import DataTable from '../../../components/DataTableDispatch.vue'
// import DataTable from '../../../components/DataTable.vue'
import DispatchOptions from './partials/options.vue'
import FormGenerateDocument from "./generate-document";
import ModalGenerateCPE from './ModalGenerateCPE';

export default {
        components: {DataTable, DispatchOptions, FormGenerateDocument, ModalGenerateCPE},
        props:['configuration'],
        data() {
            return {
                resource: 'dispatches',
                showDialogOptions: false,
                recordId: null,
                showDialogGenerateDocument: false,
                showModalGenerateCPE: false,
            }
        },
        created(){
            this.$setStorage('configuration',this.configuration)
        },
        methods: {
            showSentSunat(row){
                let data = row.soap_shipping_response;
                if(this.configuration.auto_send_dispatchs_to_sunat === true) return false;
                if(data === undefined || data === null) return true;
                if(data.sent === null || data.sent === false) return true;
                return false;
            },
            sendSunat(id){
                this.$http.post(`/dispatches/sendSunat/${id}`)
                    .then((result)=>{
                        let data = result.data;
                        if(data.sent === false){
                            this.$notify.error({
                                title: 'Envio no realizado',
                                message: data.description,
                            });
                        }else{
                            this.$notify.success({
                                title: 'Se ha realizado el envio',
                                message: data.description,
                            });
                        }
                    }).catch(()=>{
                    this.$notify.success({
                        title: 'Error',
                        message: 'Error desconocido',
                    });
                })
            },
            onGenerateDocument(dispatchId) {
                this.recordId = dispatchId;
                this.showDialogGenerateDocument = true;
            },
            clickOptions(recordId = null) {
                this.recordId = recordId
                this.showDialogOptions = true
            },
            clickDownload(download) {
                window.open(download, '_blank');
            },
            clickPrint(external_id){
                window.open(`/print/dispatch/${external_id}/a4`, '_blank');
            },
        }
    }
</script>
