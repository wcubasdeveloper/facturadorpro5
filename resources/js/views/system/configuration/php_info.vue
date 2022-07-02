<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Datos de PHP</h3>
        </div>
        <div class="card-body">
            <div class="form-body">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <th>
                                    Indicador
                                </th>
                                <th>
                                    Valor
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    Memoria en bytes
                                </td>
                                <td>
                                    <strong>{{memory_bytes}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Memoria en el archivo
                                </td>
                                <td>
                                    <strong>{{memory_write}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    pcre.backtrack_limit
                                </td>
                                <td>
                                    <strong>{{backtrack_limit}}</strong>
                                </td>
                            </tr>
                            <tr v-for="(row, index)  in all_config" :key="index">
                                <td>
                                    {{index}}
                                </td>
                                <td>
                                    <strong>{{row}}</strong>
                                </td>
                            </tr>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: [
        'memory_bytes',
        'memory_write',
        'backtrack_limit',
        'all_config'
    ],
    data() {
        return {};
    },
    created() {
        // console.error(this.all_config)
    },
    methods: {
    }
};
</script>

