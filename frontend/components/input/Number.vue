<template>
  <el-form-item class="!mb-0" :error="error" :label="label" :prop="name">
    <el-input-number
      class="!w-full"
      :placeholder="`Masukan ${label}`"
      controls-position="right"
      clearable
      v-bind="$attrs"
    />

    <span v-if="showHelptext" class="text-xs text-gray-500">
      {{ helpText }}
    </span>
  </el-form-item>
</template>

<script setup lang="ts">
const props = defineProps({
  errors: [Object, String],
  helpText: String,
  label: {
    type: String,
    required: true,
  },
  name: {
    type: String,
    required: true,
  },
  showHelptext: {
    type: Boolean,
    default: false,
  },
})

const error = computed(() => {
  if (typeof props.errors === 'string') return null

  return props.errors?.errors?.[props.name]
    ? props.errors.errors[props.name][0]
    : null
})
</script>
