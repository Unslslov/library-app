<script setup lang="ts">
import { ref } from 'vue'
import apiQuery from '../../../plugins/axios'

const props = defineProps<{
  bookId: number
}>()

const emit = defineEmits<{
  (e: 'rated'): void
}>()

const rating = ref<number>(0)
const isSubmitting = ref<boolean>(false)

const rateBook = async () => {
  if (!rating.value || rating.value < 1 || rating.value > 5) return
  isSubmitting.value = true

  try {
    await apiQuery.post('/client/ratings', {
      book_id: props.bookId,
      user_id: localStorage.getItem('userId'),
      rating: rating.value
    })

    emit('rated')
  } catch (err) {
    alert('Ошибка при оценке книги')
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <form @submit.prevent="rateBook" class="mt-4">
    <label class="block text-sm text-gray-600 mb-2">Поставьте свою оценку:</label>
    <div class="flex gap-2">
      <button
        v-for="n in 5"
        :key="n"
        type="button"
        @click="rating = n"
        class="focus:outline-none"
        :class="{ 'text-yellow-500': rating >= n, 'text-gray-300': rating < n }"
      >
        ★
      </button>
    </div>
    <button
      type="submit"
      :disabled="isSubmitting"
      class="mt-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded disabled:opacity-50"
    >
      {{ isSubmitting ? 'Отправка...' : 'Отправить оценку' }}
    </button>
  </form>
</template>