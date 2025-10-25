<template>
  <div class="relative flex h-screen">
    <button :aria-expanded="isOpen" @click="toggleMenu"
      class="fixed top-3 left-5 z-[60] p-2 rounded-md shadow-md bg-black hover:bg-gray-700 transition-transform duration-300 transform"
      :class="isOpen ? 'translate-x-44' : 'translate-x-0'" aria-label="Abrir menu">
      <svg class="w-6 h-6 text-gray-700" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true">
        <path d="M4 6H20M4 12H20M4 18H20" stroke="white" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round" />
      </svg>
    </button>
    <aside :class="[
      'bg-[#f0f0f0] text-gray-800 flex flex-col shadow-lg h-full w-60 z-50 transition-transform duration-300 fixed top-0 left-0',
      isOpen ? 'translate-x-0' : '-translate-x-full'
    ]" aria-hidden="!isOpen">
      <nav class="flex-1 px-4 pt-[50px]">
        <ul class="space-y-1">

          <li>
            <router-link to="/home"
              class="flex items-center gap-3 px-3 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200 hover:scale-105"
              @click="onNavLinkClick">
              <span
                class="material-icons rounded-md p-2 text-indigo-600 bg-indigo-50 text-2xl transition-colors duration-200 group-hover:text-indigo-700 "
                aria-hidden="true">
                home
              </span>
              <span class="text-sm font-medium text-gray-700">Página Inicial</span>
            </router-link>
          </li>

          <li>
            <router-link to="/usuarios"
              class="flex items-center gap-3 px-3 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200 hover:scale-105 transition transform"
              @click="onNavLinkClick">
              <span
                class="material-icons rounded-md p-2 text-gray-800 bg-gray-100 text-2xl transition-colors duration-200"
                aria-hidden="true">
                manage_accounts
              </span>
              <span class="text-sm font-medium text-gray-700">Administradores</span>
            </router-link>
          </li>

          <li>
            <router-link to="/alunos"
              class="flex items-center gap-3 px-3 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200 hover:scale-105 transition transform"
              @click="onNavLinkClick">
              <span
                class="material-icons rounded-md p-2 text-yellow-300 bg-blue-50 text-2xl transition-colors duration-200"
                aria-hidden="true">
                person
              </span>
              <span class="text-sm font-medium text-gray-700">Alunos</span>
            </router-link>
          </li>

          <li>
            <router-link to="/matriculas"
              class="flex items-center gap-3 px-3 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200 hover:scale-105 transition transform"
              @click="onNavLinkClick">
              <span
                class="material-icons rounded-md p-2 text-green-600 bg-green-50 text-2xl transition-colors duration-200"
                aria-hidden="true">
                how_to_reg
              </span>
              <span class="text-sm font-medium text-gray-700">Matrículas</span>
            </router-link>
          </li>

          <li>
            <router-link to="/turmas"
              class="flex items-center gap-3 px-3 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200 hover:scale-105 transition transform"
              @click="onNavLinkClick">
              <span
                class="material-icons rounded-md p-2 text-orange-500 bg-orange-50 text-2xl transition-colors duration-200"
                aria-hidden="true">
                book
              </span>
              <span class="text-sm font-medium text-gray-700">Turmas</span>
            </router-link>
          </li>

          <li class="mt-4">
            <router-link to="/logout"
              class="flex items-center gap-3 px-3 py-3 rounded-lg text-red-600 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-100">
              <span class="material-icons rounded-md p-2 text-red-600 bg-red-50 text-2xl transition-colors duration-200"
                aria-hidden="true">exit_to_app</span>
              <span class="text-sm font-medium text-red-600">Sair</span>
            </router-link>
          </li>

        </ul>
      </nav>
    </aside>
    <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 z-40" @click="toggleMenu" aria-hidden="true"></div>
    <div class="flex-1 flex flex-col transition-all duration-300" :class="isOpen ? 'md:ml-60' : 'md:ml-0'">
      <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '@/stores/authStore'

const authStore = useAuthStore()
const name = authStore.user?.name ?? ''
const isOpen = ref(false)

const toggleMenu = () => {
  isOpen.value = !isOpen.value
}
</script>
