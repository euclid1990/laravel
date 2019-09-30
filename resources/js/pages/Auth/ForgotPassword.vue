<template>
  <div>
    <div class="row">
      <forgot-password-component />
    </div>
  </div>
</template>

<script>
import RedirectIfAuthenticated from '@/mixins/RedirectIfAuthenticated'
import ForgotPasswordComponent from '@/components/Login/ForgotPassword'

export default {
  name: 'ForgotPassword',

  components: {
    ForgotPasswordComponent
  },

  mixins: [RedirectIfAuthenticated],

  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.__protectRoute()
    })
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
