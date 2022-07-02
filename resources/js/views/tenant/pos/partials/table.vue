<template>
    <div>
        <Keypress key-event="keyup" :key-code="40" @success="handle40" />
        <Keypress key-event="keyup" :key-code="38" @success="handle38" />
        <Keypress key-event="keyup" :key-code="13" @success="handle13" />
        <Keypress key-event="keyup" :key-code="113" @success="openTableListPrices113" />

        <el-table
            ref="singleTable"
            :data="records"
            highlight-current-row
            :cell-style="changeColor"
            :header-cell-class-name="headerFont"
            :row-class-name="boldFont"
            @current-change="handleCurrentChange"
            @cell-mouse-enter="enterChangeColor"
            @cell-mouse-leave="leaveChangeColor"
            style="width: 100%"
        >
            <el-table-column type="index" width="50"> </el-table-column>
            <el-table-column property="description" label="Nombre" width="180">
            </el-table-column>
            <el-table-column property="internal_id" label="Código" width="120">
            </el-table-column>
            <el-table-column property="brand" label="Marca" width="120">
                <!-- <template slot-scope="{ row }">
                    {{ row }}
                </template> -->
            </el-table-column>
            <!-- <el-table-column property="currency_type_id" label="Moneda" width="80">
                </el-table-column> -->
            <el-table-column label="Precio" width="100">
                <template slot-scope="{ row }">
                    {{ row.currency_type_symbol }} {{ row.sale_unit_price }}
                </template>
            </el-table-column>

            <el-table-column label="Pack" width="120">
                <template slot-scope="{ row }">
                    <br />
                    <small> {{ row.sets.join("-") }} </small>
                </template>
            </el-table-column>
            <el-table-column label="Stock">
                <template slot-scope="{ row }">
                    <!-- <button type="button" class="btn btn-xs btn-primary-pos" @click="clickWarehouseDetail(row)">
                            <i class="fa fa-search"></i>
                        </button> -->
                    <div v-if="config.product_only_location == true">
                        {{ row.stock }}
                    </div>
                    <div v-else>
                        <template
                            v-if="
                                typeUser == 'seller' && row.unit_type_id != 'ZZ'
                            "
                            >{{ row.stock }}</template
                        >
                        <template
                            v-else-if="
                                typeUser != 'seller' && row.unit_type_id != 'ZZ'
                            "
                        >
                            <button
                                type="button"
                                class="btn waves-effect waves-light btn-xs btn-info"
                                @click.prevent="clickWarehouseDetail(row)"
                            >
                                <i class="fa fa-search"></i>
                            </button>
                        </template>
                    </div>
                </template>
            </el-table-column>

            <el-table-column label="Lista precios" width="120">
                <template slot-scope="{ row }"> 
                    <template v-if="row.unit_type.length > 0">
                        <el-popover
                            placement="top"
                            title="Precios"
                            width="280"
                            trigger="click"
                        >
                            <el-table
                                v-if="row.unit_type"
                                :data="row.unit_type"
                            >
                                <el-table-column
                                    width="90"
                                    label="Precio"
                                >
                                    <template
                                        slot-scope="{
                                            row
                                        }"
                                    >
                                        <span
                                            v-if="row.price_default ==1 "
                                        >
                                            {{
                                                row.price1
                                            }}
                                        </span>
                                        <span
                                            v-else-if="row.price_default ==2"
                                        >
                                            {{
                                                row.price2
                                            }}
                                        </span>
                                        <span
                                            v-else-if="row.price_default ==3"
                                        >
                                            {{
                                                row.price3
                                            }}
                                        </span>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                    width="80"
                                    label="Unidad"
                                    property="unit_type_id"
                                ></el-table-column>
                                <el-table-column
                                    width="80"
                                    label=""
                                >
                                    <template
                                        slot-scope="{
                                            row
                                        }"
                                    >
                                        <button
                                            @click="
                                                setPriceItem(
                                                    row
                                                )
                                            "
                                            type="button"
                                            class="btn btn-custom btn-xs"
                                        >
                                            <i
                                                class="fas fa-check"
                                            ></i>
                                        </button>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <button
                                slot="reference"
                                type="button"
                                style="width:100%" 
                                class="btn btn-xs btn-primary-pos"
                            >
                                <i
                                    class="fa fa-money-bill-alt"
                                ></i>
                            </button>
                        </el-popover> 
                    </template>
                </template>
            </el-table-column>


            <el-table-column label="Historial ventas">
                <template slot-scope="{ row }">
                    <button
                        type="button"
                        class="btn btn-xs btn-primary-pos"
                        @click="clickHistorySales(row.item_id)"
                    >
                        <i class="fa fa-list"></i>
                    </button>
                </template>
            </el-table-column>

            <!-- <el-table-column label="Historial compras">
                    <template slot-scope="{row}">
                        <button type="button" class="btn btn-xs btn-primary-pos" @click="clickHistoryPurchases(row.item_id)"><i class="fas fa-cart-plus"></i></button>
                    </template>
                </el-table-column> -->
        </el-table>
        <item-unit-types-table
            :showDialog.sync="showDialogItemUnitTypes"
            :itemUnitTypes="itemUnitTypes"
        >
        </item-unit-types-table>
    </div>
