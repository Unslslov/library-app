<script setup lang="ts">
import { ref, onMounted } from 'vue';
import apiQuery from '../../plugins/axios';

interface Reservation {
  id: number;
  book: {
    id: number;
    title: string;
  };
  user_id: number;
  status: string;
  expires_at: string;
}

const reservations = ref<Reservation[]>([]);
const isLoading = ref(false);
const error = ref<string | null>(null);

const userId = localStorage.getItem('userId');

const fetchReservations = async () => {
  isLoading.value = true;
  error.value = null;

  try {
    const response = await apiQuery.get(`/client/reservations/my/${userId}`)
    // .then(res => {
    //     console.log(res);
    // });
    reservations.value = response.data.data;
  } catch (err: any) {
    const message = err.response?.data?.message || 'Ошибка загрузки бронирований';
    error.value = message;
  } finally {
    isLoading.value = false;
  }
};

const cancelReservation = async (id: number) => {
  if (!confirm('Вы уверены, что хотите отменить бронирование?')) return;

  try {
    await apiQuery.delete(`/client/reservations/${id}`);

    reservations.value = reservations.value.filter(res => res.id !== id);
  } catch (err: any) {
    const message = err.response?.data?.message || 'Ошибка при отмене бронирования';
    alert(message);
  }
};

onMounted(() => {
  fetchReservations();
});
</script>

<template>
  <div class="min-h-screen bg-black text-gray-300">
    <main class="container mx-auto px-6 py-8">
      <h2 class="text-2xl font-bold mb-6 text-white">Мои бронирования</h2>

      <div v-if="error" class="mb-4 text-red-500">{{ error }}</div>

      <div v-if="isLoading" class="flex justify-center items-center h-32">
        <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-blue-500"></div>
      </div>

      <div v-if="!isLoading && reservations.length > 0" class="bg-gray-900 shadow overflow-hidden rounded-lg border border-gray-800">
        <table class="min-w-full divide-y divide-gray-800">
          <thead class="bg-gray-800">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Книга</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Статус</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Действует до</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Действие</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-800">
            <tr v-for="reservation in reservations" :key="reservation.id" class="hover:bg-gray-800 transition duration-150 ease-in-out">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">{{ reservation.book.title }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <span :class="{
                  'text-green-500': reservation.status === 'активно',
                  'text-yellow-500': reservation.status === 'ожидает',
                  'text-red-500': reservation.status === 'истекло'
                }">
                  {{ reservation.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                {{ new Date(reservation.expires_at).toLocaleString('ru-RU') }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                <button
                  @click="cancelReservation(reservation.id)"
                  class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200"
                >
                  Отменить
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <p v-else-if="!isLoading && reservations.length === 0" class="text-gray-400 text-center py-4">
        Нет активных бронирований.
      </p>

      <div class="mt-6 text-center">
        <router-link
          :to="{ name: 'book.index' }"
          class="inline-block px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-600 transition duration-200"
        >
          Вернуться к книгам
        </router-link>
      </div>
    </main>
  </div>
</template>