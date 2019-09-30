<template>
  <div class="auth-panel col-12">
    <div class="auth-panel__wrapper col-xl-6 col-md-8 col-10">
      <div class="auth-panel__wrapper__header col-6__header">
        <div class="col-4">
          {{ $t('auth.labels.reset_password') }}
        </div>
      </div>
      <div>
        <form
          class="form container"
          @submit.prevent="validateFormData"
        >
          <div
            v-if="error"
            class="alert alert-danger col-sm-10 offset-sm-1 error"
          >
            {{ errorMessage }}
          </div>
          <div class="form-group row form-row">
            <label
              for="password"
              class="col-4 col-form-label"
            >{{ $t('auth.labels.password') }}</label>
            <div class="col-6">
              <input
                id="password"
                ref="password"
                v-model="password"
                v-validate="'required|min:6'"
                type="password"
                class="form-control"
                name="password"
                :placeholder="$t('auth.labels.password')"
              >
            </div>
          </div>
          <div class="form-group row form-row">
            <label
              for="password"
              class="col-4 col-form-label"
            >{{ $t('auth.labels.confirm_password') }}</label>
            <div class="col-6">
              <input
                id="password-confirmation"
                v-model="password_confirmation"
                v-validate="'required|min:6|confirmed:password'"
                type="password"
                class="form-control"
                name="password_confirmation"
                :placeholder="$t('auth.labels.confirm_password')"
                data-vv-as="password"
              >
            </div>
          </div>
          <div class="form-row">
            <div class="offset-4">
              <button class="btn btn-primary">
                {{ $t('auth.labels.reset_password') }}
              </button>
            </div>
          </div>
        </form>
      </div>
      <div class="footer" />
    </div>
  </div>
</template>

<script>
import swal from '@/utils/swal'
import { $t } from '@/utils/i18n'

export default {
  name: 'ResetPassword',

  data: () => ({
    token: '',
    password: '',
    password_confirmation: '',
    errorMessage: '',
    error: false
  }),

  methods: {
    validateFormData() {
      this.errorMessage = null
      this.$validator.validateAll().then(result => {
        if (result) {
          this.error = false
          this.resetPassword()
        } else {
          this.error = true
          this.errorMessage = this.errors.first('password') ||
            this.errors.first('password_confirmation')
        }
      })
    },

    async resetPassword() {
      const data = {
        password: this.password,
        password_confirmation: this.password_confirmation,
        token: this.$route.params.token
      }
      await this.$store.dispatch('auth/resetPassword', data)
      this.error = false
      swal('success', {
        text: $t('auth.message.reset_password.text')
      }, () => {
        this.$router.push({ name: 'login' })
      })
    }
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
