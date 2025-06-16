<script setup lang="ts">
import { computed, onMounted } from 'vue';
import apiQuary from '../../../plugins/axios';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();

const book = computed(() => store.getters['librarian/books/book']);

const route = useRoute();
const bookId = route.params.id;

const emit = defineEmits(['close']);

const updateBook = async () => {
    try {
        const bookData = {
      ...book.value,
      year_published: formatDateForServer(book.value.year_published)
    };  

    await store.dispatch('librarian/books/editBook', {
    id: book.value.id,
    data: bookData
    });
    
    emit('close');

    } catch (error) {
      console.error('Ошибка при обновлении книги:', error);
    }
}

const formatDateForServer = (dateString: string): string => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toISOString().slice(0, 19).replace('T', ' ');
}

const getBook = async () => {
  try {
    const res = await apiQuary.get(`/librarian/books/${bookId}`);
    const data = res.data.data;
  
  if (data.year_published) 
    {
    const date = new Date(data.year_published);
    data.year_published = date.toISOString().split('T')[0]; // => "YYYY-MM-DD"
    }
    
    store.commit('librarian/books/SET_BOOK', data)

    } catch (error) {
        console.error('Ошибка при получении книги:', error);
    }
}

onMounted(() => {
  getBook();
});
</script>

<template>
  <div class="min-h-screen bg-black text-gray-300">
    <main class="container mx-auto px-6 py-8">
      <h2 class="text-2xl font-bold mb-6 text-white">Редактировать книгу</h2>

      <form @submit.prevent="updateBook" class="space-y-6 bg-gray-900 shadow-lg rounded-xl p-6 max-w-2xl mx-auto">
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

        <div class="flex justify-end pt-4 space-x-3">
          <button 
            type="button" 
            @click="$emit('close')"
            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition duration-200"
          >
            Отмена
          </button>
          <button 
            type="submit" 
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200"
          >
            Сохранить
          </button>
        </div>
      </form>
    </main>
  </div>
</template>