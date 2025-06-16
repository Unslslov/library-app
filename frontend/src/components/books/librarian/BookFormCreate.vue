<script setup lang="ts">
import { computed, onMounted } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

const book = computed(() => store.getters['librarian/books/book']);

const createBook = async () => {
  try {
    await store.dispatch('librarian/books/createBook', book.value)
  } catch (error) {
    console.error('Ошибка при создании книги:', error);
  }
};

onMounted(() => {
  store.commit('librarian/books/SET_BOOK', {
  title: '',
  author: '',
  genre: '',
  publisher: '',
  year_published: new Date().toISOString().split('T')[0] as string,
  available_copies: 1,
  total_copies: 1,
  description: null,
  image_url: null
  })
})
</script>

<template>
  <div class="min-h-screen bg-black text-gray-300">
    <main class="container mx-auto px-6 py-8">
      <h2 class="text-2xl font-bold mb-6 text-white">Добавить книгу</h2>

      <form @submit.prevent="createBook" class="space-y-6 bg-gray-900 shadow-lg rounded-xl p-6 max-w-2xl mx-auto">
        <div>
          <label for="title" class="block text-sm font-medium text-gray-400">Название книги</label>
          <input 
            v-model="book.title" 
            id="title" 
            type="text" 
            placeholder="Название книги" 
            required 
            class="mt-1 block w-full rounded-md border-gray-700 bg-gray-800 text-gray-200 focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border"
          />
        </div>

        <div>
          <label for="author" class="block text-sm font-medium text-gray-400">Автор</label>
          <input 
            v-model="book.author" 
            id="author" 
            type="text" 
            placeholder="Автор" 
            required 
            class="mt-1 block w-full rounded-md border-gray-700 bg-gray-800 text-gray-200 focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border"
          />
        </div>

        <div>
          <label for="genre" class="block text-sm font-medium text-gray-400">Жанр</label>
          <input 
            v-model="book.genre" 
            id="genre" 
            type="text" 
            placeholder="Жанр" 
            required 
            class="mt-1 block w-full rounded-md border-gray-700 bg-gray-800 text-gray-200 focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border"
          />
        </div>

        <div>
          <label for="publisher" class="block text-sm font-medium text-gray-400">Издатель</label>
          <input 
            v-model="book.publisher" 
            id="publisher" 
            type="text" 
            placeholder="Издатель" 
            required 
            class="mt-1 block w-full rounded-md border-gray-700 bg-gray-800 text-gray-200 focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border"
          />
        </div>

        <div>
          <label for="year_published" class="block text-sm font-medium text-gray-400">Год издания</label>
          <input 
            v-model="book.year_published" 
            id="year_published" 
            type="date" 
            required 
            class="mt-1 block w-full rounded-md border-gray-700 bg-gray-800 text-gray-200 focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border"
          />
        </div>

        <div>
          <label for="available_copies" class="block text-sm font-medium text-gray-400">Доступные копии</label>
          <input 
            v-model.number="book.available_copies" 
            id="available_copies" 
            type="number" 
            placeholder="Количество копий" 
            required 
            class="mt-1 block w-full rounded-md border-gray-700 bg-gray-800 text-gray-200 focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border"
          />
        </div>

        <div>
          <label for="total_copies" class="block text-sm font-medium text-gray-400">Всего копий</label>
          <input 
            v-model.number="book.total_copies" 
            id="total_copies" 
            type="number" 
            placeholder="Всего копий" 
            required 
            class="mt-1 block w-full rounded-md border-gray-700 bg-gray-800 text-gray-200 focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border"
          />
        </div>

        <div>
          <label for="description" class="block text-sm font-medium text-gray-400">Описание</label>
          <textarea 
            v-model="book.description" 
            id="description" 
            placeholder="Описание" 
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-700 bg-gray-800 text-gray-200 focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border"
          ></textarea>
        </div>

        <div>
          <label for="image_url" class="block text-sm font-medium text-gray-400">URL изображения</label>
          <input 
            v-model="book.image_url" 
            id="image_url" 
            type="text" 
            placeholder="https://example.com/book-cover.jpg"  
            class="mt-1 block w-full rounded-md border-gray-700 bg-gray-800 text-gray-200 focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border"
          />
        </div>

        <div class="flex justify-end pt-4">
          <button 
            type="submit" 
            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200"
          >
            Создать
          </button>
        </div>
      </form>
    </main>
  </div>
</template>