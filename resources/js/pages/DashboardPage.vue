<template>
  <div>
    <img
      alt="Vue logo"
      src="https://upload.wikimedia.org/wikipedia/commons/9/95/Vue.js_Logo_2.svg"
      class="logo"
    >
    <div class="w-100" />
    <label
      for=""
      class="dashboad-label"
    >{{ $t('pages.title.dashboard') }}</label>
  </div>
</template>

<script>
import { $t } from '@/utils/i18n'
export default {
  name: 'DashboardPage',
  components: {
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.logo {
  height: 100px;
  width: 100px;
}

.dashboard-label {
  display: block;
}
</style>
