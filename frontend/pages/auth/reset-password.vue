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
        Buat Kata Sandi Baru
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
      disabled
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
      Ganti Kata Sandi
    </button>
  </el-form>
</template>

<script setup lang="ts">
import useUsers from '@/composables/user-management/users'
import type { FormInstance, FormRules } from 'element-plus'

definePageMeta({
  layout: 'auth-layout',
  sanctum: {
    guestOnly: true,
  },
  title: 'Reset Password',
})

const route = useRoute()
const sanctumFetch = useSanctumClient()

const formRef = ref<FormInstance>()
const { checkTokenReset, resetPassword, loading, errors } = useUsers()
const model = ref({
  token: '',
  email: '',
  password: '',
  password_confirmation: '',
})

// Create Rules Form
const rules = reactive<FormRules>({
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
      await resetPassword({ ...model.value })

      if (!errors.value) {
        navigateTo({ name: 'auth-sign-in', query: { reset: 'success' } })
      }
    }
  })
}

onBeforeMount(async () => {
  await checkTokenReset({ token: route.query.token, email: route.query.email })
})

onMounted(() => {
  if (typeof route.query.email === 'string') {
    model.value.email = route.query.email
  }
  if (typeof route.query.token === 'string') {
    model.value.token = route.query.token
  }
})
</script>
