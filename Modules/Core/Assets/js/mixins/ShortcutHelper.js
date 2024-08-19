export default {
    methods: {
        pushRoute(route) {
            this.$router.push(route);
        },
        convertTitleToName(title) {
            return title.split(' ').join('_').toLowerCase()
        }
    },
};
