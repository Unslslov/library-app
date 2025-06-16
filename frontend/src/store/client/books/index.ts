import apiQuary from "../../../plugins/axios";

interface Books {
  id: number,
  title: string,
  author: string,
  available_copies: number  
}

interface Book {
  id: number
  title: string
  author: string
  genre: string
  publisher: string
  year_published: string
  available_copies: number
  total_copies: number
  description: string | null
  image_url: string | null
}

interface Filters {
  author?: string;
  genre?: string;
  publisher?: string;
}

export default {
  namespaced: true,
  state: () => ({
    books: [] as Books[],
    book: {} as Book | null
  }),

  mutations: {
    SET_BOOKS(state: any, books: Books[]) {
      state.books = books;
    },  
    SET_BOOK(state: any, book: Book) {
      state.book = book;
    },
  },

  actions: {
  async fetchBooks({ commit }: any, filters: {}) {
    const params = Object.fromEntries(
          Object.entries(filters).filter(([_, v]) => v !== '')
        );
    try {
      const response = await apiQuary.get('/client/books', { params });

      commit('SET_BOOKS', response.data.data);
    } catch (err) {
      console.error('Ошибка загрузки книг:', err);
    }
  },

  async fetchBook({ commit }: any, id: number) {
    try {
      const response = await apiQuary.get(`/client/books/${id}`);
      commit('SET_BOOK', response.data.data)
    } catch (err) {
      console.error('Ошибка загрузки клиента: ', err);
    }
  },
}, 
getters: {
  allBooks: (state: any) => state.books,
  book: (state: any) => state.book,
}
}