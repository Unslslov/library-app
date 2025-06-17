<template>
  <div class="bg-gray-900 shadow-lg rounded-xl p-6 mb-6 transition-all duration-300">
    <h3 class="text-xl font-bold text-white mb-4">Выдать книгу</h3>

    <form @submit.prevent="createLoan" class="space-y-4">
      <div>
        <label for="book_id" class="block text-sm font-medium text-gray-400 mb-1">Книга</label>
        <select
          v-model="loan.book_id"
          id="book_id"
          required
          class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-md text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="" disabled selected>Выберите книгу</option>
          <option v-for="book in books" :key="book.id" :value="book.id">
            {{ book.title }} (Доступно: {{ book.available_copies }})
          </option>
        </select>
      </div>

      <div>
        <label for="user_id" class="block text-sm font-medium text-gray-400 mb-1">Пользователь</label>
        <select
          v-model="loan.user_id"
          id="user_id"
          required
          class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-md text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="" disabled selected>Выберите пользователя</option>
          <option v-for="user in users" :key="user.id" :value="user.id">
            {{ user.name }} ({{ user.email }})
          </option>
        </select>
      </div>

      <div>
        <button
          type="submit"
          class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200"
        >
          Выдать книгу
        </button>
      </div>

      <div v-if="error" class="text-red-500 text-sm">
        {{ error }}
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import router from '../../../router';
import { useStore } from 'vuex';

const store = useStore();

const loan = computed(() => store.getters['librarian/loans/loan']);
const books = computed(() => store.getters['librarian/books/allBooks']);
const users = computed(() => store.getters['librarian/clients/allClients'])

const error = ref('');
const fetchBooks = async () => {
  store.dispatch('librarian/books/fetchBooks');
}

const fetchClients = async () => {
  store.dispatch('librarian/clients/fetchClients');
} 

onMounted(async () => {
  try {
    fetchBooks();
    fetchClients();
  } catch (err) {
    error.value = 'Ошибка загрузки данных';
  }

  store.commit('librarian/loans/SET_LOAN', {
    id: null,
  book: {
    id: null,
    title: ''
  },
  user: {
    id: null,
    name: ''
  },
  loaned_at: new Date(),
  reservation_id: null
  })
});

const createLoan = async () => {
  try {
    await store.dispatch('librarian/loans/createLoan', loan.value)

    alert('Книга успешно выдана!');
    router.push('/librarian/loans');
  } catch (err) {
    if (err) {
      error.value = 'Ошибка при выдаче книги', err;
    } else {
      error.value = 'Сетевая ошибка';
    }
  }
};
</script>