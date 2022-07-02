 <template>
    <div class="card">
      <div class="card-header bg-info">
        <h3 class="my-0">URL de descarga de Aplicacion móvil</h3>
      </div>
      <div class="card-body">
        <form autocomplete="off" @submit.prevent="submit">
          <div class="form-body">
              <div class="col-md-12">
                <div class="form-group" :class="{'has-danger': errors.apk_url}">
                  <label class="control-label">
                    URL
                  </label>
                  <el-input v-model="form.apk_url"></el-input>
                  <small
                    class="form-control-feedback"
                    v-if="errors.apk_url"
                    v-text="errors.apk_url[0]"
                  ></small>
                </div>
              </div>
          </div>
          <div class="form-actions text-right pt-2">
            <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
          </div>
        </form>
      </div>
    </div>
</template>


<script>
export default {
  data() {
    return {
      loading_submit: false,
      resource: "configurations",
      errors: {},
      form: {},
    };
  },
  async created() {
    await this.initForm();

    await this.$http.get(`/${this.resource}/apkurl`).then(response => {
        this.form.apk_url = response.data.apk_url;
    });
  },
  methods: {
    initForm() {
      this.errors = {};
      this.form = {
        apk_url: null
      };
    },
    submit() {
      this.loading_submit = true;
      this.$http
        .post(`/${this.resource}`, this.form)
        .then(response => {
          if (response.data.success) {
            this.$message.success(response.data.message);
          } else {
            this.$message.error(response.data.message);
          }
        })
        .catch(error => {
          if (error.response.status === 422) {
            this.errors = error.response.data;
          } else {
            console.log(error);
          }
        })
        .then(() => {
          this.loading_submit = false;
        });
    },
  }
};
</script>

