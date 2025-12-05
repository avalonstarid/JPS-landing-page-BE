<template>
  <el-form
    ref="formRef"
    class="card-body flex flex-col gap-5 p-10"
    :model="model"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="text-center mb-2.5">
      <h3 class="text-lg font-semibold text-gray-900 leading-none mb-2.5">
        Daftar Aplikasi
      </h3>
      <div class="flex items-center justify-center font-medium">
        <span class="text-2sm text-gray-600 me-1.5">
          Sudah memiliki akun?
        </span>
        <NuxtLink class="text-2sm link" :to="{ name: 'auth-sign-in' }">
          Masuk
        </NuxtLink>
      </div>
    </div>

    <InputBase
      v-model="model.name"
      :errors="errors"
      label="Nama Lengkap"
      name="name"
      size="large"
    />

    <InputBase
      v-model="model.email"
      :errors="errors"
      label="Email"
      name="email"
      size="large"
      type="email"
    />

    <InputBase
      v-model="model.password"
      :errors="errors"
      label="Kata Sandi"
      name="password"
      size="large"
      type="password"
      show-password
    />

    <InputBase
      v-model="model.password_confirmation"
      :errors="errors"
      label="Konfirmasi Kata Sandi"
      name="password_confirmation"
      size="large"
      type="password"
      show-password
    />

    <button class="btn btn-primary flex justify-center grow" type="submit">
      Daftar
    </button>
  </el-form>
</template>

<script setup lang="ts">
import type { FormInstance, FormRules } from 'element-plus'

definePageMeta({
  layout: 'auth-layout',
  sanctum: {
    guestOnly: true,
  },
  title: 'Sign Up',
})

const sanctumConfig = useSanctumConfig()
const sanctumFetch = useSanctumClient()
const formRef = ref<FormInstance>()
const errors = ref<any>({})
const loading = ref<boolean>(false)
const model = ref({
  email: '',
  password: '',
})

// Create Rules Form
const rules = reactive<FormRules>({
  email: [
    {
      required: true,
      message: 'Alamat Email wajib diisi.',
      trigger: 'blur',
    },
    {
      type: 'email',
      message: 'Silakan masukkan alamat email yang benar.',
      trigger: ['blur', 'change'],
    },
  ],
  name: [
    {
      required: true,
      message: 'Nama Lengkap wajib diisi.',
      trigger: 'blur',
    },
  ],
  password: [
    {
      required: true,
      message: 'Kata Sandi wajib diisi.',
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

// Form submit function
const onSubmit = async (formEl: FormInstance | undefined) => {
  if (!formEl) return

  await formEl.validate(async (valid, fields) => {
    if (valid) {
      loading.value = true

      try {
        await sanctumFetch('/auth/register', {
          method: 'POST',
          body: model.value,
        })

        ElMessageBox.alert('Pendaftaran berhasil.', 'Berhasil', {
          type: 'success',
        }).then(() => {
          navigateTo(sanctumConfig.redirect.onGuestOnly || '/')
        })
      } catch (e: any) {
        if (e.response) {
          errors.value = e.response._data
        } else {
          errors.value = e
        }
      } finally {
        loading.value = false
      }
    }
  })
}
</script>
