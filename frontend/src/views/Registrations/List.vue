<template>
  <div class="relative overflow-x-autosm:rounded-lg">
    <div class="flex items-center justify-between pb-4 mr-8">
      <h2 class="text-2xl font-bold text-gray-900 text-center w-full">Nossas Matrículas</h2>
    </div>

    <Template v-model:filters="filters" :columns="columns" :advanced-filter="true" :data-link="'/matriculas'"
      :delete-link="'/matriculas/'" custom-delete="deleteRegistration" />
  </div>
</template>

<script setup lang="ts">
import Template from '@/components/Template.vue'
import request from '@/services/request'
import { onMounted, ref } from 'vue'

const studentsOptions = ref<{ value: string; label: string }[]>([])
const classesOptions = ref<{ value: string; label: string }[]>([])

const filters = ref([
  { key: 'student_id', label: 'Aluno', type: 'select', options: studentsOptions },
  { key: 'class_id', label: 'Turma', type: 'select', options: classesOptions },
])

const columns = [
  { key: 'student_id', label: 'ID' },
  { key: 'student_name', label: 'Aluno' },
  { key: 'student_cpf', label: 'CPF', mask: 'cpf' },
  { key: 'class_name', label: 'Turma' },
  { key: 'date_registration', label: 'Data Matricula', mask: 'date' },
]

const fetchOptions = async () => {
  try {
    const studentsResponse = await request.get('/alunos?itemsPerPage=100')
    studentsOptions.value =
      studentsResponse.data.data?.map((a: any) => ({
        value: a.id,
        label: `${a.name}`,
      })) || []

    const classesResponse = await request.get('/turmas?itemsPerPage=100')
    classesOptions.value =
      classesResponse.data.data?.map((t: any) => ({
        value: t.id,
        label: `${t.name}`,
      })) || []
  } catch (error) {
    console.error('Erro ao carregar alunos e turmas:', error)
  }
}

onMounted(fetchOptions)
</script>