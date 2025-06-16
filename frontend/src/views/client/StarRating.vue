<script setup lang="ts">
defineProps<{
  rating: number;
}>()
</script>

<template>
  <div class="flex">
    <span v-for="n in 5" :key="n" class="text-xl">
      <span v-if="rating >= n" class="text-yellow-500">★</span>
      <span v-else class="text-gray-300">★</span>
    </span>
  </div>
</template>