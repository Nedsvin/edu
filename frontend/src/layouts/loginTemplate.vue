<template>
  <div class="flex justify-center items-center min-h-screen bg-gradient-radial">
    <div class="auth-container w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">
      <router-view></router-view>
    </div>
  </div>
</template>

<script setup lang="ts"></script>

<style scoped>
.auth-container {
  animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
  0% {
    opacity: 0;
    transform: translateY(20px);
  }

  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.bg-gradient-radial {
  background: radial-gradient(circle at center, #ffffff 0%, #d8d8d8 40%, #a5a5a5 100%);
}
</style>
