<template>
    <div class="row" v-loading="loading">
        
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Ruta</th>
                            <th>Convertir a Soles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in records" :key="index">

                            <td>{{ index + 1 }}</td>
                            <td>{{ row.name }}</td>
                            <td>{{ row.route_path }}</td>
                            <td>
                                <el-switch v-model="row.convert_pen"
                                            active-text="Si"
                                            inactive-text="No"
                                            @change="submit(row)"></el-switch>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
 
    </div>
</template>

<script>


export default {
    props: [ 
    ],
    data() {
        return {
            resource: 'report_configurations',
            records: [],
            loading: false,
            errors: {}
        }
    },
    created() {
        this.getData()
    },
    methods: {
        getData() {
            this.$http.get(`/${this.resource}/records`)
                .then(response => {
                    this.records = response.data.data
                })
        },
        submit(row) {

            this.loading = true

            this.$http.post(`/${this.resource}`, row).then(response => {
                let data = response.data

                if (data.success) {
                    this.$message.success(data.message)
                } else {
                    this.$message.error(data.message)
                }

            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors
                } else {
                    console.log(error)
                }
            }).then(() => {
                this.loading = false
            })
        },
    }
}
</script>
