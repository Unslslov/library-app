<template>
  <div class="min-h-screen bg-black text-gray-300">
    <main class="container mx-auto px-6 py-8">
      <h2 class="text-2xl font-bold mb-6 text-white">Бронирование</h2>

      <div class="mb-8">
        <ReservationForm />
      </div>

      <div class="bg-gray-900 shadow overflow-hidden rounded-lg border border-gray-800">
        <table class="min-w-full divide-y divide-gray-800">
          <thead class="bg-gray-800">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Книга</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Пользователь</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Статус</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Действия</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-800">
            <tr v-for="reservation in reservations" :key="reservation.id" class="hover:bg-gray-800 transition duration-150 ease-in-out">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">{{ reservation.book.title }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">{{ reservation.user.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                <span :class="{
                  'text-green-500': reservation.status === 'активно',
                  'text-red-500': reservation.status === 'истекло',
                  'text-yellow-500': reservation.status === 'ожидает'
                }">
                  {{ reservation.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                <button
                  @click="deleteReservation(reservation.id)"
                  class="text-red-500 hover:text-red-400 font-medium transition-colors"
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

<script setup lang="ts">
import { computed, onMounted } from 'vue';
import { useStore } from 'vuex';
import ReservationForm from '../../components/books/librarian/ReservationForm.vue';

const store = useStore();

const reservations = computed(() => store.getters['librarian/reservations/allReservations']);

const fetchReservations = async () => {
  await store.dispatch('librarian/reservations/fetchReservations');
};

const deleteReservation = async (id: number) => {
  await store.dispatch('librarian/reservations/deleteReservation', id);
};

onMounted(async () => {
  await fetchReservations();
});
</script>