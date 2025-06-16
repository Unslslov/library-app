<script setup lang="ts">

import { computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();

const client = computed(() => store.getters['admin/clients/client']);

const clientId = useRoute().params.id;

const fetchClient = async () => {
    await store.dispatch('admin/clients/fetchClient', clientId)
}

onMounted(() => {
    fetchClient()
});
</script>

<template>
  <div class="min-h-screen bg-black text-gray-300">
    <div class="container mx-auto px-6 py-8">
      <div v-if="client && client.id" class="bg-gray-900 rounded-lg shadow-md p-6 max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-white mb-6 text-center">Информация о клиенте</h2>

        <div class="space-y-4">
          <div>
            <span class="block text-sm text-gray-400">ID:</span>
            <span class="text-lg text-gray-200">{{ client.id }}</span>
          </div>

          <div>
            <span class="block text-sm text-gray-400">Имя:</span>
            <span class="text-lg text-gray-200">{{ client.name }}</span>
          </div>

          <div>
            <span class="block text-sm text-gray-400">Email:</span>
            <span class="text-lg text-gray-200">{{ client.email }}</span>
          </div>

          <div>
            <span class="block text-sm text-gray-400">Роль:</span>
            <span class="text-lg text-gray-200">{{ client.role.name }}</span>
          </div>
        </div>

        <div class="mt-8 flex flex-wrap gap-4">
        <router-link
            :to="{ name: 'client.edit', params: { id: client.id } }"
            class="inline-block px-6 py-2 text-center bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Редактировать
        </router-link>

        <router-link
            :to="{ name: 'client.index' }"
            class="inline-block px-6 py-2 text-center bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Назад к клиентам
        </router-link>
        </div>
      </div>

      <div v-else class="text-center text-gray-400 mt-10">
        Клиент не найден.
      </div>
    </div>
  </div>
</template>