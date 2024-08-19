//import componenet pelanggan SLA
import TicketSessionSla from "./components/sla/session/TicketSessionSla.vue";
import TicketIndexSla from "./components/sla/TicketIndexSla.vue";
import TicketCreateSla from "./components/sla/TicketCreateSla.vue";

//import component pelanggan suspend
import TicketSessionSuspend from "./components/suspend/session/TicketSessionSuspend.vue";
import TicketIndexSuspend from "./components/suspend/TicketIndexSuspend.vue";
import TicketCreateSuspend from "./components/suspend/TicketCreateSuspend.vue";

const locales = window.AsgardCMS.locales;

export default [
  //router vue for sla
  {
    path: "/ticket/sla",
    name: "admin.ticket.sla.index",
    component: TicketIndexSla,
  },
  {
    path: "/ticket/sla/create",
    name: "admin.ticket.sla.create",
    component: TicketCreateSla,
  },
  {
    path: "/ticket/sla/session/:id",
    name: "admin.ticket.sla.session",
    component: TicketSessionSla,
  },

  //router vue for suspend
  {
    path: "/ticket/suspend",
    name: "admin.ticket.suspend.index",
    component: TicketIndexSuspend,
  },
  {
    path: "/ticket/suspend/create",
    name: "admin.ticket.suspend.create",
    component: TicketCreateSuspend,
  },
  {
    path: "/ticket/suspend/create/:pelanggan",
    name: "admin.ticket.suspend.create.with.params",
    component: TicketCreateSuspend,
  },
  {
    path: "/ticket/suspend/session/:id",
    name: "admin.ticket.suspend.session",
    component: TicketSessionSuspend,
  },
];
