<template>
  <div class="mb-4">
    <label class="mb-2 form-label">{{ label }}</label>

    <div class="input-file-list">
      <!-- Hidden File Input -->
      <input
        ref="fileInput"
        class="hidden"
        :multiple="multiple"
        :accept="accept"
        type="file"
        @change="handleFiles"
      />

      <div class="flex flex-col gap-2">
        <!-- File List -->
        <div
          v-for="(file, index) in currentList"
          :key="index"
          class="group relative flex items-center bg-gray-50 hover:bg-gray-100 p-3 border border-gray-200 rounded-lg transition-colors cursor-move"
          :draggable="true"
          @dragend="dragEnd"
          @dragover.prevent
          @dragstart="dragStart(index)"
          @drop="drop(index)"
        >
          <div class="mr-3 text-gray-500">
            <i class="ki-outline text-2xl ki-file"></i>
          </div>

          <div class="flex-1 min-w-0">
            <div class="font-medium text-gray-700 text-sm truncate">
              {{ getFileName(file) }}
            </div>
            <div v-if="getFileSize(file)" class="text-gray-400 text-xs">
              {{ getFileSize(file) }}
            </div>
          </div>

          <!-- Remove Button -->
          <button
            class="hover:bg-red-50 ml-2 text-gray-500 hover:text-danger btn btn-icon btn-icon-sm btn-light"
            type="button"
            @click="removeFile(index)"
          >
            <i class="ki-outline text-sm ki-trash"></i>
          </button>
        </div>

        <!-- Add Button -->
        <div
          v-if="
            (!limit || currentList.length < limit) &&
            (multiple || currentList.length < 1)
          "
          class="flex justify-center items-center bg-white hover:bg-gray-50 p-4 border-2 border-gray-300 hover:border-primary border-dashed rounded-lg font-medium hover:text-primary text-sm transition-all cursor-pointer"
          @click="triggerFileInput"
        >
          <i class="mr-2 ki-outline text-lg ki-file-up"></i>
          <span>Upload File</span>
        </div>
      </div>

      <!-- Error Message -->
      <div v-if="error" class="mt-1 text-danger text-xs">
        {{ error }}
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { formatBytes } from '@/helpers/helpers'
import { computed, ref } from 'vue'

const props = defineProps({
  label: {
    type: String,
    default: 'List File',
  },
  modelValue: {
    type: [Array, String, Object] as PropType<
      (string | File | any)[] | string | File
    >,
    default: () => [],
  },
  multiple: {
    type: Boolean,
    default: true,
  },
  limit: {
    type: Number,
    default: 0, // 0 means unlimited
  },
  errors: {
    type: [Object, String],
    default: null,
  },
  name: {
    type: String,
    default: '',
  },
  accept: {
    type: String,
    default: '*/*',
  },
})

const emit = defineEmits(['update:modelValue', 'change', 'remove'])

const fileInput = ref<HTMLInputElement>()
const draggedIndex = ref<number | null>(null)

// Error handling
const error = computed(() => {
  if (typeof props.errors === 'string') return null
  return props.errors?.errors?.[props.name]
    ? props.errors.errors[props.name][0]
    : null
})

const getFileName = (file: string | File | any) => {
  if (file instanceof File) {
    return file.name
  }
  if (typeof file === 'object') {
    return (
      file?.file_name ||
      file?.name ||
      file?.original_url?.split('/').pop() ||
      'Unknown File'
    )
  }
  if (typeof file === 'string') {
    return file.split('/').pop()
  }
  return 'Unknown File'
}

const getFileSize = (file: string | File | any) => {
  if (file instanceof File) {
    const size = file.size

    return formatBytes(size)
  }
  if (typeof file === 'object') {
    return formatBytes(file?.size)
  }
  return null
}

const triggerFileInput = () => {
  fileInput.value?.click()
}

const currentList = computed((): (string | File | any)[] => {
  if (!props.multiple) {
    if (props.modelValue && !Array.isArray(props.modelValue)) {
      return [props.modelValue as string | File]
    }
    return []
  }
  return Array.isArray(props.modelValue)
    ? (props.modelValue as (string | File | any)[])
    : []
})

const handleFiles = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (!target.files) return

  const newFiles = Array.from(target.files)

  if (!props.multiple) {
    if (newFiles.length > 0) {
      emit('update:modelValue', newFiles[0])
      emit('change', newFiles[0])
    }
  } else {
    // Multiple logic
    const updatedList = [
      ...(currentList.value as (string | File | any)[]),
      ...newFiles,
    ]

    if (props.limit && updatedList.length > props.limit) {
      updatedList.length = props.limit
    }

    emit('update:modelValue', updatedList)
    emit('change', updatedList)
  }

  // Reset input so same files can be selected again if needed
  target.value = ''
}

const removeFile = (index: number) => {
  if (!props.multiple) {
    emit('update:modelValue', null)
    emit('change', null)
    return
  }

  const list = currentList.value as (string | File | any)[]
  const removedItem = list[index]
  const updatedList = [...list]
  updatedList.splice(index, 1)

  emit('update:modelValue', updatedList)
  emit('change', updatedList)
  emit('remove', removedItem)
}

// Drag and Drop Logic
const dragStart = (index: number) => {
  if (!props.multiple) return
  draggedIndex.value = index
}

const drop = (index: number) => {
  if (!props.multiple) return
  if (draggedIndex.value === null || draggedIndex.value === index) return

  const updatedList = [...(currentList.value as (string | File)[])]
  const [removed] = updatedList.splice(draggedIndex.value, 1)
  updatedList.splice(index, 0, removed)

  emit('update:modelValue', updatedList)
  emit('change', updatedList)

  dragEnd()
}

const dragEnd = () => {
  draggedIndex.value = null
}
</script>

<style scoped>
/* Scoped styles if needed */
</style>
