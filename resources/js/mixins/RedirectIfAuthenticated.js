import { mapGetters } from 'vuex'

export default {
  computed: {
    ...mapGetters({
      authenticated: 'auth/authenticated'
    })
  },

  methods: {
    __protectRoute() {
      if (this.authenticated) {
        this.__redirect()
      }
    },

    __redirect() {
      const redirect = {
        path: this.$router.resolve({ name: 'dashboard' }).href
      }

      this.$router.push(redirect)
    }
  }
}
