<template>
  <div class="p-6 max-w-7xl mx-auto space-y-8">
    <h1 class="text-center font-bold text-4xl text-gray-800">Seja muito bem vindo a nossa Plataforma</h1>
    <h2 class="text-center font-bold text-2xl text-gray-800">Aqui alguns dos nossos principais recursos</h2>
    <div class="flex flex-col md:flex-row justify-center items-center gap-4 md:gap-8">
      <router-link
        to="/usuarios"
        class="group flex flex-col items-center justify-center w-full md:w-44 h-28 bg-white rounded-xl shadow-sm border border-transparent transition transform hover:scale-105"
      >
        <span class="material-icons text-gray-800 text-4xl mb-2">manage_accounts</span>
        <span class="text-gray-700 font-medium text-center">Administradores</span>
      </router-link>

      <router-link
        to="/alunos"
        class="group flex flex-col items-center justify-center w-full md:w-44 h-28 bg-white rounded-xl shadow-sm border border-transparent transition transform hover:scale-105"
      >
        <span class="material-icons text-yellow-300 text-4xl mb-2">person</span>
        <span class="text-gray-700 font-medium text-center">Alunos</span>
      </router-link>

      <router-link
        to="/matriculas"
        class="group flex flex-col items-center justify-center w-full md:w-44 h-28 bg-white rounded-xl shadow-sm border border-transparent transition transform hover:scale-105"
      >
        <span class="material-icons text-green-600 text-4xl mb-2">how_to_reg</span>
        <span class="text-gray-700 font-medium text-center">Matrículas</span>
      </router-link>

      <router-link
        to="/turmas"
        class="group flex flex-col items-center justify-center w-full md:w-44 h-28 bg-white rounded-xl shadow-sm border border-transparent transition transform hover:scale-105"
      >
        <span class="material-icons text-orange-500 text-4xl mb-2">book</span>
        <span class="text-gray-700 font-medium text-center">Turmas</span>
      </router-link>
      
    </div>

    <div class="flex flex-col md:flex-row gap-8 items-center">
      <div class="w-full flex flex-col items-center">
        <h2 class="w-full text-center text-lg font-semibold text-gray-700 mb-4">Última postagem</h2>
        <figure class="w-full md:w-2/4 rounded-xl overflow-hidden shadow-lg">
          <img
            src="/Untitled.png"
            alt="Última postagem"
            class="w-full h-auto object-cover max-h-[480px]"
          />
        </figure>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import request from '@/services/request'

const isLoading = ref(true)
const totalStudents = ref<number>(0)
const totalRegistrations = ref<number>(0)
const totalUsers = ref<number>(0)

function extractTotalFromResponse(resp: any): number {
  if (!resp) return 0
  const payload = resp?.data ?? resp
  const total = Number(payload?.total ?? payload?.count ?? 0)
  return Number.isFinite(total) ? total : 0
}

const loadData = async () => {
  isLoading.value = true
  totalStudents.value = 0
  totalRegistrations.value = 0
  totalUsers.value = 0

  try {
    const results = await Promise.allSettled([
      request.get('alunos'),
      request.get('matriculas'),
      request.get('usuarios'),
    ])

    if (results[0].status === 'fulfilled') {
      totalStudents.value = extractTotalFromResponse((results[0] as PromiseFulfilledResult<any>).value)
    }

    if (results[1].status === 'fulfilled') {
      totalRegistrations.value = extractTotalFromResponse((results[1] as PromiseFulfilledResult<any>).value)
    }

    if (results[2].status === 'fulfilled') {
      totalUsers.value = extractTotalFromResponse((results[2] as PromiseFulfilledResult<any>).value)
    }

  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  loadData()
})
</script>
