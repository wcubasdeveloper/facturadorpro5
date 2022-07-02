<template>
    <el-dialog   :title="titleDialog" :visible="showDialog" :close-on-click-modal="true" @close="close" @open="create" append-to-body top="7vh" width="30%">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">    
                        <div class="form-group">
                            <el-select v-model="selected_price"  ref="ref_select_price" @keyup.enter.native="enterSelectPrice()" filterable>
                                <el-option v-for="option in itemUnitTypes" :key="option.id" :label="getDescription(option) "
                                            :value="option.id"></el-option>
                            </el-select> 
                        </div>
 
                    </div> 
                </div>   
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cerrar</el-button>
            </div>
        </form>
    </el-dialog>
</template> 
<script>

    export default {
        props: ['showDialog', 'itemUnitTypes'],
        data() {
            return {
                selected_price: null,  
                titleDialog: 'Seleccionar precio',
            }
        },
        async created() {
             
        },
        methods: {    
            async enterSelectPrice(){

                let unit_type = await _.find(this.itemUnitTypes, {id : this.selected_price})

                if(unit_type){
                    this.$eventHub.$emit("enterSelectItemUnitType", (unit_type))
                    this.close()
                }else{
                    this.$message.warning('No ha seleccionado un precio');
                }
                // console.log(unit_type.description)


            },
            getDescription(option){
                 
                let price = 0

                switch (option.price_default) {
                    case 1:
                        price = option.price1
                        break;
                    case 2:
                        price = option.price2
                        break;
                    case 3:
                        price = option.price3
                        break; 
                }

                return `Precio: ${price} - Unidad: ${option.unit_type_id}`
            },  
            create() {
                // console.log(this.itemUnitTypes)
                
                this.$nextTick(() => {
                    this.initFocus()
                }); 

                // if(this.itemUnitTypes.length > 0){
                //     this.selected_price = this.itemUnitTypes[0].id
                // }
            },  
            initFocus() {
                this.$refs.ref_select_price.$el.getElementsByTagName("input")[0].focus();
            },
            close() {
                this.selected_price = null
                this.$emit('update:showDialog', false)
            }, 
        }
    }
</script>