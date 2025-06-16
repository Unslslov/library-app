<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import BookForm from '../../components/books/librarian/BookFormEdit.vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();

const book = computed(() => store.getters['librarian/books/book']);

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

const showForm = ref(false);

const bookId = useRoute().params.id;

const fetchBook = async () => {
  await store.dispatch('librarian/books/fetchBook', bookId);
}

const deleteBook = async (id:number) => {
  await store.dispatch('librarian/books/deleteBook', id);
}

const formatDate = (dateString: string) => {
  const options: Intl.DateTimeFormatOptions = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
}

const onBookSaved = (updatedBook: Book) => {
  store.commit('librarian/books/SET_BOOK', updatedBook);
  
  showForm.value = false;
}

onMounted(() => {fetchBook()});
</script>

<template>
  <div class="min-h-screen bg-black text-gray-300">
    <main class="container mx-auto px-6 py-8">
      <h2 class="text-2xl font-bold mb-6 text-white">Книги</h2>

      <div v-if="book">
        <div v-if="!showForm" class="bg-gray-900 shadow-lg rounded-xl p-6 mb-6 transition-all duration-300">
          <div class="flex flex-col md:flex-row gap-6">
            <div class="flex-shrink-0">
              <div v-if="book.image_url" class="bg-gray-800 border-2 border-dashed border-gray-700 rounded-xl w-48 h-64 overflow-hidden">
                <img :src="book.image_url" alt="Book Cover" class="w-full h-full object-cover">
              </div>
              <div v-else class="bg-gray-800 border-2 border-dashed border-gray-700 rounded-xl w-48 h-64 flex items-center justify-center">
                <span class="text-gray-500">Нет изображения</span>
              </div>
            </div>

            <div class="flex-grow">
              <h3 class="text-2xl font-bold text-white mb-2">{{ book.title }}</h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                  <p class="text-sm text-gray-400">Автор</p>
                  <p class="font-medium text-gray-200">{{ book.author }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-400">Жанр</p>
                  <p class="font-medium text-gray-200">{{ book.genre }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-400">Издатель</p>
                  <p class="font-medium text-gray-200">{{ book.publisher }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-400">Год издания</p>
                  <p class="font-medium text-gray-200">{{ formatDate(book.year_published) }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-400">Доступно экземпляров</p>
                  <p class="font-medium text-gray-200">{{ book.available_copies }} из {{ book.total_copies }}</p>
                </div>
              </div>

              <div v-if="book.description" class="mb-4">
                <p class="text-sm text-gray-400">Описание</p>
                <p class="text-gray-300">{{ book.description }}</p>
              </div>
            </div>
          </div>

          <div class="flex flex-wrap gap-3 mt-6 pt-4 border-t border-gray-800">
            <button 
              @click="showForm = true"
              class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
              </svg>
              Редактировать
            </button>

            <button 
              @click="deleteBook(book.id)"
              class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
              Удалить
            </button>
          </div>
        </div>

        <BookForm 
          v-else 
          :initial-book="book"
          @close="showForm = false"
          @saved="onBookSaved"
          class="bg-gray-900 shadow-lg rounded-xl p-6 mb-6 transition-all duration-300"
        />
      </div>

      <div v-else class="flex justify-center items-center h-64">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
      </div>
    </main>
  </div>
</template>