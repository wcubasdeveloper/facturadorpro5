<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" width="70%" :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false" append-to-body>
        <div v-loading="loading">
            <div class="row mb-4">
                <div class="col-md-12">
                    <template></template>
                    <el-alert
                        :title="data.message"
                        type="success"
                        show-icon>
                    </el-alert>
                </div>
            </div> 
            <div class="row">
                <div class="col-md-12"> 
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Comprobante</th>
                                    <th>Descripción</th>
                                    <th class="text-center">Estado sistema</th>
                                    <th class="text-center">Estado Sunat</th>
                                    <th class="text-center">¿Estado actualizado?</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, index) in data.data" :key="index">
                                    <td>{{ row.number_full }}</td>
                                    <td>{{ row.description }}</td>
                                    <td class="text-center">{{ row.initial_state }}</td>
                                    <td class="text-center">{{ row.final_state }}</td>
                                    <td class="text-center">{{ row.updated ? 'SI' : 'NO' }}</td>
                                </tr>
                            </tbody>
                        </table> 
                    </div>
                </div> 
            </div>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button @click="clickClose">Cerrar</el-button>
        </span>
    </el-dialog>
</template>

<script>

    export default {
        props: ['showDialog', 'data'],
        data() {
            return {
                titleDialog: null,
                loading: false,
                resource: 'documents',
                form: {},
            }
        },
        created() {
        },
        mounted(){
        },
        methods: {
            create() {

            },
            clickClose() {
                this.$emit('update:showDialog', false)
            },
        }
    }
</script>
