<script setup>
import apiQuary from '../../plugins/axios';
import router from '../../router';

const logout = async () => {
  const userId = localStorage.getItem('userId');
  await apiQuary.post(`/logout/${userId}`)
    .then(() => {
      router.push('/');
    });
};
</script>

<template>
  <div class="min-h-screen bg-black text-gray-300">
    <div class="bg-gray-900 border-b border-gray-800 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">Панель библиотекаря</h1>
        <button
          @click="logout"
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
        >
          Выйти
        </button>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8 flex">
      <aside class="w-64 mr-8 hidden md:block">
        <nav class="bg-gray-900 shadow rounded-lg p-4 space-y-3">
          <h2 class="font-semibold text-gray-400 mb-2">Меню</h2>
          <router-link
            :to="{ name: 'librarian.book.index' }"
            class="block px-4 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-blue-400 transition-colors duration-150"
          >
            Управление книгами
          </router-link>

          <router-link
            :to="{ name: 'librarian.loans.index' }"
            class="block px-4 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-blue-400 transition-colors duration-150"
          >
            Выдача книг
          </router-link>

          <router-link
            :to="{ name: 'librarian.reservations.index' }"
            class="block px-4 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-blue-400 transition-colors duration-150"
          >
            Резервирование
          </router-link>
        </nav>
      </aside>

      <section class="flex-grow">
        <router-view />
      </section>
    </div>
  </div>
</template>