<template>
    <div
        v-if="haveObservation(file)"
        class="table-responsive">
        <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Observacion</th>
                    <th>Fecha</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item,index) in file.observations" :key="item.id">
                    <td>{{ index + 1 }}</td>
                    <td>
                        {{ item.observation }}
                    </td>
                    <td>
                        {{ item.created_at }}
                    </td>
                </tr>
                </tbody>
            </table>
    </div>
</template>

<script>
import {mapState} from "vuex";

export default {
    name: 'table-observation-historical',
    props: [],
    computed: {
        ...mapState([
            'file',
        ]),
    },
    data() {
        return {
            borrando: false,
        };
    },
    mounted() {

    },
    methods: {

        haveObservation(file){
            if(file === null) return false;
            if(file === undefined) return false;
            if(file.observations === undefined) return false;
            if(file.observations == null) return false;
            if(file.observations.length == null) return false;
            if(file.observations.length < 1) return false;
            return true
        },
    },
};
</script>
