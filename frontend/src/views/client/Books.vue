<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useStore } from 'vuex';
import BookCard from '../../components/books/client/BookCard.vue';
import apiQuary from '../../plugins/axios';
import router from '../../router';

const store = useStore();
const books = computed(() => store.getters['client/books/allBooks']);

const filters = ref({
  author: '',
  genre: '',
  publisher: ''
});

const logout = async () => {
  const userId = localStorage.getItem('userId');
  await apiQuary.post(`/logout/${userId}`)
    .then(() => {
      router.push('/');
    });
};

const resetFilters = async () => {
  filters.value = { author: '', genre: '', publisher: '' };
  await fetchBooks();
};

const fetchBooks = async () => {
  await store.dispatch('client/books/fetchBooks', filters.value);
};


onMounted(async () => {
  await fetchBooks();
});
</script>

<template>
  <div class="min-h-screen bg-black text-gray-300">
    <div class="bg-gray-900 border-b border-gray-800 shadow-sm">
      <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-white">Раздел книг</h1>
        <button
          @click="logout"
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          Выход
        </button>
      </div>
    </div>

    <div class="container mx-auto px-6 py-8">
      <router-view />

      <div class="mb-6 text-right">
        <router-link
          :to="{ name: 'client.reservations.index' }"
          class="text-blue-400 hover:text-blue-300 transition duration-200"
        >
          Посмотреть свои бронирования
        </router-link>
      </div>

      <h2 class="text-2xl font-bold mb-6 text-white">Книги</h2>

      <div class="bg-gray-900 p-6 rounded-lg shadow-md mb-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-200">Фильтры</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
          <div>
            <label class="block text-sm font-medium text-gray-400 mb-1">Автор</label>
            <input
              type="text"
              v-model="filters.author"
              @keyup.enter="fetchBooks"
              placeholder="Имя автора"
              class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-md text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-400 mb-1">Жанр</label>
            <input
              type="text"
              v-model="filters.genre"
              @keyup.enter="fetchBooks"
              placeholder="Жанр"
              class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-md text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-400 mb-1">Издатель</label>
            <input
              type="text"
              v-model="filters.publisher"
              @keyup.enter="fetchBooks"
              placeholder="Название издателя"
              class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-md text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
        </div>

        <div class="flex space-x-4 justify-end">
          <button
            @click="fetchBooks"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200"
          >
            Применить
          </button>
          <button
            @click="resetFilters"
            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200"
          >
            Сбросить
          </button>
        </div>
      </div>

      <div class="bg-gray-900 shadow overflow-hidden rounded-lg border border-gray-800">
        <table class="min-w-full divide-y divide-gray-800">
          <thead class="bg-gray-800">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Название</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Жанр</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Автор</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Издатель</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Доступные копии</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Действие</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-800">
            <BookCard
              v-for="book in books"
              :key="book.id"
              :book="book"
              class="hover:bg-gray-800 transition duration-150"
            />
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>