<template>
    <el-dialog :title="titleDialog" :visible="showDialog" width="25%" @close="close" append-to-body top="7vh">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12"> 
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <th class="">Product</th>
                                    <th class="">Cantidad</th>
                                </thead>
                                <tbody>
                                    <tr v-for="item in records" :key="item.key">
                                        <td >{{ item.description }}</td>
                                        <td >{{ item.quantity }}</td>
                                    </tr>
                                </tbody> 
                            </table>
                            <div>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cerrar</el-button>
            </div>
    </el-dialog>
</template>

<script>


    export default {
        props:['showDialog', 'records'],
        components: {
           
        },
        data() {
            return {
                titleDialog: null
            }
        },
        created() {
        }, 
        methods: {
            
            async create() { 
 
            },
            close() {
                this.$emit('update:showDialog', false)
            },
           
           
        }
    }
</script>
