<template>
    <div class="row">
        <div v-for="(item,index) in requirement" class="col-6">
            <div class="form-group">
                <label >Sera necesario cargarlo al sistema?</label>
                <el-checkbox
                    v-model="form.file"
                    :class="{ 'is-invalid': errors.description }"
                ></el-checkbox>

                <div v-if="errors.file" class="invalid-feedback">
                    {{ errors.file[0] }}
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    props: [
        'requirements',
        'requirement',
    ],
    data() {
        return {
            officesSelected: [],
        };
    },
    beforeMount() {
        this.onAddOffice();
    },
    methods: {
        onAddOffice() {
            this.form.offices.push({
                documentary_action_id: null,
                documentary_office_id: null,
                observation: null,
            });
        },
        onDeleteOffice(index) {
            this.form.offices.splice(index, 1);
        }
    },
};
</script>
<style land="scss">
.btn-delete-office {
    position: absolute;
    right: 16px;
    top: 44px;
    padding: 4px 4px;
    z-index: 2;
}
</style>
