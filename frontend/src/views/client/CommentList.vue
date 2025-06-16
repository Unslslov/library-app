<script setup lang="ts">
interface Comment {
  id: number
  user: {
    name: string
  }
  comment: string
  created_at: string
}

defineProps<{
  comments: Comment[]
}>()

const formatDate = (dateString: string) => {
  const options: Intl.DateTimeFormatOptions = { year: 'numeric', month: 'long', day: 'numeric' }
  return new Date(dateString).toLocaleDateString(undefined, options)
}
</script>

<template>
  <div class="mt-6">
    <h3 class="text-xl font-semibold mb-4">Комментарии</h3>

    <div v-if="comments.length === 0" class="text-gray-500 italic">
      Нет комментариев.
    </div>

    <div v-for="comment in comments" :key="comment.id" class="border-b pb-4 mb-4 last:border-b-0">
      <div class="flex justify-between">
        <strong>{{ comment.user.name }}</strong>
        <small class="text-gray-500">{{ formatDate(comment.created_at) }}</small>
      </div>
      <p class="mt-1 text-gray-700">{{ comment.comment }}</p>
    </div>
  </div>
</template>