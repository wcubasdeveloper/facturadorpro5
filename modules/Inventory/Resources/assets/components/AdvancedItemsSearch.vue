<template>
    <div>
        <label class="control-label">
            Producto
            <el-tooltip class="item"
                        content="Escribir al menos 3 caracteres para iniciar la búsqueda"
                        effect="dark"
                        placement="top-start">
                <i class="fa fa-info-circle"></i>
            </el-tooltip>
        </label>
        <el-select
            v-model="item_id"
            :loading="loading_search"
            :remote-method="searchRemoteItems"
            filterable
            placeholder="Buscar"
            remote
            @change="changeItem"
        >
            <el-option
                v-for="option in items"
                :key="option.id"
                :label="option.full_description"
                :value="option.id"
            ></el-option>

        </el-select>
        <small v-if="errors.item_id" class="form-control-feedback" v-text="errors.item_id[0]"></small>
    </div>
</template>

<script>

    export default {
        props: [

        ],
        data() {
            return {
                resource: 'advanced-items-search',
                errors: {},
                item_id: null,
                loading_search: false,
                all_items: [],
                items: [],
            }
        },
        created() {
            this.initData()
        },
        methods: {
            async initData(){

                await this.$http.get(`/${this.resource}`)
                    .then(response => {
                        this.all_items = response.data.items
                        this.initItems()
                    })

            },
            changeItem(){
                this.setItem(this.item_id)
            },
            setItem(item_id){
                this.$emit('eventSetItemId', item_id)
            },
            async searchRemoteItems(input) {

                if (input.length > 2) {

                    this.loading_search = true
                    let parameters = `search_value=${input}`

                    await this.$http.get(`/${this.resource}?${parameters}`)
                        .then(response => {

                            this.items = response.data.items

                            if (this.items.length == 0) {
                                this.initItems()
                            }
                        })
                        .then(() => {
                            this.loading_search = false
                        })
                } else {
                    await this.initItems()
                }

            },
            initItems(){
                this.items = this.all_items
            }
        }
    }
</script>
