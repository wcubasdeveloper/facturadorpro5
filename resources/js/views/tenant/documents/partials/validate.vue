<template>
    <div>
          <el-dialog :title="titleDialog" :visible="showDialogValidate"  @close="close"  @open="initForm" class="dialog-import" width="80%">
        <form autocomplete="off">
            <div class="form-body" v-loading="loading_submit" :element-loading-text="loading_text">
                 <div class="row"> 
                     <div class="col-md-12">
                            <label class="control-label">Periodo</label>
                            <el-select v-model="form.period" @change="changePeriod">
                                <el-option key="month" value="month" label="Por mes"></el-option>
                                <el-option key="between_months" value="between_months" label="Entre meses"></el-option>
                                <el-option key="date" value="date" label="Por fecha"></el-option>
                                <el-option key="between_dates" value="between_dates" label="Entre fechas"></el-option>
                            </el-select>
                    </div>
                    <template v-if="form.period === 'month' || form.period === 'between_months'">
                            <div class="col-md-12">
                                <label class="control-label">Mes de</label>
                                <el-date-picker v-model="form.month_start" type="month"
                                                @change="changeDisabledMonths"
                                                value-format="yyyy-MM" format="MM/yyyy" :clearable="false"></el-date-picker>
                            </div>
                        </template>
                        <template v-if="form.period === 'between_months'">
                            <div class="col-md-12">
                                <label class="control-label">Mes al</label>
                                <el-date-picker v-model="form.month_end" type="month"
                                                :picker-options="pickerOptionsMonths"
                                                value-format="yyyy-MM" format="MM/yyyy" :clearable="false"></el-date-picker>
                            </div>
                        </template>
                        <template v-if="form.period === 'date' || form.period === 'between_dates'">
                            <div class="col-md-12">
                                <label class="control-label">Fecha del</label>
                                <el-date-picker v-model="form.date_start" type="date"
                                                @change="changeDisabledDates"
                                                value-format="yyyy-MM-dd" format="dd/MM/yyyy" :clearable="false"></el-date-picker>
                            </div>
                        </template>
                        <template v-if="form.period === 'between_dates'">
                            <div class="col-md-12">
                                <label class="control-label">Fecha al</label>
                                <el-date-picker v-model="form.date_end" type="date"
                                                :picker-options="pickerOptionsDates"
                                                value-format="yyyy-MM-dd" format="dd/MM/yyyy" :clearable="false"></el-date-picker>
                            </div>
                        </template>
                     <div class="col-lg-12 col-md-12 ">
                        <div class="form-group" :class="{'has-danger': errors.document_type_id}"> 
                            <label class="control-label">Comprobante<span class="text-danger"> *</span></label>
                             <el-select v-model="form.document_type_id">
                               <el-option v-for="option in document_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                               
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.document_type_id" v-text="errors.document_type_id[0]"></small>
                        </div>
                    </div>
                  
                     
                    <div class="col-md-12 mt-4"> 
                        <el-button class="submit" type="primary" @click.prevent="getRecords" :loading="loading_submit" icon="el-icon-check" >Validar documentos</el-button>
                         <el-button class="submit" type="danger" @click.prevent="close"  icon="el-icon-delete" >Cerrar </el-button>
                    </div>             
                    
                </div>
            </div>
        </form>
    </el-dialog>      
    </div>
