<template>
  <div class="relative overflow-hidden bg-transparent">
    <div class="p-4 md:p-6 max-w-7xl mx-auto space-y-4">
      <div v-if="dataLink ? isLoadingInternal : isLoading"
        class="absolute inset-0 flex items-center justify-center bg-white/60 backdrop-blur-sm z-40">
        <div class="flex flex-col items-center gap-3">
          <div class="w-12 h-12 border-4 border-t-indigo-600 border-gray-200 rounded-full animate-spin"></div>
          <div class="text-sm text-gray-600">Carregando...</div>
        </div>
      </div>

      <div v-if="showAdvancedFilters" class="bg-white rounded-lg shadow-sm p-4 md:p-6 border">
        <strong class="text-gray-700 block mb-4">Filtros</strong>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
          <template v-for="filter in filters" :key="filter.key">
            <div class="w-full">
              <Select v-if="filter?.type === 'select'" :id="filter.key" :label="filter.label" :options="filter.options"
                v-model="filter.value" />
              <div v-else>
                <div class="w-full">
                  <Input type="text" :id="filter.key" v-model="filter.value" :placeholder="filter.placeholder"
                    width="full" :label="filter.label" @update:modelValue="(v) => onInput(filter.key, v)"
                    @input="() => onInput(filter.key, filter.value)" />
                </div>

                <p v-if="errors[filter.key]" class="text-red-600 text-sm absolute">
                  {{ errors[filter.key] }}
                </p>
              </div>
            </div>
          </template>

          <div class="flex gap-2 md:col-span-1 justify-start">
            <Button class="text-sm" type="primary" @click="clearFilters">Limpar</Button>
            <Button class="text-sm" type="outline" @click="applyFilters">Aplicar</Button>
            <Button class="text-sm font-bold" v-if="!isHidden" type="primary" :redirect-to="newItemPath">
              {{ buttonLabel }}
            </Button>
          </div>
        </div>
      </div>

      <hr v-if="showAdvancedFilters" class="border-gray-200" />

      <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="hidden md:block">
          <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-50">
              <tr>
                <th v-for="column in columns" :key="column.key"
                  class="px-5 py-3 font-medium text-gray-700 whitespace-nowrap">
                  {{ column.label }}
                </th>
                <th v-if="showActions" class="px-5 py-3" />
              </tr>
            </thead>

            <tbody>
              <tr v-for="item in localData" :key="item.id"
                class="border-t last:border-b hover:bg-gray-50 transition-colors">
                <td v-for="column in columns" :key="column.key" class="px-5 py-4 align-top whitespace-nowrap">
                  <span v-if="!column?.mask" class="text-sm text-gray-700">
                    {{ item[column.key] }}
                  </span>
                  <span v-else class="text-sm text-gray-700">
                    {{ applyMask(item[column.key], column?.mask) }}
                  </span>
                </td>

                <td v-if="showActions" class="px-5 py-4">
                  <div class="flex items-center gap-2 flex-wrap">
                    <Button v-if="editLink" type="success" width="small" :redirect-to="editLink + item.id">
                      Editar
                    </Button>

                    <Button v-if="customDelete" type="danger" width="small" @click="customDelete(item)"
                      :is-loading="isLoadingDelete">
                      Excluir
                    </Button>
                  </div>
                </td>
              </tr>

              <tr v-if="localData.length === 0">
                <td :colspan="columns.length + (showActions ? 1 : 0)" class="px-5 py-8 text-center text-gray-500">
                  Nenhum registro encontrado.
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="md:hidden space-y-3 p-3">
          <template v-if="localData.length">
            <article v-for="item in localData" :key="item.id" class="bg-white border rounded-lg shadow-sm p-4">
              <div class="flex justify-between items-start gap-4">
                <div class="flex-1">
                  <div class="grid grid-cols-1 gap-2">
                    <template v-for="column in columns" :key="column.key">
                      <div class="text-xs text-gray-500">{{ column.label }}</div>
                      <div class="text-sm text-gray-800 font-medium">
                        <span v-if="!column?.mask">{{ item[column.key] }}</span>
                        <span v-else>{{ applyMask(item[column.key], column?.mask) }}</span>
                      </div>
                    </template>
                  </div>
                </div>
                <div class="flex flex-col items-end gap-2">
                  <Button v-if="editLink" type="success" width="small" :redirect-to="editLink + item.id">
                    Edit
                  </Button>

                  <Button v-if="customDelete" type="danger" width="small" @click="customDelete(item)"
                    :is-loading="isLoadingDelete">
                    Excluir
                  </Button>
                </div>
              </div>
            </article>
          </template>

          <template v-else>
            <div class="py-8 text-center text-gray-500">Nenhum registro encontrado.</div>
          </template>
        </div>
      </div>

      <div class="flex flex-col md:flex-row items-center justify-between gap-3 bg-white rounded-lg p-4 shadow-sm">
        <div class="w-full md:w-48">
          <Select v-model="itemsPerPage" :options="itemsPerPageOptions" width="full" />
        </div>

        <div class="text-sm text-gray-600 w-full text-center md:text-left">
          Mostrando {{ localData.length }} até o total de {{ totalItems }} registros
        </div>

        <nav class="flex items-center gap-2 w-full md:w-auto justify-center md:justify-end">
          <Button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" width="sm">
            <i class="material-icons">chevron_left</i>
          </Button>

          <div class="inline-flex gap-2">
            <span v-for="page in visiblePages" :key="page">
              <Button @click="changePage(page)" type="outline" :disabled="page === currentPage" width="sm">
                {{ page }}
              </Button>
            </span>
          </div>

          <Button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" width="sm">
            <i class="material-icons">chevron_right</i>
          </Button>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import request from '@/services/request'
