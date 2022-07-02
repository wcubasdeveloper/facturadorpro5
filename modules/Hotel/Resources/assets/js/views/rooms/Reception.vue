<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active"><span>VISTA GENERAL RECEPCIÓN</span></li>
            </ol>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Vista general recepción</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- piso -->
                    <div class="col-md-3 col-sm-12 pb-2">
                        <el-select
                            v-model="hotel_floor_id"
                            :disabled="loading"
                            clearable
                            placeholder="Piso"
                            @change="searchRooms"
                        >
                            <el-option
                                v-for="f in floors"
                                :key="f.id"
                                :label="f.description"
                                :value="f.id"
                            >
                            </el-option>
                        </el-select>
                    </div>
                    <!-- Campo de busqueda -->
                    <div class="col-md-5 col-sm-12 pb-2">
                        <el-input
                            v-model="hotel_name_room"
                            clearable
                            placeholder="Buscar por nombre de habitacion"
                            prefix-icon="el-icon-search"
                            style="width: 100%;"
                            @input="searchRooms"
                        >
                        </el-input>
                    </div>
                    <!-- botones de status -->
                    <div class="col-md-4 col-sm-12 pb-2">
                        <el-button-group
                        >
                            <el-button
                                v-for="st in roomStatus"
                                :key="st"
                                :class="onGetColorStatus(st)"
                                :disabled="loading"
                                class="btn btn-sm"
                                size="mini"
                                @click="onFilterByStatus(st)"
                            >{{ st }}
                            </el-button
                            >
                        </el-button-group>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div v-for="ro in items"
                         :key="ro.id"
                         class="col-sm-12 col-md-6 col-lg-4 mb-4">
                        <el-card
                            :class="onGetColorStatus(ro.status)"
                            style="min-height: 160px"
                        >
                            <div
                                slot="header"
                                class="d-flex align-items-center justify-content-between"
                            >
                                <span>{{ ro.status }}: {{ ro.name }}</span>
                                <template v-if="ro.status === 'LIMPIEZA'">
                                    <el-button
                                        :disabled="loading"
                                        :loading="loading"
                                        style="margin-left: auto"
                                        title="Ir al checkout"
                                        type="primary"
                                        @click="onFinalizeClean(ro)"
                                    >
                                        <i class="fa fa-broom"></i>
                                    </el-button>
                                </template>
                                <template v-if="ro.status === 'OCUPADO'">
                                    <el-button
                                        style="margin-left: auto"
                                        title="Ir al checkout"
                                        type="primary"
                                        @click="onGoToCheckout(ro)"
                                    >
                                        <i class="fa fa-arrow-circle-right"></i>
                                    </el-button>
                                    <el-button
                                        style="margin-left: 0.5rem"
                                        title="Agregar productos"
                                        type="primary"
                                        @click="onGoToAddProducts(ro)"
                                    >
                                        <i class="fa fa-plus-circle"></i>
                                    </el-button>
                                </template>
                                <el-button
                                    v-if="ro.status === 'DISPONIBLE'"
                                    type="primary"
                                    @click="onToRent(ro)"
                                >
                                    <i class="fa fa-arrow-circle-left"></i>
                                </el-button>
                            </div>
                            <div
                                v-if="ro.status === 'DISPONIBLE'"
                                class="d-flex justify-content-center align-items-center"
                            >
                                <i class="fa fa-bed fa-2x mt-2"></i>
                                <span class="h3 ml-3">{{ ro.name }}</span>
                            </div>
                            <div
                                v-if="ro.status === 'OCUPADO'"
                                class="d-flex justify-content-center align-items-center"
                            >
                                <i class="fa fa-user-tie fa-2x"></i>
                                <span class="h6 ml-3">{{ ro.rent.customer.name }}</span>
                            </div>
                        </el-card>
                    </div>
                </div>
            </div>
        </div>
        <ModalRoomRates
            :room="room"
            :visible.sync="openModalRoomRates"
            @onAddRoomRate="onAddRoomRate"
            @onDeleteRate="onDeleteRate"
        ></ModalRoomRates>
    </div>
</template>

<script>
import ModalRoomRates from "./RoomRates";

export default {
    components: {
        ModalRoomRates,
    },
    props: {
        roomStatus: {
            type: Array,
            required: true,
        },
        floors: {
            type: Array,
            required: true,
        },
        rooms: {
            type: Array,
            required: true,
            default: [],
        },
    },
    data() {
        return {
            hotel_floor_id: "",
            hotel_name_room: null,
            loading: false,
            items: [],
            room: null,
            openModalRoomRates: false,
        };
    },
    mounted() {
        this.items = this.rooms;
    },
    /*
    watch: {
        hotel_floor_id() {
            this.onFilterByStatus();
        },
    },
    */
    methods: {
        onFinalizeClean(room) {
            const text = `Está a punto de terminar la limpieza de la habitación ${room.name}`;
            this.$confirm(text, "Atención", {
                confirmButtonText: "Si",
                cancelButtonText: "No",
                type: "warning",
            })
                .then(() => {
                    this.loading = true;
                    const payload = {
                        status: "DISPONIBLE",
                    };
                    this.$http
                        .post(`/hotels/rooms/${room.id}/change-status`, payload)
                        .then((response) => {
                            room.status = "DISPONIBLE";
                            this.items = this.items.map((r) => {
                                if (r.id === room.id) {
                                    return room;
                                }
                                return r;
                            });
                            this.$message({
                                type: "success",
                                message: response.data.message,
                            });
                        })
                        .finally(() => (this.loading = false));
                })
                .catch();
        },
        onGoToCheckout(room) {
            window.location.href = `/hotels/reception/${room.rent.id}/rent/checkout`;
        },
        onGoToAddProducts(room) {
            window.location.href = `/hotels/reception/${room.rent.id}/rent/products`;
        },
        onDeleteRate(rateId) {
            this.room.rates = this.room.rates.filter((r) => r.id !== rateId);
        },
        onAddRoomRate(rate) {
            this.room.rates.push(rate);
        },
        onToRent(room) {
            if (room.rates.length > 0) {
                window.location.href = `/hotels/reception/${room.id}/rent`;
            } else {
                this.room = room;
                this.openModalRoomRates = true;
            }
        },
        searchRooms() {
            this.loading = true;
            let form = {
                hotel_status_room: this.hotel_status_room,
                hotel_name_room: this.hotel_name_room,
                hotel_floor_id: this.hotel_floor_id,

            }
            this.$http
                .post("/hotels/reception/search", form)
                .then((response) => {
                    // console.error(response.data)
                    this.items = response.data.rooms;

                })
                .finally(() => {
                    this.loading = false;
                })
        },
        onFilterByStatus(status = "") {
            // Si se presiona dos veces la misma opcion, se cancelaria
            if(this.hotel_status_room == status){
                this.hotel_status_room = null
            }else {
                this.hotel_status_room = status
            }
            this.searchRooms()
            return null;
            this.loading = true;
            const params = {
                status,
                hotel_floor_id: this.hotel_floor_id,
            };
            this.$http
                .get("/hotels/reception", {params})
                .then((response) => {
                    this.items = response.data.rooms;
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        onGetColorStatus(status) {
            if (status === "DISPONIBLE") {
                return "btn-success";
            } else if (status === "MANTENIMIENTO") {
                return "btn-warning";
            } else if (status === "OCUPADO") {
                return "btn-danger";
            } else if (status === "LIMPIEZA") {
                return "btn-info";
            }
            return "";
        },
    },
};
</script>
