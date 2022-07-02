<template>
    <div class="card mb-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Consulta kardex</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12  ">
                    <el-tabs v-model="activeName">
                        <el-tab-pane label="General" name="first">
                            <tenant-report-kardex-index></tenant-report-kardex-index>
                        </el-tab-pane>
                        <el-tab-pane label="Por Lotes" name="second">
                            <tenant-report-kardex-lots></tenant-report-kardex-lots>
                        </el-tab-pane>
                        <el-tab-pane label="Por series" name="third">
                            <tenant-report-kardex-series></tenant-report-kardex-series>
                        </el-tab-pane>
                    </el-tabs>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapState} from "vuex/dist/vuex.mjs";

export default {
    props:['configuration'],
    components: {},
    data() {
        return {
            activeName: "first"
        };
    },
    created() {
        this.loadConfiguration()
        this.$store.commit('setConfiguration', this.configuration)
    },
    methods:{
        ...mapActions([
            'loadConfiguration',
        ]),
    },
};
</script>
