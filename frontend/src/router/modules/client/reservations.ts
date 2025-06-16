import type { RouteRecordRaw } from 'vue-router';
import Reservations from '../../../views/client/Reservations.vue';

const reservationClientRoutes: RouteRecordRaw[] = [
  { 
    path: '/reservations', 
    name: 'client.reservations.index', 
    component: Reservations,
  },
];

export default reservationClientRoutes;