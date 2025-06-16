import apiQuary from "../../../plugins/axios";

interface Loan {
  id: number;
  loan: {
    id: number;
    title: string;
  };
  user: {
    id: number;
    name: string;
  };
  loaned_at: Date;
  reservation_id: number;
}

export default {
  namespaced: true,
  state: () => ({
    loans: [] as Loan[],
    loan: {} as Loan,
  }),

  mutations: {
    SET_LOANS(state: any, loans: Loan[]) {
      state.loans = loans;
    }, 
    SET_LOAN(state: any, loan: Loan) {
      state.loan = loan;
    },   
    DELETE_LOANS(state: any, id: number) {
      state.loans = state.loans.filter((loan: Loan) => loan.id !== id);
    }
  },

  actions: {
  async fetchLoans({ commit }: any) {
    try {
      const response = await apiQuary.get('/librarian/loans');

      const loans = response.data.data.map((loan: Loan) => ({
      ...loan,
       loaned_at: new Date(loan.loaned_at),
    }));

      commit('SET_LOANS', loans);
    } catch (err) {
      console.error('Ошибка загрузки книг:', err);
    }
  },

  async deleteLoan({ commit, dispatch }: {commit: any, dispatch: any}, id: number) {
    try {
      await apiQuary.delete(`/librarian/loans/${id}`);
      commit('DELETE_LOANS', id);
      dispatch('fetchLoans')
    } catch (err) {
      console.error('Книга не найден:', err);
    }
  },
  async createLoan ({dispatch} : any, data: any)
  {
    try {
      await apiQuary.post(`/librarian/loans`, data)

      dispatch('fetchLoans');
    }
    catch (err) {
      console.error('Ошибка загрузки книги: ', err);
    }
  }
}, 
getters: {
  allLoans: (state: any) => state.loans,
  loan: (state: any) => state.loan
}
}