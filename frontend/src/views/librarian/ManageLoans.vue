<template>
  <div class="min-h-screen bg-black text-gray-300">
    <main class="container mx-auto px-6 py-8">
      <h2 class="text-2xl font-bold mb-6 text-white">Выдача книг</h2>

      <div class="mb-8">
        <LoanForm />
      </div>

      <h3 class="text-xl font-semibold mb-4 text-white">Текущие выдачи</h3>
      <div class="bg-gray-900 shadow overflow-hidden rounded-lg border border-gray-800">
        <table class="min-w-full divide-y divide-gray-800">
          <thead class="bg-gray-800">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Книга</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Пользователь</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Дата выдачи</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Действия</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-800">
            <tr v-for="loan in loans" :key="loan.id" class="hover:bg-gray-800 transition duration-150 ease-in-out">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">{{ loan.book.title }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">{{ loan.user.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">{{ formatDate(loan.loaned_at) }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                <button
                  @click="returnBook(loan.id)"
                  class="px-3 py-1 bg-green-600 text-white rounded-md hover:bg-red-700 transition duration-200"
                >
                  Вернуть
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
import LoanForm from '../../components/books/librarian/LoanForm.vue';
import { useStore } from 'vuex';

const store = useStore();

const loans = computed(() => store.getters['librarian/loans/allLoans']);

const fetchLoans = async () => {
store.dispatch('librarian/loans/fetchLoans');
}

const returnBook = async (id:number) => {
  store.dispatch('librarian/loans/deleteLoan', id);
}

const formatDate = (dateString: string) => {
  const options : any = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
  return new Date(dateString).toLocaleDateString('ru-RU', options);
};

onMounted(() => {fetchLoans()});
</script>