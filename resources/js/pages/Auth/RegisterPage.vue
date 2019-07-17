<template>
  <div>
    <div class="row">
      <registeration />
    </div>
  </div>
</template>

<script>
import RedirectIfAuthenticated from '@/mixins/RedirectIfAuthenticated'
import Registeration from '@/components/Login/Registeration'

export default {
  name: 'RegisterPage',

  components: {
    Registeration
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
