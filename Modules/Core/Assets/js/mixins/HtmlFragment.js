
import Vue from "vue";

import VueI18n from "vue-i18n";

export default {
    methods: {
        html_fragment(data) {
            const currentLocale = window.AsgardCMS.currentLocale;
            
            Vue.use(VueI18n);
            const messages = {
            [currentLocale]: window.AsgardCMS.translations,
            };
            
            const i18n = new VueI18n({
            locale: currentLocale,
            messages,
            });

            const nodes = new Vue({
                i18n,
                template: `<div>${data}</div>`,
            }).$mount();
            
            return nodes.$el;
        }
    }
};
