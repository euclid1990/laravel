<template>
  <div>
    <b-navbar
      toggleable="lg"
      type="dark"
      variant="info"
    >
      <b-navbar-brand href="#">
        <router-link :to="{name: 'dashboard'}">
          <img
            src="https://sun-asterisk.vn/wp-content/uploads/2019/03/Sun-Logotype-RGB-01.png"
            alt=""
          >
        </router-link>
      </b-navbar-brand>

      <b-navbar-toggle target="nav-collapse" />

      <b-collapse
        id="nav-collapse"
        is-nav
      >
        <b-navbar-nav>
          <b-nav-item href="#">
            <router-link :to="{name: 'dashboard'}">
              Home <span class="sr-only">(current)</span>
            </router-link>
          </b-nav-item>
        </b-navbar-nav>

        <!-- Right aligned nav items -->
        <b-navbar-nav class="ml-auto">
          <b-nav-item-dropdown
            text="Lang"
            right
          >
            <b-dropdown-item href="#">
              EN
            </b-dropdown-item>
          </b-nav-item-dropdown>

          <b-nav-item-dropdown
            v-if="isAuthenticated"
            right
          >
            <!-- Using 'button-content' slot -->
            <template slot="button-content">
              <em>User</em>
            </template>
            <b-dropdown-item href="#">
              <router-link :to="{name: 'profile'}">
                Profile
              </router-link>
            </b-dropdown-item>
            <b-dropdown-item
              href="#"
              @click="logout"
            >
              Logout
            </b-dropdown-item>
          </b-nav-item-dropdown>
          <fragment
            v-else
            right
          >
            <b-nav-item href="#">
              <router-link :to="{name: 'login'}">
                Login
              </router-link>
            </b-nav-item>
            <b-nav-item href="#">
              <router-link :to="{name: 'register'}">
                Register
              </router-link>
            </b-nav-item>
          </fragment>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
  </div>
</template>

<script>

import { mapGetters } from 'vuex'

export default {
  name: 'Navbar',

  components: {
  },

  computed: {
    ...mapGetters({
      isAuthenticated: 'auth/check'
    })
  },
  methods: {
    logout() {
      this.$store.dispatch('auth/logout')
        .then(() => {
          this.$router.push({ name: 'login' })
        })
    }
  }
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
