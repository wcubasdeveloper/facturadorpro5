<template>
    <div
        v-if="
        file !== null &&
        file.documentary_file_archives !== null &&
        file.documentary_file_archives.length > 0
        "
        class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>
                    #
                </th>

                <!--                                        <th>
                                                            observation
                                                        </th>-->
                <th>
                    Nombre de archivo
                </th>
                <th>
                    Accion
                </th>
            </tr>


            </thead>
            <tbody>
            <tr v-for="(archive,index) in this.file.documentary_file_archives"
                :key="archive.id"
            >
                <td>
                    {{ index + 1 }}
                    <!--                                        </td>
                                                            <td>
                                                                {{ archive.observation }}

                                                            </td>-->
                <td>
                    {{ archive.public_name }}
                </td>
                <td>
                    <el-button type="primary"
                               @click.prevent="downloadFile(archive.public_url)">Descargar
                    </el-button>
                    <el-button :loading="borrando" type="danger"
                               @click.prevent="removeFile(archive.id, archive.documentary_file_id)">Borrar

                    </el-button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import {mapState} from "vuex";

export default {
    name: 'table-archives-documentary',
    props: [
    ],
    computed: {
        ...mapState([
            'file',
            'files',
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
        downloadFile(url) {
            window.open(url, '_blank');
        },
        removeFile(id,documentary_file_id) {
            this.borrando = true;
            this.$http
                .get(`/documentary-procedure/file/remove/${id}`)
                .then((result) => {
                    //reload
                    this.updateFile(documentary_file_id)
                    this.updateFiles()
                    this.borrando = false;
                })
                .catch((err)=>{
                    this.updateFile(documentary_file_id)

                    this.updateFiles()
                })
                .finally(() => {
                    this.borrando = false;
                })

        },

        updateFiles() {
            this.$emit("updateFiles");
        },
        updateFile(id) {
            this.$http
                .post(`/documentary-procedure/file/reload/${id}`)
                .then((result) => {
                    let item = result.data.item;
                    this.$store.commit('setFile', item)

                })
        }
    },
};
</script>
