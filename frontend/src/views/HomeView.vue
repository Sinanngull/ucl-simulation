<script setup lang="ts">
import { useRouter } from 'vue-router'
import { onMounted } from 'vue'

const router = useRouter()

onMounted(() => {
  router.push('/teams')
})
</script>

<template>
  <div class="text-center p-5">
    Redirecting...
  </div>
</template>