import Swal from 'sweetalert2'
import { ref, computed, onMounted, watch, reactive } from 'vue'
import {
  formatCpf,
  formatDate,
  formatCpfCnpj,
  formatCep,
  formatPhone,
  formatCurrency,
} from '@/helpers/formatters'
import Select from './standard/Select.vue'
import { useRoute } from 'vue-router'

const route = useRoute()

interface Column {
  key: string
  label: string
  size?: string
  mask?: string
}

const props = defineProps<{
  columns: Column[]
  filters?: any[]
  advancedFilter: boolean
  editLink?: string
  deleteLink?: string
  dataLink?: string
  customDelete?: string
}>()

const currentPage = ref(1)
const itemsPerPage = ref(10)
const totalItems = ref(0)
const localData = ref<any[]>([])
const isLoading = ref(false)
const isLoadingDelete = ref(false)
const showAdvancedFilters = ref(props.advancedFilter)
const itemsPerPageOptions = [
  { value: 5, label: '5' },
  { value: 10, label: '10' },
  { value: 25, label: '25' },
  { value: 50, label: '50' },
]
const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage.value))
const showActions = computed(() => props.editLink || props.deleteLink || props.customDelete)
const filters = props.filters ?? []

const loadInfo = async () => {
  if (!props.dataLink) return
  isLoading.value = true
  localData.value = []
  totalItems.value = 0
  try {
    const optionsFilters = [...props.filters]
    let filtersQuery = ''
    if (optionsFilters.length > 0) {
      optionsFilters.forEach((filter) => {
        if (filter.value === null || typeof filter.value === 'undefined') return
        filtersQuery += `&${filter.key}=${filter.value}`
      })
    }
    const response = await request.get(`${props.dataLink}?page=${currentPage.value}&itemsPerPage=${itemsPerPage.value}${filtersQuery}`)
    if (response.status === 200) {
      localData.value = response.data.data || []
      totalItems.value = response.data.total || 0
    }
  } catch (error) {
    console.error('Erro ao carregar os dados:', error)
  } finally {
    isLoading.value = false
  }
}

const visiblePages = computed(() => {
  const pagesToShow = 5
  const half = Math.floor(pagesToShow / 2)
  let start = currentPage.value - half
  let end = currentPage.value + half
  if (start < 1) {
    start = 1
    end = pagesToShow
  }
  if (end > totalPages.value) {
    end = totalPages.value
    start = totalPages.value - pagesToShow + 1
    if (start < 1) start = 1
  }
  return Array.from({ length: end - start + 1 }, (_, i) => start + i)
})

const changePage = (page: number) => {
  if (page > 0 && page <= totalPages.value) {
    currentPage.value = page
    loadInfo()
  }
}

