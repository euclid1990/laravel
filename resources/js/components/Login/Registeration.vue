<template>
  <div class="auth-panel col-12">
    <div class="auth-panel__wrapper col-xl-6 col-md-8 col-10">
      <div class="auth-panel__wrapper__header col-6__header">
        <div class="col-1">Register</div>
      </div>
      <div>
        <form class="form container" @submit.prevent="validateFormData">
          <div v-if="error" class="alert alert-danger col-sm-10 offset-sm-1 error">{{ errorMessage }}</div>
          <div class="form-group row form-row">
            <label for="name" class="col-4 col-form-label">Name</label>
            <div class="col-6">
              <input
                type="text"
                class="form-control"
                id="name"
                name="name"
                v-model="name"
                placeholder="Name"
                v-validate="'required'"
                />
            </div>
          </div>
          <div class="form-group row form-row">
            <label for="email" class="col-4 col-form-label">E-Mail Address</label>
            <div class="col-6">
              <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                v-model="email"
                placeholder="E-Mail Address"
                v-validate="'required|email'"
                />
            </div>
          </div>
          <div class="form-group row form-row">
            <label for="password" class="col-4 col-form-label">Password</label>
            <div class="col-6">
              <input
                type="password"
                class="form-control"
                id="password"
                name="password"
                placeholder="Password"
                v-model="password"
                v-validate="'required|min:6'"
                ref="password"
                />
            </div>
          </div>
          <div class="form-group row form-row">
            <label for="password" class="col-4 col-form-label">Confirm Password</label>
            <div class="col-6">
              <input
                type="password"
                class="form-control"
                id="password-confirmation"
                name="password_confirmation"
                placeholder="Password confirm"
                v-model="password_confirmation"
                v-validate="'required|min:6|confirmed:password'"
                data-vv-as="password"
                />
            </div>
          </div>
          <div class="form-row">
            <div class="offset-4">
              <button class="btn btn-primary">Register</button>
            </div>
          </div>
        </form>
      </div>
      <div class="footer"></div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'Registeration',

  data: () => ({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    errorMessage: '',
    error: false,
  }),

  methods: {
    validateFormData() {
      this.errorMessage = null
      this.$validator.validateAll().then(result => {
        if (result) {
          this.error = false
          this.register()
        } else {
          this.error = true
          this.errorMessage = this.errors.first('email') ||
            this.errors.first('password') ||
            this.errors.first('name') ||
            this.errors.first('password_confirmation')
        }
      })
    },

    register() {
      const data = {
        name: this.name,
        email: this.email,
        password: this.password,
        password_confirmation: this.password_confirmation,
      }
      this.$store.dispatch('auth/register', data)
        .then(() => {
          this.error = false
          this.$router.push({name: 'dashboard'})
        })
    },
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
@import '~sass/app';

.auth-panel {
  display: flex;
  margin-top: 20px;
  justify-content: center;

  &__wrapper {
    @include padding-none();

    border: 1px solid $black;
    border-radius: 7px;
    margin-top: 80px;
    overflow: hidden;

    &__header {
      background: $gray-light;
      padding: 12px 20px;
    }
  }
}

.form-row {
  margin-top: 30px;

  &__label {
    @include padding-none();
    margin-left: 10px;
  }
}

.footer {
  margin-top: 30px;
}

.error {
  margin-top: 30px;
}
</style>
