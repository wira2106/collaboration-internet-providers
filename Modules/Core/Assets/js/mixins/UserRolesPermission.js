export default {
    methods: {
        getRolesPermission(users=null) {
            return new Promise((resolve, reject) => {
                let routeUri = route('api.user.user.roles', { user: users });
                var self = this;
                axios.post(routeUri)
                .then((response) => {
                    self.user_company = response.data.company;
                    self.user_role = response.data.name;
                    self.user_permission = response.data.permission;
                    resolve(response.data);
                }).catch(error => {
                    console.log(error);
                    reject(error)
                })

            })
        },
    }
};
