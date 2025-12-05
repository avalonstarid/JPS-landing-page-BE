<template>
  <el-form
    ref="formRef"
    class="card"
    :model="appVersion"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="card-body grid grid-cols-3 gap-4">
      <InputBase
        v-model="appVersion.name"
        :errors="errors"
        label="Nama Versi"
        name="name"
      />

      <InputNumber
        v-model="appVersion.code"
        :controls="false"
        :errors="errors"
        :min="0"
        label="Kode Versi"
        name="code"
        value-on-clear="min"
      />

      <InputBase
        v-model="appVersion.url"
        :errors="errors"
        label="URL Download"
        name="url"
      />
    </div>
    <div class="card-footer justify-start gap-3">
      <BtnIndicator class="btn-sm" :loading="loading">
        <i class="ki-filled ki-arrow-circle-right"></i> Submit
      </BtnIndicator>
    </div>
  </el-form>
</template>

<script setup lang="ts">
import useAppVersions from '@/composables/settings/app-versions'
import type { FormInstance, FormRules } from 'element-plus'

definePageMeta({
  title: 'App Version',
  breadcrumbs: [{ title: 'Settings' }],
  // authorize: ['permission_read'],
})

const formRef = ref<FormInstance>()
const { appVersion, getAppVersion, storeAppVersion, loading, errors } =
  useAppVersions()

// Create Rules Form
const rules = reactive<FormRules>({
  code: [
    {
      required: true,
      message: 'Kode Versi wajib diisi.',
      trigger: 'blur',
    },
  ],
  name: [
    {
      required: true,
      message: 'Nama Versi wajib diisi.',
      trigger: 'blur',
    },
  ],
  url: [
    {
      required: true,
      message: 'URL Download wajib diisi.',
      trigger: 'blur',
    },
  ],
})

// Form Submit Function
const onSubmit = async (formEl: FormInstance | undefined) => {
  if (!formEl) return

  await formEl.validate(async (valid, fields) => {
    if (valid) {
      await storeAppVersion({ ...appVersion.value })
    }
  })
}

onMounted(() => {
  getAppVersion()
})
</script>
