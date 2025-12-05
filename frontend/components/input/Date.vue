<template>
  <el-form-item class="!mb-0" :error="error" :label="label" :prop="name">
    <el-date-picker
      class="!w-full"
      :editable="false"
      :placeholder="`Pilih ${label ?? 'disini'}`"
      format="YYYY-MM-DD"
      type="date"
      value-format="YYYY-MM-DD"
      arrow-control
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
  label: String,
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
