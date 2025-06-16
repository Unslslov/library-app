<script setup lang="ts">

import { ref } from 'vue';
import { useRouter } from 'vue-router';
import apiQuary from '../plugins/axios';

const router = useRouter()
const data = ref({
  email: null,
  password: null
});
const error = ref('');


const login = async () => {
  try{
    const response = await apiQuary.post('login', {
      email: data.value.email,
      password: data.value.password
    })

    const userRole = response.data.userRole;
    console.log(userRole);


    localStorage.setItem('token', response.data.token)
    localStorage.setItem('userId', response.data.userId);
    localStorage.setItem('userRole', response.data.userRole);

    if(userRole == 'client')
      router.push('/books')
    else if(userRole == 'librarian')
      router.push('/librarian')
    else if(userRole == 'admin')
      router.push('/clients')
  }
  catch (err)
  {
    error.value = 'Неверные учетные данные'
  }
}

</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-black text-gray-300">
    <div class="w-full max-w-md p-8 space-y-6 bg-gray-900 rounded-lg shadow-md">
      <h1 class="text-2xl font-bold text-blue-500 text-center">Страница аутентификации</h1>

      <div v-if="error" class="p-3 text-sm text-white bg-red-600 rounded">
        {{ error }}
      </div>

      <form @submit.prevent="login" class="space-y-4">
        <div>
          <label for="email" class="block mb-1 text-sm font-medium text-gray-400">Email</label>
          <input
            id="email"
            v-model="data.email"
            type="email"
            placeholder="example@mail.com"
            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          >
        </div>

        <div>
          <label for="password" class="block mb-1 text-sm font-medium text-gray-400">Пароль</label>
          <input
            id="password"
            v-model="data.password"
            type="password"
            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          >
        </div>

        <div>
          <button
            type="submit"
            class="w-full py-2 mt-2 text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors duration-200"
          >
            Войти
          </button>
        </div>
      </form>
    </div>
  </div>
</template>