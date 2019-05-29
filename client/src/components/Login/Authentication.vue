<template>
  <div class="auth-panel col-12">
    <div class="auth-panel__wrapper col-xl-6 col-md-8 col-10">
      <div class="auth-panel__wrapper__header col-6__header">
        <div class="col-1">Login</div>
      </div>
      <div>
        <form class="form container" @submit.prevent="validateFormData">
          <div v-if="error" class="alert alert-danger col-sm-10 offset-sm-1 error">{{ errorMessage }}</div>
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
                />
            </div>
          </div>
          <div class="form-row">
            <div class="col-xl-4 offset-4 text-left">
              <input type="checkbox" aria-label="Checkbox for following text input">
              <label for="password" class="col-form-label form-row__label">Remember me</label>
            </div>
          </div>
          <div class="form-row">
            <div class="offset-4">
              <button class="btn btn-primary">Login</button>
              <a href="#" class="form-row__label col-sm-6">Forgot Your Password?</a>
            </div>
          </div>
        </form>
      </div>
      <div class="footer"></div>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import RedirectIfAuthenticated from '@/mixins/RedirectIfAuthenticated'

export default {
  name: 'Authentication',

  mixins: [RedirectIfAuthenticated],

  data: () => ({
    email: '',
    password: '',
    errorMessage: '',
    error: false,
  }),

  methods: {
    ...mapActions('Global', [
      'dispatchLogin',
    ]),

    validateFormData() {
      this.errorMessage = null
      this.$validator.validateAll().then(result => {
        if (result) {
          this.error = false
          this.login()
        } else {
          this.error = true
          this.errorMessage = this.errors.first('email') || this.errors.first('password')
        }
      })
    },

    login() {
      const data = {
        email: this.email,
        password: this.password,
      }

      this.dispatchLogin(data)
        .then(() => {
          this.error = false
          this.__redirect()
        })
    },
  }
}
</script>

<style lang="scss" scoped>
@import '~@/assets/scss/app';

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