const customDelete = async (item: any) => {
  if (props.customDelete === 'deleteRegistration') {
    await deleteRegistration(item)
  }
}

const deleteRegistration = async (item: any): Promise<void> => {
  const rawPath = window.location.pathname || '';
  const path = rawPath.toLowerCase();

  let endpoint = '/matriculas';
  let confirmText = 'Quer deletar os dados desta matricula?';

  if (path.startsWith('/alunos') || path.startsWith('/aluno')) {
    endpoint = '/alunos';
    confirmText = 'Quer mesmo deletar este aluno?';
  }
  else if (path.startsWith('/turmas') || path.startsWith('/turma')) {
    endpoint = '/turmas';
    confirmText = 'Quer mesmo deletar esta turma?';
  }

  const result = await Swal.fire({
    title: 'Tem certeza?',
    html: confirmText,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sim, excluir!',
    cancelButtonText: 'Cancelar',
  });

  if (!result.isConfirmed) return;

  try {
    isLoadingDelete.value = true;

    if (path.startsWith('/matriculas')) {
      const data = {
        student_id: item.student_id,
        class_id: item.class_id,
      };

      await request.delete(endpoint, { data });
    } else {
      await request.delete(`${endpoint}/${item.id}`);
    }

    await Swal.fire({
      icon: 'success',
      title: 'Sucesso!',
      text: 'Item excluído com sucesso.',
      showConfirmButton: true,
      confirmButtonText: 'OK',
      confirmButtonColor: '#3085d6',
      timer: 3000,
    });

    if (typeof loadInfo === 'function') await loadInfo();
  } catch (err) {
    console.error(err);
    await Swal.fire({
      icon: 'error',
      title: 'Erro',
      text: 'Não foi possível excluir. Tente novamente.',
      confirmButtonText: 'OK',
      confirmButtonColor: '#3085d6',
    });
  } finally {
    isLoadingDelete.value = false;
  }
};

const applyMask = (value: string, mask: string) => {
  if (!value) return ''
  switch (mask) {
    case 'cpf': return formatCpf(value)
    case 'date': return formatDate(value)
    case 'cpfCnpj': return formatCpfCnpj(value)
    case 'cep': return formatCep(value)
    case 'phone': return formatPhone(value)
    case 'currency': return formatCurrency(Number(value))
    default: return value
  }
}

const clearFilters = () => {
  props.filters.forEach((filter) => {
    filter.value = null
  })
  loadInfo()
}

const errors = reactive<Record<string, string>>({})

const clearError = (key: string) => {
  if (errors[key]) delete errors[key]
}

const onInput = (key: string, val: string) => {
  if (typeof val === 'string' && val.trim() !== '') {
    clearError(key)
  }
}

const applyFilters = () => {
  for (const k in errors) delete errors[k]
  let hasInvalid = false

  for (const filter of filters) {
    if (filter?.type !== 'select') {
      const val = filter.value ?? ''
      if (typeof val === 'string') {
        const trimmed = val.trim()
        if (val !== '' && (trimmed === '' || val.startsWith(' '))) {
          errors[filter.key] = 'Caracteres inválidos.'
          hasInvalid = true
        }
      }
    }
  }

  if (hasInvalid) return
  currentPage.value = 1
  loadInfo()
}


watch(itemsPerPage, () => {
  currentPage.value = 1
  loadInfo()
})

onMounted(() => {
  loadInfo()
})

const baseSegment = computed(() => {
  const seg = (route.path || '').split('/')[1] || ''
  return seg || 'matriculas'
})

const suffixMap: Record<string, string> = {
  matriculas: 'nova',
  alunos: 'novo',
  turmas: 'novo',
}

const newItemPath = computed(() => {
  const base = baseSegment.value
  const suffix = suffixMap[base] ?? 'novo'
  return `/${base}/${suffix}`
})

const labelMap: Record<string, string> = {
  matriculas: 'Nova Matrícula',
  alunos: 'Adicionar',
  turmas: 'Nova Turma',
}
const buttonLabel = computed(() => labelMap[baseSegment.value] ?? 'Novo')
const isHidden = computed(() => baseSegment.value === 'usuarios')
</script>

<style scoped>
.loader {
  border: 4px solid #f3f3f3;
  border-top: 4px solid #3498db;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}
</style>
