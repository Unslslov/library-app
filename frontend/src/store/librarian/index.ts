import books from './books';
import loans from './loans';
import clients from './clients';
import reservations from './reservations';

export default {
  namespaced: true,
  modules: {
    books,
    loans,
    clients,
    reservations
  }
};