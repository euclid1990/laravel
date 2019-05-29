<template>
  <div>
    <nav class="navbar navbar-expand navbar-light bg-light">
      <a class="navbar-brand offset-1" href="#">SunOS</a>
      <div class="collapse navbar-collapse">
        <div class="navbar-nav col-12 container">
          <div v-if="isLogin" class="col-12 navbar-container">
            <button class="btn btn-light">Home</button>
          </div>
          <div v-else class="login-section col-xl-3 offset-xl-9 col-md-5 offset-md-7 col-sm-7 offset-sm-5">
            <button
              class="btn btn-light login-section__button"
              v-on:click="$router.push('/login')"
              >
              Login
            </button>
            <button
              class="btn btn-light login-section__button"
              v-on:click="$router.push('/register')"
              >
              Register
            </button>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>

<script>
export default {
  name: 'navbar',

  components: {
  },

  data: () => ({
    isLogin: false
  }),
}
</script>

<style lang="scss" scoped>
.login-section {
  flex-direction: row-reverse;

  &__button {
    margin: 0px 10px;
    border: 1q solid #585858;
  }
}

.navbar-container {
  display: flex;
}
</style>
