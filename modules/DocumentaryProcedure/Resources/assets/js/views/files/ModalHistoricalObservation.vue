<template>
    <el-dialog
        :close-on-click-modal="false"
        :title="title"
        :visible="visible"
        @close="onClose"
        @open="onOpened"
    >
        <div class="form-body">
            <table-observation></table-observation>
            <div class="col-12 text-center p-t-20">

                <el-button @click="onClose">Cerrar</el-button>
            </div>
        </div>
    </el-dialog>
</template>

<script>
import {mapState} from "vuex";
import TableObservation from "./TableObservation";

export default {
    components: {
        TableObservation
    },
    props: {
        visible: {
            type: Boolean,
            required: true,
        },
    },
    data() {
        return {
            loading: false,
            title: "",
        };
    },
    computed: {
        ...mapState([
            'file',
        ]),

    },
    created() {
    },
    mounted() {

    },
    methods: {

        onClose() {
            this.$emit("update:visible", false);
        },
        onOpened() {
            this.title = `Historico de Observaciones para el tramite: ${this.file.invoice}`;
        },
    },
};
</script>
