<template>
  <div class="relative overflow-x-autosm:rounded-lg">
    <div class="flex items-center justify-between pb-4 mr-8">
      <h2 class="text-2xl font-bold text-gray-900 text-center w-full">Nossas Turmas</h2>
    </div>

    <Template v-model:filters="filters" :columns="columns" :data="turmas ?? []" :advanced-filter="true"
      :is-loading="isLoading" :data-link="'/turmas'" :edit-link="'/turmas/editar/'" :delete-link="'/turmas/'"
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
  { key: 'id', label: 'ID' },
  { key: 'name', label: 'Nome' },
  { key: 'total_students', label: 'Total de alunos' },
]
</script>
