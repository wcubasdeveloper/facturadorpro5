<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Liquidaciones de compras</span></li>
            </ol>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha Emisión</th>
                        <th>Vendedor</th>
                        <th>Número</th>
                        <th>Estado</th>
                        <th class="text-right">T.Inafecto</th>
                        <th class="text-right">T.Exonerado</th>
                        <th class="text-right">T.Gravado</th>
                        <th class="text-right">T.Igv</th>
                        <th class="text-right">Total</th>
                        <th class="text-center">Descargas</th>
                    <tr>
                    <tr slot-scope="{ index, row }" :class="{'text-danger': (row.state_type_id === '11')}">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.supplier_name }} <br/> {{ row.supplier_number }}</td>
                        <td>{{ row.number_full }}<br/>
                        </td>
                        <td>
                            <span class="badge bg-secondary text-white" :class="{
                            'bg-danger': (row.state_type_id === '11'),
                            'bg-warning': (row.state_type_id === '13'),
                            'bg-secondary': (row.state_type_id === '01'),
                            'bg-info': (row.state_type_id === '03'),
                            'bg-success': (row.state_type_id === '05'),
                            'bg-secondary': (row.state_type_id === '07'),
                            'bg-dark': (row.state_type_id === '09')}">
                                {{ row.state_type_description }}
                            </span>
                        </td>
                        <td class="text-right">{{ row.total_unaffected }}</td>
                        <td class="text-right">{{ row.total_exonerated }}</td>
                        <td class="text-right">{{ row.total_taxed }}</td>
                        <td class="text-right">{{ row.total_igv }}</td>
                        <td class="text-right">{{ row.total }}</td>
                        <td class="text-center">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.download_external_xml)">XML</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.download_external_pdf)">PDF</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.download_external_cdr)"
                                    v-if="row.has_cdr">CDR</button>
                        </td>
                    </tr>
                </data-table>
            </div>
        </div>
    </div>
</template>
<script>

    import DataTable from '../../../components/DataTable.vue'

    export default {
        components: {DataTable},
        data() {
            return {
                resource: 'purchase-settlements',
                recordId: null,
            }
        },
        created() {
        },
        methods: {
            clickDownload(download) {
                window.open(download, '_blank');
            },
        }
    }
</script>
