<template>
  <div class="relative overflow-x-auto sm:rounded-lg">
    <Header :title="title" :title-previous="'Alunos'" :route-back="'/alunos'" />

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <form @submit.prevent="handleFormSubmit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <Input type="text" id="name" v-model="name" required placeholder="Nome" label="Nome"
            :error-message="errors?.name" />

          <Input type="text" id="cpf" v-model="cpf" required placeholder="CPF" label="CPF" :error-message="errors?.cpf"
            :max-length="18" format-type="cpf" />

          
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <Input type="text" id="nascimento" v-model="birth" required format-type="data" placeholder="Nascimento"
            label="Data de Nascimento" :error-message="errors?.birth" />

          <Input type="text" id="email" v-model="email" required placeholder="E-mail" label="E-mail"
            :error-message="errors?.email" />

          <Input type="password" id="senha" v-model="password" :required="!studentId" placeholder="Senha" label="Senha"
            :error-message="errors?.password" />
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
import { formatDate } from '@/helpers/formatters'
import Swal from 'sweetalert2'

const { errors, validate, hasErrors } = useValidation()

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const studentId = computed(() => route.params.id as string | null)

const title = computed(() => {
  return studentId.value ? 'Editar Aluno' : 'Adicionar'
})

const isLoading = ref(false)

const userId = authStore.user?.id ?? ''
const name = ref('')
const birth = ref('')
const cpf = ref('')
const email = ref('')
const password = ref('')

const handleFormSubmit = async () => {
  if (isLoading.value) return

  isLoading.value = true
  errors.value = {}
  validate('name', name.value, { required: true, min: 3 })
  validate('birth', birth.value, { required: true })
  validate('cpf', cpf.value, { required: true, type: 'cpf' })
  validate('email', email.value, { required: true, type: 'email' })

  if (!studentId.value && password.value) {
    validate('password', password.value, { required: true, type: 'passwordStrong' })
  }

  const data = {
    userId,
    name: name.value,
    birth: birth.value,
    cpf: cpf.value,
    email: email.value,
    password: password.value,
  }

  try {
    const response = studentId.value
      ? await request.put(`/alunos/${studentId.value}`, data)
      : await request.post('/alunos', data)

    if (response.status === 200 || response.status === 201) {
      const message = `Aluno ${studentId.value ? 'editado' : 'adicionado'} com sucesso!`

      await Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: message,
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
        timer: 2500,
      })

      isLoading.value = false
      router.push('/alunos')
    }
  } catch (error: any) {
    await Swal.fire({
      icon: 'error',
      title: 'Erro!',
      text: `Erro ao ${studentId.value ? 'editar' : 'adicionar'} o aluno: ${response.error}`,
      showConfirmButton: true,
      confirmButtonText: 'OK',
      confirmButtonColor: '#d33',
    })

    isLoading.value = false
  } finally {
    isLoading.value = false
  }
}

const loadInfo = async () => {
  try {
    isLoading.value = true
    const response = await request.get(`/alunos/${studentId.value}`)

    if (response.status === 200) {
      const data = response.data
      name.value = data.name
      birth.value = formatDate(data.birth)
      cpf.value = data.cpf
      email.value = data.email
      password.value = ''
    }
  } catch (error) {
    console.error('Erro ao carregar os alunos:', error)
    router.push('/alunos')
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  if (studentId.value) {
    loadInfo()
  }
})
</script>

<style scoped></style>
