import SlotInstalasiForm from './components/slotinstalasi/SlotInstalasiForm.vue'

export default [
    {
        path: '/company/slot-instalasi',
        name: 'admin.company.slotinstalasi.index',
        component:SlotInstalasiForm,
        props: {
            pageTitle: 'title.slot_instalasi'
        }
    }
]