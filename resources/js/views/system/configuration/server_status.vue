<template>
    <div class="card">
        <div class="card-body">
            <p class="mb-0">CPU %</p>
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="chart-data-selector ready">
                        <div class="chart-data-selector-items">
                            <chart-line :data="dataCpu" v-if="loaded"></chart-line>
                        </div>
                    </div>
                </div>
            </div>
            <p class="mb-0">RAM GB</p>
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="chart-data-selector ready">
                        <div class="chart-data-selector-items">
                            <chart-line :data="dataMemory" v-if="loaded"></chart-line>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import ChartLine from "./charts/Line";

export default {
    components: { ChartLine },
    data() {
        return {
            loaded: false,
            dataCpu: {
                labels: null,
                datasets: [
                {
                    // label: 'Data One',
                    // backgroundColor: '#f87979',
                    data: null
                }
                ]
            },
            dataMemory: {
                labels: null,
                datasets: [
                {
                    // label: 'Data One',
                    // backgroundColor: '#f87979',
                    data: null
                }
                ]
            }
        };
    },
    async mounted() {
        this.loaded = false;
        await this.$http.get(`/status/history`).then(response => {
            let line = response.data;
            this.dataCpu.labels = line.labels;
            this.dataCpu.datasets[0].data = line.cpu;
            this.dataMemory.labels = line.labels;
            this.dataMemory.datasets[0].data = line.memory;
        });
        this.loaded = true;
    },
    created() {

    },
    methods: {
    }
};
</script>

