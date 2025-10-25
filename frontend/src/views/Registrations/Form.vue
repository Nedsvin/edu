<template>
  <div class="relative overflow-x-auto sm:rounded-lg">
    <Header :title="title" :title-previous="'Matrículas'" :route-back="'/matriculas'" />

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <form @submit.prevent="handleFormSubmit" class="space-y-6">
        <div class="grid grid-cols-1 gap-6">
          <Select id="aluno" label="Aluno" :options="students" v-model="form.student_id"
            :error-message="errors?.student_id" :required="true" />
          <Select id="turma" label="Turma" :options="classes" v-model="form.class_id" :error-message="errors?.class_id"
            :required="true" />
        </div>
        <div>
          <Button type="primary" @click="handleFormSubmit" :is-loading="isLoading" class="w-full">
            Salvar
          </Button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useValidation } from '@/composables/useValidation'
import request from '@/services/request'
import Swal from 'sweetalert2'

const title = ref('Adicionar')
const route = useRoute()
const router = useRouter()
const isLoading = ref(false)

const students = ref<{ value: string; label: string }[]>([])
const classes = ref<{ value: string; label: string }[]>([])

const form = ref({
  student_id: '',
  class_id: '',
})

const { errors, validate, hasErrors } = useValidation()

const handleFormSubmit = async () => {
  if (isLoading.value) return

  errors.value = {}

  validate('student_id', form.value.student_id, { required: true })
  validate('class_id', form.value.class_id, { required: true })

  isLoading.value = true

  try {
    const response = await request.post('/matriculas', {
      student_id: form.value.student_id,
      class_id: form.value.class_id,
    })

    if ([200, 201].includes(response.status)) {
      await Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: 'Matrícula criada com sucesso!',
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
        timer: 2500,
      })

      router.push('/matriculas')
    }
  } catch (error: any) {
    await Swal.fire({
      icon: 'error',
      title: 'Erro!',
      text: 'Erro ao criar a matrícula. Tente novamente.',
      showConfirmButton: true,
      confirmButtonText: 'OK',
      confirmButtonColor: '#d33',
    })

    isLoading.value = false
  }
}

const fetchOptions = async () => {
  try {
    const studentsResponse = await request.get('/alunos?itemsPerPage=100000')
    students.value =
      studentsResponse.data.data?.map((a: any) => ({
        value: a.id,
        label: `${a.name}`,
      })) || []

    const classesResponse = await request.get('/turmas?itemsPerPage=100000')
    classes.value =
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
