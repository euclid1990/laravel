<template>
  <div class="auth-panel col-12">
    <div class="auth-panel__wrapper col-xl-6 col-md-8 col-10">
      <div class="auth-panel__wrapper__header col-6__header">
        <div class="col-3">
          {{ $t('auth.labels.forgot_password') }}
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
              for="email"
              class="col-4 col-form-label"
            >{{ $t('auth.labels.email') }}</label>
            <div class="col-6">
              <input
                id="email"
                v-model="email"
                v-validate="'required|email'"
                type="email"
                class="form-control"
                name="email"
                :placeholder="$t('auth.labels.email')"
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
import RedirectIfAuthenticated from '@/mixins/RedirectIfAuthenticated'
import swal from '@/utils/swal'
import { $t } from '@/utils/i18n'

export default {
  name: 'ForgotPassword',
  mixins: [RedirectIfAuthenticated],

  data: () => ({
    email: '',
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
          this.errorMessage = this.errors.first('email')
        }
      })
    },

    async resetPassword() {
      const data = {
        email: this.email
      }

      const response = await this.$store.dispatch('auth/emailResetPassword', data)
      const text = response.message ? response.message : $t('auth.message.email_reset_password.text')
      swal('success', {
        text,
        title: $t('auth.message.email_reset_password.title')
      })
    }
  }
}
</script>

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
