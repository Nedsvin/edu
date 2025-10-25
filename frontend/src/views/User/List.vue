<template>
  <div class="relative overflow-x-autosm:rounded-lg">
    <div class="flex items-center justify-between pb-4 mr-8">
      <h2 class="text-2xl font-bold text-gray-900 text-center w-full">Administradores</h2>
    </div>
    <Template v-model:filters="filters" :columns="columns" :data="usuarios" :advanced-filter="true"
      :data-link="'/usuarios'" :is-loading="isLoading" />
  </div>
</template>

<script setup lang="ts">
import Template from '@/components/Template.vue'
import { ref } from 'vue'

const filters = ref([
  { key: 'id', label: 'ID', placeholder: 'Filtrar por ID' },
  { key: 'name', label: 'Nome', placeholder: 'Filtrar por Nome' },
])

const columns = [
  { key: 'id', label: 'ID' },
  { key: 'name', label: 'Nome' },
  { key: 'email', label: 'E-mail' },
  { key: 'role', label: 'Nivel' },
]

const usuarios = ref([])
const isLoading = ref(true)
</script>
