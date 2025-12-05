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
        Masuk Aplikasi
      </h3>
      <div
        v-if="$router.hasRoute('auth-sign-up')"
        class="flex items-center justify-center font-medium"
      >
        <span class="text-2sm text-gray-600 me-1.5">
          Belum memiliki akun?
        </span>
        <NuxtLink class="text-2sm link" :to="{ name: 'auth-sign-up' }">
          Daftar
        </NuxtLink>
      </div>
    </div>

    <div
      v-if="$route.query.registered == 'success'"
      class="flex items-center grow rounded-xl border border-dashed gap-4 p-4 border-success bg-success-light"
    >
      <i class="ki-outline ki-information-1 text-3xl text-success"> </i>
      <div class="flex flex-col gap-0.5">
        <p class="text text-sm font-normal">
          Silahkan konfirmasi email terlebih dahulu agar dapat melanjutkan.
        </p>
      </div>
    </div>

    <div
      v-if="$route.query.reset == 'success'"
      class="flex items-center grow rounded-xl border border-dashed gap-4 p-4 border-success bg-success-light"
    >
      <i class="ki-outline ki-information-1 text-3xl text-success"> </i>
      <div class="flex flex-col gap-0.5">
        <p class="text text-sm font-normal">
          Silahkan masukan email dan kata sandi agar dapat melanjutkan.
        </p>
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

    <InputBase
      v-model="model.password"
      :errors="errors"
      label="Kata Sandi"
      name="password"
      size="large"
      type="password"
      show-password
    />

    <div
      v-if="$router.hasRoute('auth-forgot-password')"
      class="flex justify-end"
    >
      <NuxtLink class="text-2sm link" :to="{ name: 'auth-forgot-password' }">
        Lupa Kata Sandi?
      </NuxtLink>
    </div>

    <btnIndicator class="flex justify-center grow" :loading="loading">
      Masuk
    </btnIndicator>

    <!-- <div class="flex items-center gap-2">
        <span class="border-t border-gray-200 w-full"> </span>
        <span class="text-2xs text-gray-500 font-medium uppercase"> Or </span>
        <span class="border-t border-gray-200 w-full"> </span>
      </div>
      <div class="grid grid-cols-2 gap-2.5">
        <a class="btn btn-light btn-sm justify-center" href="#">
          <img
            class="size-3.5 shrink-0"
            alt=""
            src="/media/brand-logos/google.svg"
          />
          Use Google
        </a>
        <a class="btn btn-light btn-sm justify-center" href="#">
          <img
            class="size-3.5 shrink-0 dark:hidden"
            alt=""
            src="/media/brand-logos/apple-black.svg"
          />
          <img
            class="size-3.5 shrink-0 light:hidden"
            alt=""
            src="/media/brand-logos/apple-white.svg"
          />
          Use Apple
        </a>
      </div> -->
  </el-form>
</template>

<script setup lang="ts">
import type { FormInstance, FormRules } from 'element-plus'

definePageMeta({
  layout: 'auth-layout',
  sanctum: {
    guestOnly: true,
  },
  title: 'Sign In',
})

const { login } = useSanctumAuth()
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
  password: [
    {
      required: true,
      message: 'Kata Sandi wajib diisi.',
      trigger: 'blur',
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
        await login(model.value)

        // @ts-ignore
        window.location.href = '/'
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
