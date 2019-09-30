<template>
  <div>
    <div class="row">
      <reset-password-component />
    </div>
  </div>
</template>

<script>
import ResetPasswordComponent from '@/components/Login/ResetPassword'

export default {
  name: 'ResetPassword',

  components: {
    ResetPasswordComponent
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
