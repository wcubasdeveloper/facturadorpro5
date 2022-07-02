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
            v-if="
                            config.show_extra_info_to_item &&
                            (
                                hasTotal ||
                                hasColors ||
                                hasCatItemSize ||
                                hasCatItemStatus ||
                                hasCatItemUnitBusiness ||
                                hasCatItemMoldCavity ||
                                hasCatItemPackageMeasurement ||
                                hasCatItemUnitsPerPackage ||
                                hasCatItemMoldProperty ||
                                hasCatItemProductFamily
                            )"
            class="form-body col-12 row">


            <template v-if="hasTotal !== false">
                <div class="col-12 text-left"><strong>{{ item.stock_by_extra.total }}</strong> Total</div>
            </template>
            <template v-if="hasColors !== false">
                <div class="col-12 text-left"><strong>{{ item.stock_by_extra.colors.total }}</strong> Colores</div>
                <div class="col-3">&nbsp;</div>
                <div class="col-9">
                    <template v-for="detail in item.stock_by_extra.colors.detailed">
                        <template>{{ detail.name }} : {{ detail.total }}</template>
                        <br>
                    </template>
                </div>
            </template>
            <template v-if="hasCatItemSize !== false">
                <div class="col-12 text-left"><strong>{{ item.stock_by_extra.CatItemSize.total }}</strong> Talla /Tamaño
                </div>
                <div class="col-3">&nbsp;</div>
                <div class="col-9">
                    <template v-for="detail in item.stock_by_extra.CatItemSize.detailed">
                        <template>{{ detail.name }} : {{ detail.total }}</template>
                        <br>
                    </template>
                </div>
            </template>
            <template v-if="hasCatItemStatus !== false">
                <div class="col-12 text-left"><strong>{{ item.stock_by_extra.CatItemStatus.total }}</strong> Status
                </div>
                <div class="col-3">&nbsp;</div>
                <div class="col-9">
                    <template v-for="detail in item.stock_by_extra.CatItemStatus.detailed">
                        <template>{{ detail.name }} : {{ detail.total }}</template>
                        <br>
                    </template>
                </div>
            </template>
            <template v-if="hasCatItemUnitBusiness !== false">
                <div class="col-12 text-left"><strong>{{ item.stock_by_extra.CatItemUnitBusiness.total }}</strong>
                    Unidad de negocio
                </div>

                <div class="col-3">&nbsp;</div>
                <div class="col-9">
                    <template v-for="detail in item.stock_by_extra.CatItemUnitBusiness.detailed">
                        <template>{{ detail.name }} : {{ detail.total }}</template>
                        <br>
                    </template>
                </div>
            </template>
            <template v-if="hasCatItemMoldCavity !== false">
                <div class="col-12 text-left"><strong>{{ item.stock_by_extra.CatItemMoldCavity.total }}</strong>
                    Cavidades de molde
                </div>
                <div class="col-3">&nbsp;</div>
                <div class="col-9">
                    <template v-for="detail in item.stock_by_extra.CatItemMoldCavity.detailed">
                        <template>{{ detail.name }} : {{ detail.total }}</template>
                        <br>
                    </template>
                </div>
            </template>
            <template v-if="hasCatItemPackageMeasurement !== false">
                <div class="col-12 text-left"><strong>{{ item.stock_by_extra.CatItemPackageMeasurement.total }}</strong>
                    Unidades de medida del paquete
                </div>
                <div class="col-3">&nbsp;</div>
                <div class="col-9">
                    <template v-for="detail in item.stock_by_extra.CatItemPackageMeasurement.detailed">
                        <template>{{ detail.name }} : {{ detail.total }}</template>
                        <br>
                    </template>
                </div>
            </template>
            <template v-if="hasCatItemUnitsPerPackage !== false">
                <div class="col-12 text-left"><strong>{{ item.stock_by_extra.CatItemUnitsPerPackage.total }}</strong>
                    Unidades por paquete
                </div>
                <div class="col-3">&nbsp;</div>
                <div class="col-9">
                    <template v-for="detail in item.stock_by_extra.CatItemUnitsPerPackage.detailed">
                        <template>{{ detail.name }} : {{ detail.total }}</template>
                        <br>
                    </template>
                </div>
            </template>
            <template v-if="hasCatItemMoldProperty !== false">
                <div class="col-12 text-left"><strong>{{ item.stock_by_extra.CatItemMoldProperty.total }}</strong>
                    Propiedades de molde
                </div>

                <div class="col-3">&nbsp;</div>
                <div class="col-9">
                    <template v-for="detail in item.stock_by_extra.CatItemMoldProperty.detailed">
                        <template>{{ detail.name }} : {{ detail.total }}</template>
                        <br>
                    </template>
                </div>
            </template>
            <template v-if="hasCatItemProductFamily !== false">
                <div class="col-12 text-left"><strong>{{ item.stock_by_extra.CatItemProductFamily.total }}</strong>
                    Familia de producto
                </div>

                <div class="col-3">&nbsp;</div>
                <div class="col-9">
                    <template v-for="detail in item.stock_by_extra.CatItemProductFamily.detailed">
                        <template>{{ detail.name }} : {{ detail.total }}</template>
                        <br>
                    </template>
                </div>
            </template>
            <div class="col-12">
                <br>
                <small>
                    <strong>NOTA:</strong> El total de los atributos se ve reflejado, solo si se hacen movimientos con
                                           el atributo seleccionado
                </small>
            </div>
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
        'item',
        'showDialog',

    ],
    computed: {
        ...mapState([
            'config',
        ]),
        canShowExtraData: function () {
            if (this.config && this.config.show_extra_info_to_item !== undefined) {
                return this.config.show_extra_info_to_item;
            }
            return false;
        },
        hasTotal: function () {
            return this.item.stock_by_extra !== undefined && this.item.stock_by_extra.total !== undefined && this.item.stock_by_extra.total !== null;
        }, hasColors: function () {
            return this.item.stock_by_extra !== undefined && this.item.stock_by_extra.colors !== undefined && this.item.stock_by_extra.colors !== null && this.item.stock_by_extra.colors.total !== null;
        },
        hasCatItemUnitsPerPackage: function () {
            return this.item.stock_by_extra !== undefined && this.item.stock_by_extra.CatItemUnitsPerPackage !== undefined && this.item.stock_by_extra.CatItemUnitsPerPackage !== null && this.item.stock_by_extra.CatItemUnitsPerPackage.total !== null;
        },
        hasCatItemMoldProperty: function () {
            return this.item.stock_by_extra !== undefined && this.item.stock_by_extra.CatItemMoldProperty !== undefined && this.item.stock_by_extra.CatItemMoldProperty !== null && this.item.stock_by_extra.CatItemMoldProperty.total !== null;
        },
        hasCatItemUnitBusiness: function () {
            return this.item.stock_by_extra !== undefined && this.item.stock_by_extra.CatItemUnitBusiness !== undefined && this.item.stock_by_extra.CatItemUnitBusiness !== null && this.item.stock_by_extra.CatItemUnitBusiness.total !== null;
        },
        hasCatItemStatus: function () {
            return this.item.stock_by_extra !== undefined && this.item.stock_by_extra.CatItemStatus !== undefined && this.item.stock_by_extra.CatItemStatus !== null && this.item.stock_by_extra.CatItemStatus.total !== null;
        },
        hasCatItemPackageMeasurement: function () {
            return this.item.stock_by_extra !== undefined && this.item.stock_by_extra.CatItemPackageMeasurement !== undefined && this.item.stock_by_extra.CatItemPackageMeasurement !== null && this.item.stock_by_extra.CatItemPackageMeasurement.total !== null;
        },
        hasCatItemMoldCavity: function () {
            return this.item.stock_by_extra !== undefined && this.item.stock_by_extra.CatItemMoldCavity !== undefined && this.item.stock_by_extra.CatItemMoldCavity !== null && this.item.stock_by_extra.CatItemMoldCavity.total !== null;
        },
        hasCatItemProductFamily: function () {
            return this.item.stock_by_extra !== undefined && this.item.stock_by_extra.CatItemProductFamily !== undefined && this.item.stock_by_extra.CatItemProductFamily !== null && this.item.stock_by_extra.CatItemProductFamily.total !== null;
        },
        hasCatItemSize: function () {
            return this.item.stock_by_extra !== undefined && this.item.stock_by_extra.CatItemSize !== undefined && this.item.stock_by_extra.CatItemSize !== null && this.item.stock_by_extra.CatItemSize.total !== null;
        },
    },
    data() {
        return {
            titleDialog: 'Stock para item',
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

        close() {
            this.$emit('update:showDialog', false)
        },
        create() {
            this.titleDialog = '';
            if (this.item !== undefined) {
                this.titleDialog = `Stock para item: ${this.item.description}`;
            }
        },

    }
}
</script>
