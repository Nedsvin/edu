<template>
  <div class="relative overflow-x-auto sm:rounded-lg">
    <Header :title="title" :title-previous="'Turmas'" :route-back="'/turmas'" />
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <form @submit.prevent="handleFormSubmit" class="space-y-6">
        <div class="grid grid-cols-1 gap-6">
          <Input type="text" id="name" v-model="name" required placeholder="Nome" label="Nome"
            :error-message="errors?.name" />

          <Input type="textarea" id="descricao" v-model="description" required placeholder="Descrição"
            label="Descrição" :error-message="errors?.description" />
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
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useValidation } from '@/composables/useValidation'
import { useAuthStore } from '@/stores/authStore'
import request from '@/services/request'
import Swal from 'sweetalert2'

const { errors, validate, hasErrors } = useValidation()

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const classId = computed(() => route.params.id as string | null)

const title = computed(() => {
  return classId.value ? 'Editar Turma' : 'Adicionar'
})

const isLoading = ref(false)

const userId = authStore.user?.id ?? ''
const name = ref('')
const description = ref('')

const handleFormSubmit = async () => {
  if (isLoading.value) return

  isLoading.value = true

  errors.value = {}

  validate('name', name.value, { required: true, min: 3 })
  validate('description', description.value, { required: true, min: 3 })

  const data = {
    userId,
    name: name.value,
    description: description.value,
  }

  try {
    const response = classId.value
      ? await request.put(`/turmas/${classId.value}`, data)
      : await request.post('/turmas', data)

    if ([200, 201].includes(response.status)) {
      const message = `Turma ${classId.value ? 'editada' : 'adicionada'} com sucesso!`

      await Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: message,
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
        timer: 2500,
      })

      isLoading.value = false
      router.push('/turmas')
    }
  } catch (error: any) {
    await Swal.fire({
      icon: 'error',
      title: 'Erro!',
      text: `Erro ao ${classId.value ? 'editar' : 'adicionar'} a turma. Tente novamente.`,
      confirmButtonText: 'OK',
      confirmButtonColor: '#d33',
    })

    isLoading.value = false
  }
}

const loadInfo = async () => {
  try {
    isLoading.value = true
    const response = await request.get(`/turmas/${classId.value}`)

    if (response.status === 200) {
      const data = response.data
      name.value = data.name
      description.value = data.description
    }
  } catch (error) {
    console.error('Erro ao carregar as turmas:', error)
    router.push('/turmas')
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  if (classId.value) {
    loadInfo()
  }
})
</script>

<style scoped></style>
