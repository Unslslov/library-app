import apiQuary from "../../../plugins/axios";

interface Role {
  id: number;
  name: string;
}

export default {
  namespaced: true,
  state: () => ({
    roles: [] as Role[],
  }),
  mutations: {
    SET_ROLES(state: any, roles: Role[]) {
      state.roles = roles;
    },
  },
  actions: {
    async fetchRoles({ commit }: any) {
      try {
        const response = await apiQuary.get('/admin/roles');
        commit('SET_ROLES', response.data);
      } catch (err) {
        console.error('Ошибка загрузки ролей:', err);
      }
    },
  },
  getters: {
    allRoles: (state: any) => state.roles,
  }
}