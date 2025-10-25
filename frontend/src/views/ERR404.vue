<template>
  <div class="flex items-center justify-center min-h-screen bg-gradient-to-r from-gray-200 to-gray-300 px-4">
    <div class="auth-container bg-white p-8 rounded-lg shadow-lg text-center w-full sm:max-w-md md:max-w-lg">

      <h2 class="text-6xl md:text-[8rem] font-bold text-gray-900 mb-4">404</h2>

      <p class="text-lg text-gray-700 mb-2">Oops! Página não encontrada.</p>
      <p class="text-gray-600 mb-6">
        A página que você procura não existe.
      </p>

      <Button type="primary" @click="goToHome">Voltar ao Home</Button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'

const router = useRouter()

const goToHome = () => {
  router.push('/home')
}
</script>

<style scoped>
.auth-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
</style>
