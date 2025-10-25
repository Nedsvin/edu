<template>
  <div class="flex items-center justify-between pb-4 mr-8">
    <h2 class="text-2xl mt-8 font-bold text-gray-900 text-center w-full flex items-center space-x-2">
      <Link :to="routeBack" class="hover:underline" v-if="showPrevious">
      {{ titlePrevious }}
      </Link>
    </h2>
    <Button type="outline" width="small" :redirect-to="routeBack" v-if="routeBack">
      {{ textButtonBack }}
    </Button>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
const props = defineProps({
  title: String,
  titlePrevious: String,
  routeBack: String,
  textButtonBack: {
    type: String,
    default: 'Voltar',
  },
})

const showPrevious = computed(() => {
  return props.routeBack && props.titlePrevious
})
</script>
