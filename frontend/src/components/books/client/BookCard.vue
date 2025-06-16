<script setup lang="ts">
import ReservationForm from './ReservationForm.vue';

interface Book {
  id: number;
  title: string;
  genre: string;
  author: string;
  publisher: string;
  available_copies: number;
}

defineProps<{
  book: Book;
}>();
</script>

<template>
  <tr class="hover:bg-gray-100">
    <td>
        <router-link :to="{name: 'book.show', params: {id: book.id}}">
            {{ book.title }}
        </router-link>
    </td>
    <td>{{ book.genre }}</td>
    <td>{{ book.author }}</td>
    <td>{{ book.publisher }}</td>
    <td>{{ book.available_copies }}</td>
    <td class="text-center">
      <ReservationForm :book-id="book.id" />
    </td>
  </tr>
</template>