</template>
<style>
.font-custom{
    font-size:15px !important
}
</style>
<script>

    import moment from 'moment'
    import queryString from 'query-string'

    export default { 
        props: ['showDialogValidate'],
        data () {
            return {
                loading_submit:false,
                columns: [],
                records: [],
                customers: [],
                document_types: [],
                state_types: [],
                resource:"reports/validate-documents",
                pagination: {}, 
                errors: {}, 
                form: {}, 
                all_series: [], 
                series: [],            
                see_more:false, 
                titleDialog:"Validar Comprobantes Electronicos",
                loading_text: 'Validando documentos electrónicos...',
                acumulado:0,
                pickerOptionsDates: {
                disabledDate: (time) => {
                    time = moment(time).format('YYYY-MM-DD')
                    return this.form.date_start > time
                    }
                },
                pickerOptionsMonths: {
                disabledDate: (time) => {
                    time = moment(time).format('YYYY-MM')
                    return this.form.month_start > time
                    }
                },
            }
        },
        computed: {
        },
        created() {
            this.initForm()
            this.$eventHub.$on('reloadData', () => {
             //   this.getRecords()
            })
        },
       /* async mounted () { 
            await this.$http.get(`/${this.resource}/data_table`).then((response) => {
                this.document_types = response.data.document_types
                this.all_series = response.data.series
                this.form.document_type_id = (this.document_types.length > 0) ? this.document_types[0].id : null
                this.changeDocumentType()
            });
            this.initForm()

            // await this.getRecords()

        },*/
        methods: {
            clickSeeMore(){
                this.see_more = (this.see_more) ? false : true
            },
           async initForm(){
                this.form = {
                    document_type_id:"01",
                   period: 'month',
                    date_start:moment().startOf('month').format('YYYY-MM-DD'),
                    date_end: moment().endOf('month').format('YYYY-MM-DD'),
                    month_start: moment().format('YYYY-MM'),
                    month_end: moment().format('YYYY-MM'),
                }
                 await this.$http.get(`/reports/validate-documents/data_table`).then((response) => {
                this.document_types = response.data.document_types
                 this.form.document_type_id = (this.document_types.length > 0) ? this.document_types[0].id : null
              //  this.changeDocumentType()
            });
            },
              changeDisabledDates() {
                if (this.form.date_end < this.form.date_start) {
                    this.form.date_end = this.form.date_start
                }
                // this.loadAll();
            },

            customIndex(index) {
                return (this.pagination.per_page * (this.pagination.current_page - 1)) + index + 1
            },
            async getRecordsByFilter(){
               
                await this.getRecords()
               
            },
            async  getRecords() {
                 this.loading_submit = true
                 this.acumulado=0
                 const response = await this.$http.get(`/reports/validate-documents/validate_masivo?${this.getQueryParameters()}`)
                 let responses=false;
                 if(response.data.success){
                    this.$message.success(response.data.message);
                        for (let index = 0; index < response.data.archivo_txt; index++) {
                             await  this.$http.get(`/reports/validate-documents/validateDocumentstxt?numero=`+index).then((response) =>{
                                    if(response.data.success==true){
                                         responses=true
                                         this.acumulado=this.acumulado+1
                                          this.$message.success(response.data.message);
                                    }else{
                                          this.$message.error(response.data.message);
                                           this.loading_submit = false
                                    }
                                  
                              })

                          }
                          if(responses==true){
                                    this.loading_submit = false
                                    this.$eventHub.$emit('reloadData')
                                    const respusta = await this.$http.get(`/reports/validate-documents/validatecount?${this.getQueryParameters()}`)
                                    this.$alert('<b>Comprobante Aceptados: '+respusta.data.aceptados+"<br> Anulados: "+respusta.data.anulados+'<br/> Registrados: '+respusta.data.registrados+'</b>', 'Validacion Comprobantes Electronicos',{
                                    dangerouslyUseHTMLString: true,    
                                    confirmButtonText: 'Aceptar',
                                    })
                                 // this.close()
                          }
                        
                 }else{
                       this.loading_submit = false
                        this.$message.error(response.data.message);
                 }

            
              

            },
            getQueryParameters() {
                return queryString.stringify({
                    page: this.pagination.current_page,
                    limit: this.limit,
                    ...this.form
                })
            },
            changeClearInput(){
                this.search.value = ''
                // this.getRecords()
            }, 
            cleanInputs(){
                this.initForm()
            },
             changeDisabledMonths() {
                 this.form.month_end=this.form.month_start
             },
              close() {
                  this.$eventHub.$emit('reloadData')
                  this.$eventHub.$emit('reloadTables')
                  this.$emit('update:showDialogValidate', false)
                  this.initForm()
            },
            changePeriod() {
                if(this.form.period === 'month') {
                    this.form.month_start = moment().format('YYYY-MM');
                    this.form.month_end = moment().format('YYYY-MM');
                }
                if(this.form.period === 'between_months') {
                    this.form.month_start = moment().startOf('year').format('YYYY-MM'); //'2019-01';
                    this.form.month_end = moment().endOf('year').format('YYYY-MM');;
                }
                if(this.form.period === 'date') {
                    this.form.date_start = moment().format('YYYY-MM-DD');
                    this.form.date_end = moment().format('YYYY-MM-DD');
                }
                if(this.form.period === 'between_dates') {
                    this.form.date_start = moment().startOf('month').format('YYYY-MM-DD');
                    this.form.date_end = moment().endOf('month').format('YYYY-MM-DD');
                }
                // this.loadAll();
            },
        }
    }
</script>