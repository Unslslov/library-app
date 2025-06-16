import apiQuary from "../../../plugins/axios";

interface Reservation {
  id: number;
  book: {
    id: number;
    title: string;
  };
  user: {
    id: number;
    name: string;
  };
  expires_at: Date;
  status: string;
}

export default {
  namespaced: true,
  state: () => ({
    reservations: [] as Reservation[],
    reservation: {} as Reservation,
  }),

  mutations: {
    SET_RESERVATIONS(state: any, reservations: Reservation[]) {
      state.reservations = reservations;
    }, 
    SET_RESERVATION(state: any, reservation: Reservation) {
      state.reservation = reservation;
    },   
    DELETE_RESERVATION(state: any, id: number) {
      state.reservations = state.reservations.filter((reservation: Reservation) => reservation.id !== id);
    }
  },

  actions: {
  async fetchReservations({ commit }: any) {
    try {
      const response = await apiQuary.get('/librarian/reservations');

      const reservations = response.data.data.map((reservation: Reservation) => ({
      ...reservation,
       expires_at: new Date(reservation.expires_at),
    }));

      commit('SET_RESERVATIONS', reservations);
    } catch (err) {
      console.error('Ошибка загрузки книг:', err);
    }
  },

  async deleteReservation({ commit, dispatch }: {commit: any, dispatch: any}, id: number) {
    try {
      await apiQuary.delete(`/librarian/reservations/${id}`);
      commit('DELETE_RESERVATION', id);
      dispatch('fetchreservations')
    } catch (err) {
      console.error('Книга не найден:', err);
    }
  },
  async createReservation ({dispatch} : any, data: any)
  {
    try {
      await apiQuary.post(`/librarian/reservations`, data)

      dispatch('fetchReservations');
    }
    catch (err) {
      console.error('Ошибка загрузки книги: ', err);
    }
  }
}, 
getters: {
  allReservations: (state: any) => state.reservations,
  reservation: (state: any) => state.reservation
}
}