<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { useStore } from 'vuex';
import router from '../../../router';

const store = useStore();

const reservation = computed(() => store.getters['librarian/reservations/reservation']);

const books = computed(() => store.getters['librarian/books/allBooks']);
const users = computed(() => store.getters['librarian/clients/allClients']);

const error = ref('');

const fetchBooks = async () => {
  store.dispatch('librarian/books/fetchBooks');
}

const fetchClients = async () => {
  store.dispatch('librarian/clients/fetchClients');
} 

const createReservation = async () => {
  error.value = '';

  try {
    await store.dispatch('librarian/reservations/createReservation', reservation.value);

    alert('Резервация успешно создана!');
    router.push('/librarian/reservations');
  } catch (err) {
    console.error('Ошибка при создании резервации:', err);
    error.value = 'Ошибка при создании резервации';
  }
};

onMounted(() => {
  try {
    fetchBooks();
    fetchClients();
  } catch (err) {
    error.value = 'Ошибка загрузки данных';
  }

  store.commit('librarian/reservations/SET_RESERVATION', {
    id: null,
  book: {
    id: null,
    title: ''
  },
  user: {
    id: null,
    name: ''
  },
  expires_at: new Date(),
  status: ''
  })
})
</script>

<template>
  <div class="bg-gray-900 shadow-lg rounded-xl p-6 mb-6 transition-all duration-300">
    <h3 class="text-xl font-bold text-white mb-4">Создание бронирования</h3>

    <form @submit.prevent="createReservation" class="space-y-4">
      <div>
        <label for="book_id" class="block text-sm font-medium text-gray-400 mb-1">Книга</label>
        <select
          id="book_id"
          v-model="reservation.book_id"
          required
          class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-md text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none"
        >
          <option value="" disabled selected>Выберите книгу</option>
          <option
            v-for="book in books"
            :key="book.id"
            :value="book.id"
            :disabled="book.available_copies <= 0"
            class="bg-gray-800 text-gray-200"
          >
            {{ book.title }} ({{ book.available_copies > 0 ? `Доступно: ${book.available_copies}` : 'Нет в наличии' }})
          </option>
        </select>
      </div>

      <div>
        <label for="user_id" class="block text-sm font-medium text-gray-400 mb-1">Пользователь</label>
        <select
          id="user_id"
          v-model="reservation.user_id"
          required
          class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-md text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none"
        >
          <option value="" disabled selected>Выберите пользователя</option>
          <option
            v-for="user in users"
            :key="user.id"
            :value="user.id"
            class="bg-gray-800 text-gray-200"
          >
            {{ user.name }} ({{ user.email }})
          </option>
        </select>
      </div>

      <div>
        <button
          type="submit"
          class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200"
        >
          Забронировать
        </button>
      </div>

      <div v-if="error" class="text-red-500 text-sm">
        {{ error }}
      </div>
    </form>
  </div>
</template>