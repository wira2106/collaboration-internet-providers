export default {
  methods: {
    validatorRequired(rules, value, callback) {
      console.log([rules, value, callback]);
      if (!value) {
        if (rules && rules.hasOwnProperty("ref")) {
          if (typeof this.$refs[rules.ref].length != "undefined") {
            this.$refs[rules.ref][0].hasOwnProperty("focus") &&
              this.$refs[rules.ref][0].focus();
          } else {
            this.$refs[rules.ref].hasOwnProperty("focus") &&
              this.$refs[rules.ref].focus();
          }

          this.$root.$emit(`${rules.ref}_required`);
        }
        return callback(new Error(rules.message));
      }
      return callback();
    },

    validatorRequiredJquery(rules, value, callback) {
      if (!value) {
        if (rules && rules.hasOwnProperty("ref")) {
          $(this.$refs[rules.ref]).focus();
        }
        return callback(new Error(rules.message));
      }
      return callback();
    },

    validatorRequiredArray(rules, value, callback) {
      if (value.length == 0) {
        this.$refs[rules.ref].focus();
        return callback(new Error(rules.message));
      }
      return callback();
    },

    validatorUploadGambar(rules, value, callback) {
      // if (value.length == 0) {
      //   $(this.$refs[rules.ref].$el.children[1]).focus();
      //   return callback(new Error(rules.message));
      // }
      return callback();
    },
  },
};
