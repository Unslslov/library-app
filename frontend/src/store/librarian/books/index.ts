import apiQuary from "../../../plugins/axios";
import router from "../../../router";

interface Books {
  id: number,
  title: string,
  author: string,
  available_copies: number  
}

interface Book {
  id: number,
  title: string,
  author: string,
  genre: string,
  publisher: string,
  year_published: string,
  available_copies: number,
  total_copies: number,
  description: string | null,
  image_url: string | null
}

export default {
  namespaced: true,
  state: () => ({
    books: [] as Books[],
    book: {} as Book
  }),

  mutations: {
    SET_BOOKS(state: any, books: Books[]) {
      state.books = books;
    },  
    SET_BOOK(state: any, book: Book) {
      state.book = book;
    },
    DELETE_BOOK(state: any, id: number) {
      state.books = state.books.filter((book: Books) => book.id !== id);
    }
  },

  actions: {
  async fetchBooks({ commit }: any) {
    try {
      const response = await apiQuary.get('/librarian/books');
      commit('SET_BOOKS', response.data.data);
    } catch (err) {
      console.error('Ошибка загрузки книг:', err);
    }
  },

  async deleteBook({ commit, dispatch }: {commit: any, dispatch: any}, id: number) {
    try {
      await apiQuary.delete(`/librarian/books/${id}`);
      commit('DELETE_BOOK', id);
      dispatch('fetchBooks')
    } catch (err) {
      console.error('Книга не найден:', err);
    }
  },

  async fetchBook({ commit }: any, id: number) {
    try {
      const response = await apiQuary.get(`/librarian/books/${id}`);
      commit('SET_BOOK', response.data.data)
    } catch (err) {
      console.error('Ошибка загрузки клиента: ', err);
    }
  },

  async editBook ({}, {id, data} : {id: number, data: any}) {
    try {
      await apiQuary.patch(`/librarian/books/${id}/edit`, data)
        .then( () => {
          router.push( {name:'librarian.book.show', params: {id:id}} )
        });
    } catch (err) {
      console.error('Ошибка загрузки книги: ', err);
    }
  },
  async createBook ({}, data: any)
  {
    try {
      await apiQuary.post(`/librarian/books`, data)
      .then(() => {
          router.push({name: 'librarian.book.index'});
      })
    }
    catch (err) {
      console.error('Ошибка загрузки книги: ', err);
    }
  }
}, 
getters: {
  allBooks: (state: any) => state.books,
  book: (state: any) => state.book,
}
}