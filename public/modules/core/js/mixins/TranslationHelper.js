export default {
    methods: {
        trans(string, object = {}) {
            // Makes a string: core.button.cancel | core.button.created at
            // to: core["button.cancel"] | core["button.created at"]
            const array = string.split('.');
            
            if (array.length < 2) { return this.$t(string); }

            const first = array.splice(0, 1);
            const key = array.join('.');
            let translation = this.$t(`${first}['${key}']`) 
            Object.entries(object).forEach(([key, item]) => {
                translation = translation.split(':' + key).join(item)
            });

            return translation;
        }
    },
};
