<!-- components/books/client/CommentForm.vue -->
<script setup lang="ts">
import { ref } from 'vue'
import apiQuery from '../../../plugins/axios'

const props = defineProps<{
  bookId: number
}>()

const emit = defineEmits<{
  (e: 'commented'): void
}>()

const commentText = ref('')
const isSubmitting = ref(false)

const submitComment = async () => {
  if (!commentText.value.trim()) return
  isSubmitting.value = true

  try {
    await apiQuery.post('/client/comments', {
      user_id: localStorage.getItem('userId'),
      book_id: props.bookId,
      comment: commentText.value
    })
    // .then(res => {
    //     console.log(res);
    // });

    commentText.value = ''
    emit('commented')
  } catch (err) {
    alert('Ошибка при отправке комментария')
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <form @submit.prevent="submitComment" class="mb-6">
    <textarea
      v-model="commentText"
      placeholder="Оставьте свой отзыв..."
      rows="3"
      required
      class="w-full p-3 border rounded-md focus:ring-2 focus:ring-blue-300 focus:outline-none"
    ></textarea>
    <button
      type="submit"
      :disabled="isSubmitting"
      class="mt-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded disabled:opacity-50"
    >
      {{ isSubmitting ? 'Отправка...' : 'Оставить комментарий' }}
    </button>
  </form>
</template>