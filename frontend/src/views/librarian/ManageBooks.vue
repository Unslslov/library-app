<script setup lang="ts">
import { computed, onMounted } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

const books = computed(() => store.getters['librarian/books/allBooks']);

const fetchBooks = async () => {
  store.dispatch('librarian/books/fetchBooks');
}

const deleteBook = async (id:number) => {
  store.dispatch('librarian/books/deleteBook', id);
}

onMounted(() => {fetchBooks()});
</script>

<template>
  <div class="min-h-screen bg-black text-gray-300">
    <main class="container mx-auto px-6 py-2">

      <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-white">Книги</h1>
        <router-link
          :to="{ name: 'librarian.book.create' }"
          class="inline-block mt-4 px-6 py-2 text-lg font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition duration-200"
        >
          Добавить книгу
        </router-link>
      </div>

      <div class="bg-gray-900 shadow overflow-hidden rounded-lg border border-gray-800">
        <table class="min-w-full divide-y divide-gray-800">
          <thead class="bg-gray-800">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Название</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Автор</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Доступные копии</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Действия</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-800">
            <tr v-for="book in books" :key="book.id" class="hover:bg-gray-800 transition duration-150 ease-in-out">
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <router-link
                  :to="{ name: 'librarian.book.show', params: { id: book.id } }"
                  class="text-blue-400 hover:text-blue-300 hover:underline transition duration-150"
                >
                  {{ book.title }}
                </router-link>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ book.author }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ book.available_copies }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                <router-link
                  :to="{ name: 'librarian.book.edit', params: { id: book.id } }"
                  class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  Редактировать
                </router-link>
                <button
                  @click="deleteBook(book.id)"
                  class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500"
                >
                  Удалить
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</template>