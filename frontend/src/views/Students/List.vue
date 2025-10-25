<template>
  <div class="relative overflow-x-autosm:rounded-lg">
    <div class="flex items-center justify-between pb-4 mr-8">
      <h2 class="text-2xl font-bold text-gray-900 text-center w-full">Nossos Alunos</h2>
    </div>

    <Template v-model:filters="filters" :columns="columns" :data="alunos ?? []" :advanced-filter="true"
      :is-loading="isLoading" :data-link="'/alunos'" :edit-link="'/alunos/editar/'" :delete-link="'/alunos/'"
      custom-delete="deleteRegistration" />
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
  { key: 'id', label: 'ID', size: '75px' },
  { key: 'name', label: 'Nome' },
  { key: 'cpf', label: 'CPF', size: '200px', mask: 'cpf' },
  { key: 'email', label: 'Email' },
]

const alunos = ref([])
const isLoading = ref(true)
</script>
