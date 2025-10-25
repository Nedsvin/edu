<template>
  <div :class="containerClasses">
    <label :for="id" class="block text-sm font-medium text-gray-700 mb-2">
      <span :class="error ? 'text-red-500' : ''">{{ label }}</span>
      <span v-if="required" class="text-red-500">*</span>
    </label>

    <div class="relative">

      <input v-if="type === 'money'" ref="inputRef" :id="id" v-model="internalValue" v-money3="moneyOptions"
        :class="inputClasses" :aria-invalid="errorMessage ? 'true' : 'false'" />

      <input v-else-if="!formatType" ref="inputRef" :id="id" v-model="internalValue" :type="type"
        :placeholder="placeholder" :class="inputClasses" :maxlength="maxLength"
        :aria-invalid="errorMessage ? 'true' : 'false'" />

      <input v-else ref="inputRef" :id="id" v-model="internalValue" v-maska="maskPattern" :type="type"
        :placeholder="placeholder" :class="inputClasses" :maxlength="maxLength"
        :aria-invalid="errorMessage ? 'true' : 'false'" />

      <p v-if="errorMessage" class="text-red-500 text-sm mt-2">
        {{ errorMessage }}
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, defineProps, watch } from 'vue'
import { vMaska } from 'maska/vue'

const props = defineProps({
  id: { type: String, required: true },
  label: { type: String, required: true },
  type: { type: String, default: 'text' },
  placeholder: { type: String, default: '' },
  required: { type: Boolean, default: false },
  errorMessage: { type: String, default: '' },
  width: { type: String, default: 'full' },
  height: { type: String, default: '10' },
  maxLength: { type: Number, default: 250 },
  modelValue: { type: String, required: true },
  formatType: { type: String, default: '' },
})

const emits = defineEmits(['update:modelValue'])
const internalValue = ref(props.modelValue ?? '')
const inputRef = ref<HTMLInputElement | null>(null)
const error = computed(() => props.errorMessage !== '')
const containerClasses = computed(() => {
  return props.width.startsWith('w-') ? `${props.width}` : `w-${props.width}`
})

const inputClasses = computed(
  () =>
    `w-full h-${props.height} py-2 px-3 border rounded-lg text-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none ${props.errorMessage ? 'border-red-500' : 'border-gray-300'
    }`
)
watch(
  () => props.modelValue,
  (newValue) => {
    internalValue.value = newValue
  }
)

watch(internalValue, (newValue) => {
  if (props.type === 'money') {
    newValue = Number(newValue.replace(/[^0-9]/g, ''))
  }

  emits('update:modelValue', newValue)
})

const maskPattern = computed(() => {
  switch (props.formatType) {
    case 'data':
      return '##/##/####'
    case 'cep':
      return '#####-###'
    case 'cpfCnpj':
      return cleanValue(props.modelValue).length < 12
        ? '###.###.###-###'
        : '##.###.###/####-##'
    case 'cpf':
      return '###.###.###-##'
    case 'telefone':
    case 'fone':
      return cleanValue(props.modelValue).length < 11
        ? '(##) ####-#####'
        : '(##) # ####-####'
    default:
      return ''
  }
})

const moneyOptions = {
  prefix: 'R$ ',
  suffix: '',
  thousands: '.',
  decimal: ',',
  precision: 2,
}

const cleanValue = (value: string) => value.replace(/[^0-9]/g, '')
</script>

<style scoped>
.money3 {
  width: 100%;
}
</style>
