export default {
	data() {
    return {
	    	user_profile: window.AsgardCMS.profile,
	    	user_company: window.AsgardCMS.company,
	    	user_roles: window.AsgardCMS.roles,
    	}
    },
    methods: {
        hasAccess(permission) {
            let user_permissions = window.AsgardCMS.permissions
            if(user_permissions.hasOwnProperty(permission)) return user_permissions[permission]
            return false;
        }
    },
};
