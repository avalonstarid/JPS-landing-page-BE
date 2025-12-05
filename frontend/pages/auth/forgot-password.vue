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
        Lupa Kata Sandi
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
      v-model="model.email"
      :errors="errors"
      label="Email"
      name="email"
      size="large"
      type="email"
    />

    <button class="btn btn-primary flex justify-center grow" type="submit">
      Ganti Kata Sandi
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
  title: 'Forgot Password',
})

const sanctumFetch = useSanctumClient()
const formRef = ref<FormInstance>()
const errors = ref<any>({})
const loading = ref<boolean>(false)
const model = ref({
  email: '',
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
})

// Form submit function
const onSubmit = async (formEl: FormInstance | undefined) => {
  if (!formEl) return

  await formEl.validate(async (valid, fields) => {
    if (valid) {
      loading.value = true

      try {
        await sanctumFetch('/auth/forgot-password', {
          method: 'POST',
          body: model.value,
        })

        ElMessageBox.alert(
          'Setel ulang kata sandi berhasil. Silahkan cek email.',
          'Berhasil',
          {
            type: 'success',
          },
        ).then(() => {})
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
