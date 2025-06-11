<script setup lang="ts">
</script>

<template>
  <div id="app" class="d-flex flex-column min-vh-100  text-dark">

    <main class="flex-grow-1 d-flex align-items-center justify-content-center py-5">
      <div class="page-wrapper w-100 px-3">
        <router-view />
      </div>
    </main>

  </div>
</template>

<style scoped>

.page-wrapper {
  max-width: 1140px;
  width: 100%;
  margin: 0 auto;
  padding: 2.5rem;
  border-radius: 1rem;
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.07);
}

:global(body) {
  margin: 0;
  padding: 0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
</style>
