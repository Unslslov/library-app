import apiQuary from "../../../plugins/axios";

interface Client {
  id: number,
  name: string,
  email: string
}

export default {
  namespaced: true,
  state: () => ({
    clients: [] as Client[],
  }),

  mutations: {
    SET_CLIENTS(state: any, clients: Client[]) {
      state.clients = clients;
    },  
  },

  actions: {
  async fetchClients({ commit }: any) {
    try {
      const response = await apiQuary.get('/librarian/clients');
      commit('SET_CLIENTS', response.data.data);
    } catch (err) {
      console.error('Ошибка загрузки книг:', err);
    }
  },
}, 
getters: {
  allClients: (state: any) => state.clients,
}
}