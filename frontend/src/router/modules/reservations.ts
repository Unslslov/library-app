import ReservationForm from '../../components/books/librarian/ReservationForm.vue';
import ManageReservations from '../../views/librarian/ManageReservations.vue';
import type { RouteRecordRaw } from 'vue-router';

const reservationLibrarianRoutes: RouteRecordRaw[] = [
  { 
    path: '/librarian/reservations',
    name: 'librarian.reservations.index',
    component: ManageReservations,
  },
  { 
    path: '/librarian/reservations/create', 
    name: 'librarian.reservations.create', 
    component: ReservationForm,
  },
];

export default reservationLibrarianRoutes;