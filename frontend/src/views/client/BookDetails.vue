<script setup lang="ts">
import { ref, onMounted } from 'vue'
import apiQuary from '../../plugins/axios'
import { useRoute } from 'vue-router'
import router from '../../router'

import RatingForm from '../../components/books/client/RatingForm.vue'
import CommentForm from '../../components/books/client/CommentForm.vue'
import CommentList from './CommentList.vue'
import StarRating from './StarRating.vue'

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
  average_rating: number
  comments: any[]
}

const book = ref<Book>();
const bookId = useRoute().params.id;
const userId = localStorage.getItem('userId'); 

const getBook = async () => {
  const res = await apiQuary.get(`/client/books/${bookId}`);
  
  book.value = res.data.data;
}

const dataForWaitList = {
  user_id: userId,
  book_id: bookId
}

const addToWaitlist = async () => {
  try {
    console.log(dataForWaitList);
    await apiQuary.post(`/client/books/waitlist`, dataForWaitList);
    alert('Вы успешно добавлены в список ожидания');
  } catch (error) {
    console.error('Ошибка при добавлении в список ожидания', error);
    alert('Не удалось добавиться в список ожидания');
  }
}

const formatDate = (dateString: string) => {
  const options: Intl.DateTimeFormatOptions = { year: 'numeric', month: 'long', day: 'numeric' }
  return new Date(dateString).toLocaleDateString(undefined, options)
}

onMounted(() => {
  getBook()
})
</script>

<template>
  <div class="min-h-screen bg-black text-gray-300">
    <main class="container mx-auto px-6 py-8">
      <div v-if="book" class="max-w-4xl mx-auto">
        <div class="bg-gray-900 shadow-lg rounded-xl p-6 mb-6 transition-all duration-300">
          <div class="flex flex-col md:flex-row gap-6">
            <div class="flex-shrink-0">
              <div v-if="book.image_url"
                   class="bg-gray-800 border-2 border-dashed border-gray-700 rounded-xl w-48 h-64 overflow-hidden">
                <img :src="book.image_url" alt="Book Cover" class="w-full h-full object-cover">
              </div>
              <div v-else
                   class="bg-gray-800 border-2 border-dashed border-gray-700 rounded-xl w-48 h-64 flex items-center justify-center">
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

              <div class="mt-4">
                <p class="text-sm text-gray-400">Средняя оценка:</p>
                <div class="flex items-center">
                  <span class="text-yellow-500 text-xl mr-2">{{ book.average_rating.toFixed(1) }}</span>
                  <StarRating :rating="book.average_rating" />
                </div>
              </div>

              <RatingForm :book-id="book.id" @rated="getBook()" />
            </div>
          </div>

          <div class="flex flex-wrap gap-3 mt-6 pt-4 border-t border-gray-800">
            <button
              @click="router.push('/books')"
              class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
              Вернуться к списку
            </button>
          </div>
          <button
            v-if="book && book.available_copies === 0"
            @click="addToWaitlist"
            class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-200">
            Уведомить о появлении
          </button>
        </div>

        <div class="bg-gray-900 shadow rounded-lg p-6 mb-6">
          <h3 class="text-xl font-semibold mb-4 text-white">Комментарии</h3>
          <CommentForm :book-id="book.id" @commented="getBook()" />
          <CommentList :comments="book.comments" />
        </div>
      </div>

      <div v-else class="flex justify-center items-center h-64">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
      </div>
    </main>
  </div>
</template>