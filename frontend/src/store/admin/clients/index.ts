import apiQuary from "../../../plugins/axios";
import router from "../../../router";

interface Client {
  id: number;
  name: string;
  email: string;
}

export default {
  namespaced: true,
  state: () => ({
    clients: [] as Client[],
    client: {} as Client
  }),
  mutations: {
    SET_CLIENTS(state: any, clients: Client[]) {
      state.clients = clients;
    },  
    SET_CLIENT(state: any, client: Client) {
      state.client = client;
    },
    DELETE_CLIENT(state: any, id: number) {
      state.clients = state.clients.filter((client: Client) => client.id !== id);
    }
  },
  actions: {
  async fetchClients({ commit }: any) {
    try {
      const response = await apiQuary.get('/admin/clients');
      commit('SET_CLIENTS', response.data.data);
    } catch (err) {
      console.error('Ошибка загрузки клиентов:', err);
    }
  },

  async deleteClient({ commit }: any, id: number) {
    try {
      await apiQuary.delete(`/admin/clients/${id}`);
      commit('DELETE_CLIENT', id);
    } catch (err) {
      console.error('Клиент не найден:', err);
    }
  },

  async fetchClient({ commit }: any, id: number) {
    try {
      const response = await apiQuary.get(`/admin/clients/${id}`);
      commit('SET_CLIENT', response.data.data)
    } catch (err) {
      console.error('Ошибка загрузки клиента: ', err);
    }
  },

  async editClient ({}, {id, data} : {id: number, data: any}) {
    try {
      await apiQuary.patch(`/admin/clients/${id}/edit`, data)
        .then( () => {
          router.push( {name:'client.show', params: {id:id}} )
        });
    } catch (err) {
      console.error('Ошибка загрузки клиента: ', err);
    }
  },
  async createClient ({}, data: any)
  {
    try {
      await apiQuary.post(`/admin/clients`, data)
      .then(() => {
          router.push({name: 'client.index'});
      })
    }
    catch (err) {
      console.error('Ошибка загрузки клиента: ', err);
    }
  },
  async resetPassword ({}, id: number)
  {
    try {
      await apiQuary.patch(`/admin/clients/${id}/reset-password`)
      .then(() => {
          router.push({name: 'client.index'});
      })
    }
    catch (err) {
      console.error('Ошибка загрузки клиента: ', err);
    }
  }
}, 
getters: {
  allClients: (state: any) => state.clients,
  client: (state: any) => state.client,
}
}