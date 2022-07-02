<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard">
                    <i class="fas fa-tachometer-alt">
                    </i>
                </a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>
                        {{ typeText }}
                    </span>
                </li>
            </ol>
            <div class="right-wrapper pull-right">
                <button class="btn btn-custom btn-sm  mt-2 mr-2"
                        type="button"
                        @click.prevent="clickCreate()">
                    <i class="fa fa-plus-circle">
                    </i>
                    Nuevo
                </button>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">
                    Listado de Clientes
                </h3>
            </div>
            <div class="card-body">
                <data-table
                    :extraquery={users:type}>
                    <tr slot="heading">
                        <th>
                            #
                        </th>
                        <th class="text-left"

                        >
                            Canal
                        </th>
                        <th class="text-left">
                            Nombre
                        </th>
                        <th class="text-left">
                            Documento
                        </th>
                        <th class="text-right">
                            Número
                        </th>
                        <th class="text-right">
                            Teléfono
                        </th>
                        <th class="text-right">
                            Correo
                        </th>
                        <th class="text-right">
                            Acciones
                        </th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>
                            {{ index }}
                        </td>
                        <td class="text-left"

                        >

                            {{ row.discord_channel }}
                        </td>
                        <td class="text-left">

                            {{ row.name }}
                        </td>
                        <td class="text-left">
                            {{ row.document_type }}
                        </td>
                        <td class="text-right">
                            {{ row.number }}
                        </td>
                        <td class="text-right">
                            {{ row.telephone }}
                        </td>
                        <td class="text-right">
                            {{ row.email }}
                        </td>
                        <td class="text-right">
                            <button
                                class="btn waves-effect waves-light btn-xs btn-info"
                                type="button"
                                @click.prevent="clickCreate(getRowId(row))">
                                Editar
                            </button>
                            <!--
                            <button
                                class="btn waves-effect waves-light btn-xs btn-danger"
                                type="button"
                                @click.prevent="clickDelete(row.id)">
                                Eliminar
                            </button>
                            -->
                        </td>
                    </tr>
                </data-table>
            </div>

            <customers-form
                :recordId="recordId"
                :showDialog.sync="showDialog">
            </customers-form>
        </div>
    </div>
</template>

<script>
import {mapActions, mapState} from "vuex/dist/vuex.mjs";

import CustomersForm from './form.vue'
import DataTable from '../components/SuscriptionsDataTable.vue'
import {deletable} from '../../../../../../resources/js/mixins/deletable'

export default {
    props: [
        'configurations',
        'listtype',
    ],
    mixins: [
        deletable
    ],
    components: {
        CustomersForm,
        DataTable
    },
    data() {
        return {
            showDialog: false,
            recordId: null,
            type: null,
            typeText: 'Clientes',
        }
    },
    computed: {
        ...mapState([
            'config',
            'resource',
            /*
            'countries',
            'all_departments',
            'all_provinces',
            'all_districts',
            'identity_document_types',
            'locations',
            */
        ]),
    },
    created() {
        this.loadConfiguration()
        this.$store.commit('setConfiguration', this.configuration)
        this.$store.commit('setResource', 'client')

        this.getCommonData()
        // Clientes

        if (this.listtype !== undefined) {
            this.type = this.listtype
            if (this.type == 'parent') {
                this.typeText = 'Padres';
            } else if (this.type == 'children') {
                this.typeText = 'Hijos';
            }
        }
        // this.getPersonData()

    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        getRowId(row) {
            // Si es un hijo, mostraria el modal del padre
            if (row.parent_id > 0) {
                return row.parent_id
            }
            return row.id
        },
        clickCreate(recordId = null) {
            this.recordId = recordId
            this.showDialog = true
        },
        clickDelete(id) {
            this.destroy(`/${this.resource}/${id}`).then(() =>
                this.$eventHub.$emit('reloadData')
            )
        },
        getCommonData() {
            this.$http.post('CommonData', {})
                .then((response) => {
                    this.$store.commit('setCurrencyTypes', response.data.currency_types)
                    this.$store.commit('setAffectationIgvTypes', response.data.affectation_igv_types)
                    this.$store.commit('setUnitTypes', response.data.unit_types)
                    this.$store.commit('setPaymentMethodTypes', response.data.payments_credit)
                })
        },

        getPersonData() {
            this.$http.post(`/full_suscription/${this.resource}/tables`)
                .then(response => {
                    this.api_service_token = response.data.api_service_token
                    // console.log(this.api_service_token) setConfiguration

                    this.$store.commit('setConfiguration', response.data.configuration);
                    this.$store.commit('setCountries', response.data.countrie);
                    this.$store.commit('setAllDepartments', response.data.departments);
                    this.$store.commit('setAllProvinces', response.data.provinces);
                    this.$store.commit('setAllDistricts', response.data.districts);
                    this.$store.commit('setIdentityDocumentTypes', response.data.identity_document_types);
                    this.$store.commit('setLocations', response.data.locations);
                    this.$store.commit('setPersonTypes', response.data.person_types);

                    this.loadConfiguration()
                })
        },

    }
}
</script>
