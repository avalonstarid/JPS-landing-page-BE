<template>
  <el-form
    ref="formRef"
    class="card w-full"
    :model="model"
    :rules="rules"
    label-width="auto"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="card-header">
      <h3 class="card-title">Ganti Kata Sandi</h3>
    </div>
    <div class="card-body grid gap-4">
      <InputBase
        v-model="model.old_password"
        :errors="errors"
        label="Kata Sandi Lama"
        name="old_password"
        type="password"
        show-password
      />

      <InputBase
        v-model="model.password"
        :errors="errors"
        label="Kata Sandi Baru"
        name="password"
        type="password"
        show-password
      />

      <InputBase
        v-model="model.password_confirmation"
        :errors="errors"
        label="Konfirmasi Kata Sandi Baru"
        name="password_confirmation"
        type="password"
        show-password
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
import useUsers from '@/composables/user-management/users'
import type { FormInstance, FormRules } from 'element-plus'

definePageMeta({
  title: 'Ganti Kata Sandi',
  breadcrumbs: [{ title: 'Profil' }],
})

const formRef = ref<FormInstance>()
const { errors, loading, updatePassword } = useUsers()
const model = ref<any>({
  old_password: '',
  password: '',
  password_confirmation: '',
})

// Create Rules Form
const rules = reactive<FormRules>({
  old_password: [
    {
      required: true,
      message: 'Kata Sandi Lama wajib diisi.',
      trigger: 'blur',
    },
  ],
  password: [
    {
      required: true,
      message: 'Kata Sandi Baru wajib diisi.',
      trigger: 'blur',
    },
    { min: 8, message: 'Minimal 8 huruf.', trigger: 'blur' },
  ],
  password_confirmation: [
    {
      required: true,
      message: 'Konfirmasi Kata Sandi wajib diisi.',
      trigger: 'blur',
    },
    { min: 8, message: 'Minimal 8 huruf.', trigger: 'blur' },
    {
      message: 'Kata Sandi tidak cocok.',
      trigger: 'blur',
      validator: (rule, value) => value === model.value.password,
    },
  ],
})

// Form Submit Function
const onSubmit = async (formEl: FormInstance | undefined) => {
  if (!formEl) return

  await formEl.validate(async (valid, fields) => {
    if (valid) {
      await updatePassword({ ...model.value })

      if (!errors.value) {
        formEl.resetFields()
      }
    }
  })
}
</script>
