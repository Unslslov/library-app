<script setup lang="ts">

import { computed, onMounted } from 'vue';

import { useStore } from 'vuex';

const store = useStore();

const client = computed(() => store.getters['admin/clients/client']);

const createClient = async () =>
{
    store.dispatch('admin/clients/createClient', client.value);
}

onMounted(() => {
    store.commit('admin/clients/SET_CLIENT', {
    id: null,
    name: '',
    email: ''
  });
})

</script>

<template>
  <div class="min-h-screen bg-black text-gray-300">
    <div class="container mx-auto px-6 py-8">
      <div class="max-w-md mx-auto p-6 bg-gray-900 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-white mb-6 text-center">Создание клиента</h1>

        <form @submit.prevent="createClient" class="space-y-4">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-400 mb-1">Имя</label>
            <input
              type="text"
              id="name"
              v-model="client.name"
              placeholder="Иван"
              required
              class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-200 placeholder-gray-500 transition duration-150 outline-none"
            >
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-400 mb-1">Email</label>
            <input
              type="email"
              id="email"
              v-model="client.email"
              placeholder="example@mail.com"
              required
              class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-200 placeholder-gray-500 transition duration-150 outline-none"
            >
          </div>

          <div>
            <input
              type="submit"
              value="Создать"
              class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 cursor-pointer font-medium"
            >
          </div>
        </form>

        <div class="mt-8 flex justify-center">
          <router-link
            :to="{ name: 'client.index' }"
            class="inline-block px-6 py-2 text-center bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            Назад к клиентам
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>