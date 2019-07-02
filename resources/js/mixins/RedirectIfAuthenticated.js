import { mapGetters } from 'vuex'

export default {
  computed: {
    ...mapGetters({
      check: 'auth/check'
    }),
  },

  methods: {
    __protectRoute() {
      if (this.check) {
        this.__redirect()
      }
    },

    __redirect() {
      const redirect = {
        'path': this.$router.resolve({name: 'dashboard'}).href
      }

      this.$router.push(redirect)
    },
  },
}