</template>

<script>
import Keypress from "vue-keypress";
import ItemUnitTypesTable from "./item_unit_types_table.vue";

export default {
    components: { Keypress, ItemUnitTypesTable },
    props: {
        typeUser: String,
        records: {
            type: Array,
            default: [],
            required: false
        },
        visibleTagsCustomer: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            currentIndex: 0,
            showDialogItemUnitTypes: false,
            currentRow: null,
            selectedIndex:null,
            hoverIndex:null,
            changeMouse:false,
            itemUnitTypes: [],
            config: {}
        };
    },
    created() {
        this.$http.get(`/configurations/record`).then(response => {
            this.config = response.data.data;
        });

        this.events()

    },
    methods: {
        events(){
            
            this.$eventHub.$on("selectedItemUnitTypeTable", (unit_type) => {
                this.setPriceItem(unit_type)
            })

        },
        setPriceItem(price) {

            let value = 0;
            switch (price.price_default) {
                case 1:
                    value = price.price1;
                    break;
                case 2:
                    value = price.price2;
                    break;
                case 3:
                    value = price.price3;
                    break;
            }

            if (this.records.length == 1) {

                this.records[0].sale_unit_price = value;
                this.records[0].unit_type_id = price.unit_type_id;
                this.records[0].presentation = price;

            } else {

                if (this.currentRow) {

                    this.currentRow.sale_unit_price = value;
                    this.currentRow.unit_type_id = price.unit_type_id;
                    this.currentRow.presentation = price;
                }
            }
            
            this.$message.success("Precio seleccionado");
            
        },
        openTableListPrices113(){
            
            if(this.config.select_available_price_list){

                if (this.records.length == 1) {
                    // console.log(this.records[0].description)

                    if(this.records[0].unit_type.length > 0){

                        this.itemUnitTypes = this.records[0].unit_type
                        this.showDialogItemUnitTypes = true
                    
                    }


                } else {

                    if (this.currentRow) {

                        if(this.currentRow.unit_type.length > 0){

                            this.itemUnitTypes = this.currentRow.unit_type
                            this.showDialogItemUnitTypes = true
                            // console.log(this.currentRow.description)

                        }

                    }
                }
            }

        },
        handle13() {
            if (this.visibleTagsCustomer) {
                return false;
            }

            if (this.records.length == 1) {
                this.$emit("clickAddItem", this.records[0]);
            } else {
                if (this.currentRow) {
                    this.$emit("clickAddItem", this.currentRow);
                }
            }
        },
        handle40() {
            if (this.visibleTagsCustomer) {
                return;
            }
            this.currentIndex += 1;

            if (this.records[this.currentIndex]) {
                this.setCurrent(this.records[this.currentIndex]);
            } else {
                this.currentIndex = 0;
                this.setCurrent(this.records[0]);
            }
        },
        handle38() {
            if (this.visibleTagsCustomer) {
                return;
            }

            if (this.currentIndex == 0) {
                return;
            }
            this.currentIndex -= 1;
            this.setCurrent(this.records[this.currentIndex]);
        },
        setCurrent(row) {
            this.$refs.singleTable.setCurrentRow(row);
        },
        handleCurrentChange(val) {
            this.currentRow = val;
            this.selectedIndex = val.index;
        },
        clickWarehouseDetail(id) {
            this.$emit("clickWarehouseDetail", id);
        },
        clickHistorySales(id) {
            this.$emit("clickHistorySales", id);
        },
        clickHistoryPurchases(id) {
            this.$emit("clickHistoryPurchases", id);
        },
        reset() {
            this.currentIndex = 0;
            this.setCurrent(this.records[this.currentIndex]);
        },
        changeColor({row, rowIndex}){
            if((this.selectedIndex) === rowIndex){
                if(this.selectedIndex === this.hoverIndex){
                    return {"background-color": "#ACE1F6"}
                }
                return {"background-color": "#A9E6FF"}
            }
            if(this.changeMouse){
                if((this.hoverIndex) === rowIndex){
                    return {"background-color": "#ACE1F6"}
                }
            }else{
                return {"background-color": "#ffff"}
            }
        },
        enterChangeColor(val){
            this.hoverIndex=val.index;
            this.changeMouse=true;
        },
        leaveChangeColor({row, column, cell, event}){
            this.changeMouse=false;
        },
        boldFont({row, rowIndex}){
            row.index=rowIndex;
            return 'font-weight-semibold';
        },
        headerFont(){
            return 'font-weight-semibold';
        }

    }
};
</script>

<style></style>
