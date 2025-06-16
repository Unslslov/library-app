<script setup lang="ts">
import { computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import apiQuary from '../../plugins/axios';

const store = useStore();
const router = useRouter();

const clients = computed(() => store.getters['admin/clients/allClients']);

const logout = async () => {
  const userId = localStorage.getItem('userId');
  await apiQuary.post(`/logout/${userId}`)
  .then(() => {
    router.push('/');
  });
};

const fetchClients = async () => {
  await store.dispatch('admin/clients/fetchClients');
};

const deleteClient = async (id: number) => {
  await store.dispatch('admin/clients/deleteClient', id); 
};

const resetPassword = async (id: number) => {
  await store.dispatch('admin/clients/resetPassword', id); 
  alert('Пароль успешно сбросился');
};

onMounted(() => {
  fetchClients();
});
</script>

<template>
  <div class="min-h-screen bg-black text-gray-300">
    <header class="p-4 border-b border-gray-800 flex justify-between items-center">
      <h1 class="text-xl font-bold text-blue-500">Панель администратора</h1>
      <a @click.prevent="logout" href="#" class="text-blue-400 hover:text-blue-300 transition">Выход</a>
    </header>

    <router-view></router-view>
    <main class="container mx-auto px-6 py-8">
      <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-white underline decoration-solid">Клиенты</h1>
        <router-link
          :to="{ name: 'client.create' }"
          class="inline-block mt-4 px-6 py-2 text-lg font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition duration-200"
        >
          Создать клиента
        </router-link>
      </div>

      <div class="bg-gray-900 shadow overflow-hidden rounded-lg border border-gray-800">
        <table class="min-w-full divide-y divide-gray-800">
          <thead class="bg-gray-800">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Имя</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Email</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Роль</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Действия</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-800">
            <tr v-for="(client, index) in clients" :key="client.id" class="hover:bg-gray-800 transition duration-150 ease-in-out">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ client.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <router-link
                  :to="{ name: 'client.show', params: { id: client.id } }"
                  class="text-blue-400 hover:text-blue-300 hover:underline transition duration-150"
                >
                  {{ client.name }}
                </router-link>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ client.email }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ client.role.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                <router-link
                  :to="{ name: 'client.edit', params: { id: client.id } }"
                  class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  Изменить
                </router-link>
                <button
                  @click="resetPassword(client.id)"
                  class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  Сбросить пароль
                </button>
                <button
                  @click="deleteClient(client.id)"
                  class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500"
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