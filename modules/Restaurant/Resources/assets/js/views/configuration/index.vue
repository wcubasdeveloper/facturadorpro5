<template>
    <div>
      <div class="page-header pr-0">
        <h2><a href="#"><i class="fas fa-cogs"></i></a></h2>
        <ol class="breadcrumbs">
          <li class="active"><span>Configuración</span></li>
          <li><span class="text-muted">Restaurante</span></li>
        </ol>
      </div>
      <template>
        <form autocomplete="off">
          <el-tabs v-model="activeName" type="border-card" class="rounded">
            <el-tab-pane class="mb-3"  name="first">
              <span slot="label">Visual</span>
              <div class="row">
                <div class="col-sm-6 col-md-4 mt-4">
                  <label class="control-label">
                    Habilitar menú POS
                  </label>
                  <div :class="{'has-danger': errors.menu_pos}"
                        class="form-group">
                    <el-switch v-model="form.menu_pos"
                                active-text="Si"
                                inactive-text="No"
                                @change="submit"></el-switch>
                    <small v-if="errors.menu_pos"
                            class="form-control-feedback"
                            v-text="errors.menu_pos[0]"></small>
                  </div>
                </div>
                <div class="col-sm-6 col-md-4 mt-4">
                  <label class="control-label">
                    Habilitar menú Mesas
                  </label>
                  <div :class="{'has-danger': errors.menu_tables}"
                        class="form-group">
                    <el-switch v-model="form.menu_tables"
                                active-text="Si"
                                inactive-text="No"
                                @change="submit"></el-switch>
                    <small v-if="errors.menu_tables"
                            class="form-control-feedback"
                            v-text="errors.menu_tables[0]"></small>
                  </div>
                </div>
                <div class="col-sm-6 col-md-4 mt-4">
                  <label class="control-label">
                    Habilitar menú Pedidos
                  </label>
                  <div :class="{'has-danger': errors.menu_order}"
                        class="form-group">
                    <el-switch v-model="form.menu_order"
                               active-text="Si"
                               inactive-text="No"
                               @change="submit"></el-switch>
                    <small v-if="errors.menu_order"
                           class="form-control-feedback"
                           v-text="errors.menu_order[0]"></small>
                  </div>
                </div>
                <div class="col-sm-6 col-md-4 mt-4">
                  <label class="control-label">
                    Habilitar Comanda/Bar
                  </label>
                  <div :class="{'has-danger': errors.menu_bar}"
                        class="form-group">
                    <el-switch v-model="form.menu_bar"
                                active-text="Si"
                                inactive-text="No"
                                @change="submit"></el-switch>
                    <small v-if="errors.menu_bar"
                            class="form-control-feedback"
                            v-text="errors.menu_bar[0]"></small>
                  </div>
                </div>
                <div class="col-sm-6 col-md-4 mt-4">
                  <label class="control-label">
                    Habilitar Comanda/Cocina
                  </label>
                  <div :class="{'has-danger': errors.menu_kitchen}"
                        class="form-group">
                    <el-switch v-model="form.menu_kitchen"
                                active-text="Si"
                                inactive-text="No"
                                @change="submit"></el-switch>
                    <small v-if="errors.menu_kitchen"
                            class="form-control-feedback"
                            v-text="errors.menu_kitchen[0]"></small>
                  </div>
                </div>
                <div class="col-sm-6 col-md-6 mt-4">
                  <div :class="{'has-danger': errors.first_menu}"
                        class="form-group">
                    <label class="control-label">Menu inicial
                    </label>
                    <el-select v-model="form.first_menu"
                                filterable
                                @change="submit">
                      <el-option v-for="option in menu_list"
                                  :key="option.id"
                                  :label="option.description"
                                  :value="option.name"></el-option>
                    </el-select>
                    <small v-if="errors.first_menu"
                            class="form-control-feedback"
                            v-text="errors.first_menu[0]"></small>
                  </div>
                </div>
                <div class="col-sm-6 col-md-6 mt-4">
                  <div :class="{'has-danger': errors.tables_quantity}"
                        class="form-group">
                    <label class="control-label">Cantidad de mesas
                    </label>
                    <el-slider
                      v-model="form.tables_quantity"
                      :step="1"
                      :max="20"
                      show-stops
                      @change="submit">
                    </el-slider>
                    <small v-if="errors.tables_quantity"
                            class="form-control-feedback"
                            v-text="errors.tables_quantity[0]"></small>
                  </div>
                </div>
              </div>
            </el-tab-pane>
            <el-tab-pane class="mb-3"  name="second">
              <span slot="label">Usuarios</span>
              <div class="row d-flex align-items-end">
                <div class="col-sm-4 col-md-4 mt-4">
                  <div :class="{'has-danger': errors.user_id}"
                        class="form-group">
                    <label class="control-label">Usuario
                    </label>
                    <el-select v-model="form_role.user_id"
                                filterable>
                      <el-option v-for="option in users"
                                  :key="option.id"
                                  :label="option.name"
                                  :value="option.id"></el-option>
                    </el-select>
                    <small v-if="errors.user_id"
                            class="form-control-feedback"
                            v-text="errors.user_id[0]"></small>
                  </div>
                </div>
                <div class="col-sm-4 col-md-4 mt-4">
                  <div :class="{'has-danger': errors.role_id}"
                        class="form-group">
                    <label class="control-label">Rol
                    </label>
                    <el-select v-model="form_role.role_id"
                                filterable>
                      <el-option v-for="option in roles"
                                  :key="option.id"
                                  :label="option.name"
                                  :value="option.id"></el-option>
                    </el-select>
                    <small v-if="errors.role_id"
                            class="form-control-feedback"
                            v-text="errors.role_id[0]"></small>
                  </div>
                </div>
                <div class="col-sm-4 col-md-4 mt-4">
                  <el-button class="submit" type="primary" @click="sendFormRole" :disabled="isFormRole">Guardar
                  </el-button>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Usuario</th>
                          <th>Correo</th>
                          <th>Rol</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="user in users" :key="user.id">
                          <td>{{user.name}}</td>
                          <td>{{user.email}}</td>
                          <td>{{user.restaurant_role_name }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </el-tab-pane>
          </el-tabs>
        </form>
      </template>
    </div>
</template>

<style>
.el-tabs__header,
.el-tabs__nav-wrap {
    border-top-right-radius: 5px;
    border-top-left-radius: 5px ;
}
</style>

<script>

export default {
    data() {
      return {
        resource: 'restaurant',
        errors: {},
        form: {
          menu_pos: true,
          menu_order: true,
          menu_tables: true,
          menu_bar: true,
          menu_kitchen: true,
          first_menu: 'POS',
          tables_quantity: 15
        },
        activeName: 'first',
        menu_list: [
          {id: 1, description: 'POS', name: 'POS'},
          {id: 2, description: 'Mesas', name: 'TABLES'},
          {id: 3, description: 'Pedidos', name: 'ORDER'},
        ],
        users: {},
        roles: {},
        form_role: {
          user_id: '',
          role_id: ''
        }
      }
    },
    computed: {
      isFormRole: function () {
        return (this.form_role.user_id != '' && this.form_role.role_id != '') ? false : true
      }
    },
    created() {
      this.getRecords();
      this.getUsers();
    },
    methods: {
      getRecords() {
        this.$http.get(`/${this.resource}/configuration/record`).then(response => {
          if (response.data !== '') {
            this.form = response.data.data;
          }
        });
        this.$http.get(`/${this.resource}/get-roles`).then(response => {
          if (response.data !== '') {
            this.roles = response.data.data;
          }
        });
      },
      getUsers() {
        this.$http.get(`/${this.resource}/get-users`).then(response => {
          if (response.data !== '') {
            this.users = response.data.data;
          }
        });
      },
      submit() {
        this.$http.post(`/${this.resource}/configuration`, this.form).then(response => {
          let data = response.data;
          if (data.success) {
            this.$message.success(data.message);
          } else {
            this.$message.error(data.message);
          }
          if (data !== undefined && data.configuration !== undefined) {
            this.form = data.configuration
          }
        }).catch(error => {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors;
          } else {
            console.log(error);
          }
        }).then(() => {
          // this.loading_submit = false;
        });
      },
      sendFormRole() {
        this.$http.post(`/${this.resource}/user/set-role`, this.form_role).then(response => {
          let data = response.data;
          if (data.success) {
            this.$message.success(data.message);
          } else {
            this.$message.error(data.message);
          }
        }).catch(error => {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors;
          } else {
            console.log(error);
          }
        }).then(() => {
          this.getUsers();
          this.form_role.user_id = '';
          this.form_role.role_id = '';
          // this.loading_submit = false;
        });
      }
    }
}
</script>
