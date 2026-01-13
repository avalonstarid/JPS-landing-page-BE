<template>
  <div class="mb-4">
    <label class="mb-2 form-label">{{ label }}</label>

    <div class="input-image-list">
      <!-- Hidden File Input -->
      <input
        ref="fileInput"
        class="hidden"
        :multiple="multiple"
        accept="image/*"
        type="file"
        @change="handleFiles"
      />

      <div class="flex flex-wrap gap-4">
        <!-- Image List -->
        <div
          v-for="(image, index) in currentList"
          :key="index"
          class="relative bg-cover bg-center border-2 border-gray-200 rounded-lg size-24 cursor-move"
          :draggable="true"
          :style="`background-image: url(${getImageUrl(image)})`"
          @dragend="dragEnd"
          @dragover.prevent
          @dragstart="dragStart(index)"
          @drop="drop(index)"
        >
          <!-- Remove Button -->
          <button
            class="-top-2 -right-2 z-1 absolute shadow-default rounded-full size-5 btn btn-icon btn-icon-xs btn-light"
            type="button"
            @click="removeImage(index)"
          >
            <i class="ki-outline text-xs ki-cross"></i>
          </button>
        </div>

        <!-- Add Button -->
        <div
          v-if="
            (!limit || currentList.length < limit) &&
            (multiple || currentList.length < 1)
          "
          class="flex flex-col justify-center items-center bg-gray-50 border-2 border-gray-300 hover:border-primary border-dashed rounded-lg size-24 hover:text-primary transition-colors cursor-pointer"
          @click="triggerFileInput"
        >
          <i class="mb-1 ki-outline text-2xl ki-plus"></i>
          <span class="font-medium text-gray-500 text-xs">Add</span>
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
const props = defineProps({
  label: {
    type: String,
    default: 'List Gambar',
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
})

const emit = defineEmits(['update:modelValue', 'change', 'remove'])

const fileInput = ref<HTMLInputElement>()
const draggedIndex = ref<number | null>(null)

// Error handling similar to Base.vue and InputImage.vue
const error = computed(() => {
  if (typeof props.errors === 'string') return null
  return props.errors?.errors?.[props.name]
    ? props.errors.errors[props.name][0]
    : null
})

const getImageUrl = (image: string | File | any) => {
  if (image instanceof File) {
    return URL.createObjectURL(image)
  }
  if (typeof image === 'object' && image?.original_url) {
    return image.original_url
  }
  return image
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

const removeImage = (index: number) => {
  if (!props.multiple) {
    emit('update:modelValue', null)
    emit('change', null)
    emit('remove', null)
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
/* Optional: Add a placeholder style if needed, 
   though inline styles are handling most things matching InputImage.vue */
</style>
