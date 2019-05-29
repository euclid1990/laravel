<template>
  <div>
    <div class="row">
      <registeration></registeration>
    </div>
  </div>
</template>

<script>
import Registeration from '@/components/Login/Registeration'
import RedirectIfAuthenticated from '@/mixins/RedirectIfAuthenticated'

export default {
  name: 'RegisterPage',

  mixins: [RedirectIfAuthenticated],

  components: {
    Registeration,
  },

  created() {
    this.__protectRoute()
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
