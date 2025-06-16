<script setup lang="ts">
import { ref } from 'vue';
import apiQuery from '../../../plugins/axios';

interface Props {
  bookId: number;
}

const props = defineProps<Props>();
const emits = defineEmits(['success', 'error']);

const isLoading = ref(false);

const reserveBook = async () => {
  isLoading.value = true;

  try {
    const response = await apiQuery.post('/client/reservations', {
      book_id: props.bookId,
      user_id: localStorage.getItem('userId')
    });

    emits('success', response.data);
  } catch (err: any) {
    const message = err.response?.data?.message || 'Ошибка при бронировании книги';
    emits('error', message);
  } finally {
    isLoading.value = false;
  }
};
</script>

<template>
  <td class="text-center">
    <button
      :disabled="isLoading"
      @click="reserveBook"
      class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 disabled:opacity-50"
    >
      {{ isLoading ? 'Бронирование...' : 'Забронировать' }}
    </button>
  </td>
</template>