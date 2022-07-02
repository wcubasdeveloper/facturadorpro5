<template>
    <div>
        <el-dialog :title="title"
                   :visible="showDialogValidate"
                   @close="close" @open="handleOpen"
                   class="dialog-import" width="80%">
            <div class="row" v-loading="loading_submit">
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
                                        value-format="yyyy-MM" format="MM/yyyy"
                                        :clearable="false"></el-date-picker>
                    </div>
                </template>
                <template v-if="form.period === 'between_months'">
                    <div class="col-md-12">
                        <label class="control-label">Mes al</label>
                        <el-date-picker v-model="form.month_end" type="month"
                                        :picker-options="pickerOptionsMonths"
                                        value-format="yyyy-MM" format="MM/yyyy"
                                        :clearable="false"></el-date-picker>
                    </div>
                </template>
                <template v-if="form.period === 'date' || form.period === 'between_dates'">
                    <div class="col-md-12">
                        <label class="control-label">Fecha del</label>
                        <el-date-picker v-model="form.date_start" type="date"
                                        @change="changeDisabledDates"
                                        value-format="yyyy-MM-dd" format="dd/MM/yyyy"
                                        :clearable="false"></el-date-picker>
                    </div>
                </template>
                <template v-if="form.period === 'between_dates'">
                    <div class="col-md-12">
                        <label class="control-label">Fecha al</label>
                        <el-date-picker v-model="form.date_end" type="date"
                                        :picker-options="pickerOptionsDates"
                                        value-format="yyyy-MM-dd" format="dd/MM/yyyy"
                                        :clearable="false"></el-date-picker>
                    </div>
                </template>
                <div class="col-lg-12 col-md-12 ">
                    <div class="form-group" :class="{'has-danger': errors.document_type_id}">
                        <label class="control-label">Comprobante<span class="text-danger"> *</span></label>
                        <el-select v-model="form.document_type_id">
                            <el-option v-for="option in document_types" :key="option.id" :value="option.id"
                                       :label="option.description"></el-option>

                        </el-select>
                        <small class="form-control-feedback" v-if="errors.document_type_id"
                               v-text="errors.document_type_id[0]"></small>
                    </div>
                </div>
                <div class="col-md-12 mt-4 text-right">
                    <el-button @click.prevent="close">Cerrar</el-button>
                    <el-button type="primary" @click.prevent="onSubmit" :loading="loading_submit" >Buscar y Validar</el-button>
                </div>

            </div>
        </el-dialog>
    </div>
</template>

<script>

import moment from 'moment';

export default {
    name: 'ApiPeruDevMassiveValidateCPE',
    props: ['showDialogValidate'],
    data() {
        return {
            loading: false,
            loading_submit: false,
            resource: 'apiperudev/massive_validate_cpe',
            title: '',
            errors: {},
            form: {},
            document_types: [],
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
    created() {
        this.initTables();
        this.title = 'Validación masiva de comprobantes'
    },
    methods: {
        initTables() {
            this.$http.get(`/${this.resource}/tables`)
                .then((response) => {
                    this.document_types = response.data.document_types
                });
        },
        initForm() {
            this.form = {
                document_type_id: "01",
                period: 'month',
                date_start: moment().startOf('month').format('YYYY-MM-DD'),
                date_end: moment().endOf('month').format('YYYY-MM-DD'),
                month_start: moment().format('YYYY-MM'),
                month_end: moment().format('YYYY-MM'),
            }
        },
        handleOpen() {
            this.initForm();
        },
        changeDisabledDates() {
            if (this.form.date_end < this.form.date_start) {
                this.form.date_end = this.form.date_start
            }
        },
        changeDisabledMonths() {
            this.form.month_end = this.form.month_start
        },
        changePeriod() {
            if (this.form.period === 'month') {
                this.form.month_start = moment().format('YYYY-MM');
                this.form.month_end = moment().format('YYYY-MM');
            }
            if (this.form.period === 'between_months') {
                this.form.month_start = moment().startOf('year').format('YYYY-MM');
                this.form.month_end = moment().endOf('year').format('YYYY-MM');
            }
            if (this.form.period === 'date') {
                this.form.date_start = moment().format('YYYY-MM-DD');
                this.form.date_end = moment().format('YYYY-MM-DD');
            }
            if (this.form.period === 'between_dates') {
                this.form.date_start = moment().startOf('month').format('YYYY-MM-DD');
                this.form.date_end = moment().endOf('month').format('YYYY-MM-DD');
            }
        },
        async onSubmit() {
            this.loading_submit = true;
            await this.$http.post(`/${this.resource}`, this.form)
                .then((response) => {
                    let res = response.data;
                    if(res.success) {
                        this.$message.success(res.message);
                        this.$eventHub.$emit('reloadData');
                    } else {
                        this.$message.error(res.message);
                    }
                });
            this.loading_submit = false;
        },
        close() {
            this.$emit('update:showDialogValidate', false)
        },
    }
}
</script>
