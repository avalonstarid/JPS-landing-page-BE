<template>
  <el-form-item class="!mb-0" :error="error" :label="label" :prop="name">
    <el-input
      ref="inputRef"
      v-model="formattedValue"
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
import { CurrencyDisplay, useCurrencyInput } from 'vue-currency-input'

const props = defineProps({
  currency: {
    type: String,
    default: 'IDR',
  },
  currencyDisplay: {
    type: String as PropType<CurrencyDisplay>,
    default: CurrencyDisplay.narrowSymbol,
  },
  errors: [Object, String],
  helpText: String,
  label: String,
  locale: {
    type: String,
    default: 'id',
  },
  modelValue: {
    type: [Number, String],
    default: null,
  },
  name: {
    type: String,
    required: true,
  },
  precision: {
    type: Number,
    default: 0,
  },
  showHelptext: {
    type: Boolean,
    default: false,
  },
})

const { inputRef, formattedValue, setValue } = useCurrencyInput({
  currency: props.currency,
  currencyDisplay: props.currencyDisplay,
  hideCurrencySymbolOnFocus: false,
  hideGroupingSeparatorOnFocus: false,
  hideNegligibleDecimalDigitsOnFocus: false,
  locale: props.locale,
  precision: props.precision,
})

const error = computed(() => {
  if (typeof props.errors === 'string') return null

  return props.errors?.errors?.[props.name]
    ? props.errors.errors[props.name][0]
    : null
})

watch(
  () => props.modelValue,
  (newVal) => {
    if (typeof newVal === 'number' || newVal === null || newVal === '') {
      setValue(newVal === '' ? null : newVal)
    }
  },
  { immediate: true },
)
</script>
