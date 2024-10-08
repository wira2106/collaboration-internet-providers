import RoleTable from './components/RoleTable.vue';
import RoleForm from './components/RoleForm.vue';
import UserTable from './components/UserTable.vue';
import UserForm from './components/UserForm.vue';
import UserProfile from './components/UserProfile.vue';
import ApiKeys from './components/ApiKeys.vue';
import StaffTable from './components/StaffTable.vue';
import StaffForm from './components/StaffForm';
import LogTable from './components/Log/LogTable';


const locales = window.AsgardCMS.locales;

export default [
    // Role Routes
    {
        path: '/user/roles',
        name: 'admin.user.roles.index',
        component: RoleTable,
    },
    {
        path: '/user/roles/create',
        name: 'admin.user.roles.create',
        component: RoleForm,
        props: {
            locales,
            pageTitle: 'new-role',
        },
    },
    {
        path: '/user/roles/:roleId/edit',
        name: 'admin.user.roles.edit',
        component: RoleForm,
        props: {
            locales,
            pageTitle: 'title.edit',
        },
    },
    // User Routes
    {
        path: '/user/users',
        name: 'admin.user.users.index',
        component: UserTable,
    },
    {
        path: '/user/users/create',
        name: 'admin.user.users.create',
        component: UserForm,
        props: {
            locales,
            pageTitle: 'title.new-user',
        },
    },
    {
        path: '/user/users/:userId/edit',
        name: 'admin.user.users.edit',
        component: UserForm,
        props: {
            locales,
            pageTitle: 'title.edit-user',
        },
    },
    // Account routes
    {
        path: '/account/profile',
        name: 'admin.user.users.account',
        component: UserProfile,
    },
    {
        path: '/account/api-keys',
        name: 'admin.user.users.account.api-keys',
        component: ApiKeys,
    },
    // Staff routes
    {
        path: '/staff',
        name: 'admin.user.staff.index',
        component: StaffTable
    },
    {
        path: '/staff/create',
        name: 'admin.user.staff.create',
        component: StaffForm
    },
    {
        path: '/staff/:staff/edit',
        name: 'admin.user.staff.edit',
        component: StaffForm
    },

    // Logs
    {
        path: '/user/logs',
        name: 'admin.user.logs.index',
        component:LogTable,
    },
];
