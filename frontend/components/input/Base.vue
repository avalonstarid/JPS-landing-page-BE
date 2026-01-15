<template>
  <el-form-item
    class="!mb-0 !w-full"
    :error="error"
    :label="label"
    :prop="name"
  >
    <el-input
      :placeholder="`Masukan ${label ?? 'disini'}`"
      clearable
      v-bind="$attrs"
    >
      <template #[name]="slotData" v-for="(_, name) in $slots">
        <slot :name="name" v-bind="slotData" />
      </template>
    </el-input>

    <span v-if="showHelptext" class="text-gray-500 text-xs">
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
