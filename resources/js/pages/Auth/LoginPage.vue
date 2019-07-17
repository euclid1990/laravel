<template>
  <div>
    <div class="row">
      <authentication />
    </div>
  </div>
</template>

<script>
import Authentication from '@/components/Login/Authentication'
import RedirectIfAuthenticated from '@/mixins/RedirectIfAuthenticated'

export default {
  name: 'LoginPage',

  components: {
    Authentication
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
