<script>
import Vue from "vue";

import VueI18n from "vue-i18n";

const currentLocale = window.AsgardCMS.currentLocale;

Vue.use(VueI18n);
const messages = {
  [currentLocale]: window.AsgardCMS.translations,
};

const i18n = new VueI18n({
  locale: currentLocale,
  messages,
});

export default {
  functional: true,
  props: ["html"],
  render(h, ctx) {
    const nodes = new Vue({
      i18n,
      beforeCreate() {
        this.$createElement = h;
      }, // not necessary, but cleaner imho
      template: `<div>${ctx.props.html}</div>`,
    }).$mount()._vnode.children;
    return nodes;
  },
};
</script>

<style></style>
