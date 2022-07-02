<template>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title text-center">
                        <i class="fab fa-gitlab" style="color: rgb(41, 41, 97);"></i>
                        <p class="">
                            Versión:{{version}}
                        </p>
                        <el-button type="button" class="btn btn-sm btn-primary pb-1" @click.prevent="start()" :loading="loading_submit" :disabled="exec_migration">Iniciar proceso</el-button>

                        <el-button type="button" class="btn btn-sm btn-warning" @click.prevent="execMigrations()" :disabled="!exec_migration" :loading="loading_submit_migrations">Ejecutar migraciones</el-button>
                        <div class="text-right">
                            <el-checkbox v-model="exec_migration">Ejecutar migraciones</el-checkbox><br>
                        </div>
                    </div>
                    <div class="card-title text-center" v-if="infostatus">
                        {{ infotextwait }}
                    </div>
                    <div class="card-title text-center" v-if="infostatus">
                        {{ infotext }}
                    </div>
                    <div v-if="content.status == true && content.step == 'updating'" id="response-content">

                        <div v-if="branch.status == 'success'">
                            <h3>Obteniendo rama del repositorio</h3>
                            <el-progress :percentage="branch.percent"></el-progress>
                            <h4>Rama actual: <strong>{{branch.name}}</strong></h4>
                            <span class="text-danger">{{branch.error}}</span><br>
                            <!-- <span class="text-danger">{{branch.status}}</span> -->
                            <hr>
                            <h3>Descargando actualización</h3>
                            <h4>Log: {{pull.content}}</h4>
                            <span class="text-danger">{{pull.error}}</span><br>
                            <!-- <span class="text-danger">{{pull.status}}</span> -->
                        </div>

                        <div v-if="artisan.migrate.status == 'success'">
                            <hr>
                            <h3>Corriendo migraciones en administrador</h3>
                            <h4>Log: {{artisan.migrate.content}}</h4>
                            <span class="text-danger">{{artisan.migrate.error}}</span><br>
                            <!-- <span class="text-danger">{{artisan.migrate.status}}</span> -->
                        </div>

                        <div v-if="artisan.tenancy_migrate.status == 'success'">
                            <hr>
                            <h3>Corriendo migraciones en cliente</h3>
                            <h4>Log: {{artisan.tenancy_migrate.content}}</h4>
                            <span class="text-danger">{{artisan.tenancy_migrate.error}}</span><br>
                            <!-- <span class="text-danger">{{artisan.tenancy_migrate.status}}</span> -->
                        </div>

                        <div v-if="pull.status == 'success'">
                            <div v-if="pull.updated == false">


                                <div v-if="artisan.clear.status == 'success'">
                                    <hr>
                                    <h3>Eliminando Caché</h3>
                                    <h4>Log: {{artisan.clear.content}}</h4>
                                    <span class="text-danger">{{artisan.clear.error}}</span><br>
                                    <!-- <span class="text-danger">{{artisan.clear.status}}</span> -->
                                </div>

                                <div v-if="composer.install.status == 'success'">
                                    <h3>Actualizando dependencias</h3>
                                    <h4>Log:</h4>
                                    <pre>
                                        {{composer.install.content}}
                                    </pre>
                                    <span class="text-danger">{{composer.install.error}}</span><br>
                                    <!-- <span class="text-danger">{{composer.install.status}}</span> -->
                                </div>
                            </div>
                            <div v-else>
                                <hr>
                                <h3>El sistema está actualizado</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6" style="">
            <div class="card">
                <div class="card-body">
                    <div class="card-title text-center">
                        <i class="fas fa-file-alt" style="color: rgb(41, 41, 97);"></i>
                        <p>Changelog</p>
                    </div>
                    <div v-html="changelog" style="max-height: 600px; overflow-y: auto;"></div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import $ from 'jquery'

    export default {
        data() {
            return {
                infostatus:false,
                infotextwait:'',
                infotext:'',
                headers: null,
                resource: 'auto-update',
                errors: {},
                form: {},
                loading_submit: false,
                loading_submit_migrations: false,
                exec_migration: false,
                version: '',
                changelog: '',
                content: {
                    status: false,
                },
                branch: {
                    name: '',
                    percent: 1,
                    error: '',
                    status: '',
                },
                pull: {
                    error: '',
                    status: '',
                    content: '',
                    updated: '',
                },
                artisan: {
                    error: '',
                    status: '',
                    migrate: {
                        error: '',
                        status: false,
                        content: '',
                    },
                    tenancy_migrate: {
                        error: '',
                        status: false,
                        content: '',
                    },
                    clear: {
                        error: '',
                        status: false,
                        content: '',
                    }
                },
                composer: {
                    install: {
                        error: '',
                        status: false,
                        content: '',
                    },
                    update: {
                        error: '',
                        status: false,
                        content: '',
                    },
                }
            }
        },
        created() {
            this.getVersion()
            this.getChangelog()
        },
        methods: {
            messajeOk(text){
                this.infostatus = true;
                this.infotextwait ='Por favor espere'
                this.infotext = text
                this.$message.success(this.infotext)
            },
            messajeFail(text){
                this.infostatus = true;
                this.infotextwait ='Ocurrió un error'
                this.infotext = text
                this.$message.error(this.infotext)
            },
            hideInfo(){
                this.infostatus = false;
                this.infotextwait =''
                this.infotext = '';
            },
            async start() {
                this.loading_submit = true
                this.infostatus = true;
                this.messajeOk('Se ha iniciado el proceso de actualización')
                this.initContent()
                this.content.status = true
                this.content.step = 'updating'
                await this.getBranch()
            },
            initContent() {
                this.content.status= false
                this.content.step= ''
                this.branch.name = ''
                this.branch.percent = 1
                this.branch.error = ''
                this.branch.status = false
                this.pull.error = ''
                this.pull.status = false
                this.pull.updated = false
                this.artisan.error = ''
                this.artisan.status = false
                this.artisan.content = ''
                this.artisan.migrate
                this.artisan.migrate.error = ''
                this.artisan.migrate.status = false
                this.artisan.migrate.content = ''
                this.artisan.tenancy_migrate.error = ''
                this.artisan.tenancy_migrate.status = false
                this.artisan.tenancy_migrate.content = ''
                this.artisan.clear.error = ''
                this.artisan.clear.status = false
                this.artisan.clear.content = ''
                this.composer.install.error = ''
                this.composer.install.status = false
                this.composer.install.content = ''
                this.composer.update.error = ''
                this.composer.update.status = false
                this.composer.update.content = ''
            },
            getVersion() {
                this.$http.get(`/${this.resource}/version`)
                .then(response => {
                    if (response.data !== '') {
                        this.version = response.data
                    }
                }).catch(error => {
                    if (error.response.status !== 200) {
                        this.version.error = error.response.data.message
                    } else {
                        console.log(error)
                    }
                })
            },
            getChangelog() {
                this.$http.get(`/${this.resource}/changelog`)
                .then(response => {
                    if (response.data !== '') {
                        this.changelog = response.data
                    }
                }).catch(error => {
                    if (error.response.status !== 200) {
                        this.changelog.error = error.response.data.message
                    } else {
                        console.log(error)
                    }
                })
            },
            getBranch() {
                this.branch.percent = 40
                this.messajeOk('Obteniendo información de la rama' )
                this.$http.get(`/${this.resource}/branch`)
                .then(response => {
                    this.hideInfo();
                    this.branch.percent = 70
                    if (response.data !== '') {
                        this.branch.name = response.data
                        this.messajeOk('Se ha Obteniendo la información de la rama ' + this.branch.name)
                        this.branch.percent = 100
                        if (response.status === 200) {
                            this.branch.status = 'success'
                        }
                        this.execPull()
                    }
                }).catch(error => {
                    if (error.response.status !== 200) {
                        this.branch.percent = 0
                        this.branch.error = error.response.data.message
                        this.branch.status = 'false'
                    } else {
                        console.log(error)
                    }
                    this.messajeFail('Ocurrió un error, no se puede continuar')
                })
            },
            execPull() {
                this.messajeOk('Se están descargando los datos')
                this.$http.get(`/${this.resource}/pull/${this.branch.name}`)
                .then(response => {
                    this.hideInfo();
                    if (response.data !== '') {
                        this.pull.content = response.data
                        this.pull.percent = 100
                        if (response.status === 200) {
                            this.pull.status = 'success'
                        }
                        let pullContent = this.pull.content

                        if (
                            pullContent.includes('Already up to date.') === true |
                            pullContent.includes('Already up-to-date.') === true
                        ) {
                            this.messajeOk('Todo esta correcto')
                            this.hideInfo()
                            this.loading_submit = false
                            this.pull.updated = true
                        } else {
                            this.execComposer()
                        }
                    }
                }).catch(error => {
                    this.pull.percent = 0
                    this.pull.error = 'no ha podido finalizar'
                    this.pull.status = 'false'
                    console.log(error)
                    this.messajeFail('No se ha podido descargar los datos')
                }).finally(()=>{
                    // ejecuta las migraciones al finalziar. sea exitoso o no
                    // this.execArtisanMigrate()
                })
            },
            execComposer() {
                this.messajeOk('Se están instalando dependencias Composer')
                this.$http.get(`/${this.resource}/composer/install`)
                .then(response => {
                    this.hideInfo();
                    if (response.data !== '') {
                        this.composer.install.content = response.data
                        this.composer.install.percent = 100
                        if (response.status === 200) {
                            this.messajeOk('Se ha ejecutado la instalación de dependecias composer')
                            this.composer.install.status = 'success'
                            this.execArtisanMigrate()
                        }
                    }
                }).catch(error => {
                    if (error.response.status !== 200) {
                        this.composer.install.percent = 0
                        this.composer.install.error = error.response.data.message
                        this.composer.install.status = 'false'
                    } else {
                        console.log(error)
                    }

                    if (
                        error !== undefined &&
                        error.response !== undefined &&
                        error.response.status !== undefined &&
                        error.response.status === 504){
                        // error de tiempo 504 de servidor nginx
                        this.execArtisanMigrate()

                    }else{
                        this.messajeFail('Ha ocurrido un error al instalar las dependencias Composer')

                    }
                })

                this.loading_submit = false
            },
            execArtisanMigrate() {
                this.messajeOk('Se están ejecutando las migraciones')
                this.$http.get(`/${this.resource}/artisan/migrate`)
                .then(response => {
                    this.hideInfo();
                    if (response.data !== '') {
                        this.artisan.migrate.content = response.data
                        this.artisan.migrate.percent = 100
                        if (response.status === 200) {
                            this.artisan.migrate.status = 'success'
                            this.messajeOk('Se han ejecutando las migraciones')
                            this.execArtisanMigrateTenant()
                        }
                    }
                }).catch(error => {
                    if (error.response.status !== 200) {
                        this.artisan.migrate.percent = 0
                        this.artisan.migrate.error = error.response.data.message
                        this.artisan.migrate.status = 'false'
                    } else {
                        console.log(error)
                    }
                    this.messajeFail('Ocurrió un error al ejecutar las migraciones')
                })
            },
            execArtisanMigrateTenant() {
                this.messajeOk('Se están ejecutando las migraciones de los tenant')
                this.$http.get(`/${this.resource}/artisan/migrate/tenant`)
                .then(response => {
                    this.hideInfo();
                    if (response.data !== '') {
                        this.artisan.tenancy_migrate.content = response.data
                        this.artisan.tenancy_migrate.percent = 100
                        if (response.status === 200) {
                            this.artisan.tenancy_migrate.status = 'success'
                            this.messajeOk('Se han ejecutando las migraciones de los tenant')
                            this.execArtisanClear()
                        }
                    }
                }).catch(error => {
                    this.artisan.tenancy_migrate.percent = 0
                    this.artisan.tenancy_migrate.error = error
                    this.artisan.tenancy_migrate.status = false
                    console.log(error)
                    this.messajeFail('Ocurrió un error al ejecutar las migraciones de los tenant')
                })
            },
            execArtisanClear() {
                this.messajeOk('Se esta limpiando los procesos artisan')
                this.$http.get(`/${this.resource}/artisan/clear`)
                .then(response => {
                    this.hideInfo();
                    if (response.data !== '') {
                        this.artisan.clear.content = response.data
                        this.artisan.clear.percent = 100
                        if (response.status === 200) {
                            this.messajeOk('Se han limpiado los procesos artisan')
                            this.artisan.clear.status = 'success'
                        }
                        this.hideInfo()
                    }
                }).catch(error => {
                    this.artisan.clear.percent = 0
                    this.artisan.clear.error = error
                    this.artisan.clear.status = false
                    console.log(error)
                    this.messajeFail('Ocurrió un error al limpiar los procesos artisan')
                })
            },
            execMigrations() {
                this.infostatus = true;
                this.infotextwait ='Por favor espere'
                this.loading_submit_migrations = true
                this.loading_submit = true
                this.initContent()
                this.content.status = true
                this.content.step = 'updating'
                this.execArtisanMigrate();
                this.loading_submit_migrations = false
                this.loading_submit = false
            }
        }
    }
</script>
