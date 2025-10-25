<template>
  <div class="c-login-page">
    <h2 class="text-3xl font-bold text-center text-gray-900 mb-6">
      Bem-vindo de volta!
    </h2>
    <p class="text-sm text-center text-gray-600 mb-8">
      Entre com seu e-mail e senha para continuar
    </p>
    <form @submit.prevent="handleLogin" class="space-y-6">
      <Row>
        <Input
          type="text"
          id="email"
          v-model="email"
          required
          placeholder="Informe seu e-mail"
          label="Email"
          :error-message="errors?.email"
        />
      </Row>
      <Row>
        <Input
          type="password"
          id="password"
          v-model="password"
          required
          placeholder="Informe sua senha"
          label="Senha"
          :error-message="errors?.password"
        />
      </Row>
      <Row>
        <Button
          type="primary"
          @click="handleLogin"
          :is-loading="isLoading"
          class="w-full"
        >
          Entrar
        </Button>
      </Row>
    </form>
  </div>
</template>

<script setup lang="ts">
  import { ref } from 'vue'
  import { useValidation } from '@/composables/useValidation'
  import { useRouter } from 'vue-router'
  import { useAuthStore } from '@/stores/authStore'
  import request from '@/services/request'

  const { errors, validate, hasErrors } = useValidation()
  const email = ref('')
  const password = ref('')
  const router = useRouter()
  const isLoading = ref(false)

  const handleLogin = async () => {
    isLoading.value = true

    errors.value = {}

    validate('email', email.value, {
      required: true,
      minLength: 8,
      type: 'email',
    })
    validate('password', password.value, { required: true, minLength: 8 })

    if (hasErrors.value) {
      isLoading.value = false
      return
    }

    const data = {
      email: email.value,
      password: password.value,
    }

    try {
      const response = await request.post('/login', data)

      if (response.status === 200) {
        const authStore = useAuthStore()
        const user = {
          id: response.data?.user.id || 0,
          name: response.data?.user.name || '',
          email: response.data?.user.email || '',
          role: response.data?.user.role || '',
        }

        authStore.login(user, response.data.token)
        router.push('/home')
      }

    } finally {
      isLoading.value = false
    }
  }
</script>

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
</style>
