<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Lista de informacion adicional</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <!--
                <a :href="`./extra_info_items/${resource}/create`"
                   class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>

                <el-button :loading="loading_submit"
                           type="primary"
                           @click.prevent="NewColor()">Nuevo
                </el-button>
                -->


            </div>

        </div>
        <div class="col-6 col-md-4 mb-4">
            <div class="card card-dashboard card-reports">
                <div class="card-body">
                    <h6 class="card-title">Informacion adicion para items
                        <el-tooltip
                            class="item"
                            effect="dark"
                            content="No serán utilizados en calculo de SUNAT."
                            placement="top-start"
                        >
                            <i class="fa fa-info-circle"></i>
                        </el-tooltip>
                    </h6>
                    <ul class="card-report-links">
                        <li>
                            <a :href="`./extra_info_items/item-size`">Tallas/Tamaño</a>
                        </li>
                        <li>
                            <a :href="`./extra_info_items/colors`">Lista de colores</a>
                        </li>
                        <li>
                            <a :href="`./extra_info_items/item-units-per-package`">Lista de unidades por paquete</a>
                        </li>
                        <li>
                            <a :href="`./extra_info_items/item-units-business`">Lista de unidades de negocio</a>
                        </li>

                        <li>
                            <a :href="`./extra_info_items/item-status`">Lista de status</a>
                        </li>

                        <li>
                            <a :href="`./extra_info_items/item-package-measurements`">Lista de unidades de medida</a>
                        </li>

                        <li>
                            <a :href="`./extra_info_items/item-mold-cavities`">Lista de cavidades de molde</a>
                        </li>
                        <li>
                            <a :href="`./extra_info_items/item-mold-property`">Propiedades de molde</a>
                        </li>
                        <li>
                            <a :href="`./extra_info_items/item-prodcut-family`">Lista de familia de producto</a>
                        </li>


                    </ul>
                </div>
            </div>
        </div>

        <!--
        <div class="card mb-0">

            <div class="card-header bg-info">
                <h3 class="my-0">
                    Lista de informacion adicional
                </h3>

            </div>

            <div class="card-body">




            </div>
        </div>
        -->
    </div>
</template>
<style scoped>
.anulate_color {
    color: red;
}
</style>
<script>

import {mapActions, mapState} from "vuex/dist/vuex.mjs";

export default {
    props: [
        'configuration',
    ],
    mixins: [],
    components: {},
    computed: {
        ...mapState([
            'config',
        ]),
    },
    data() {
        return {}
    },
    created() {
        this.loadConfiguration()
        this.$store.commit('setConfiguration', this.configuration)

    },
    methods: {
        ...mapActions(['loadConfiguration']),
    }
}
</script>
