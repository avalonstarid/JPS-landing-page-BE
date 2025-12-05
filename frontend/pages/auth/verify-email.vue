<template>
  <div class="card-body p-10">
    <div class="flex justify-center mb-5">
      <img
        class="dark:hidden max-h-[180px]"
        alt="image"
        src="/media/illustrations/28.svg"
      />
      <img
        class="light:hidden max-h-[180px]"
        alt="image"
        src="/media/illustrations/28-dark.svg"
      />
    </div>
    <h3 class="text-lg font-semibold text-gray-900 text-center mb-4">
      Verifikasi Email berhasil.
    </h3>
    <div class="text-2sm font-medium text-center text-gray-600 mb-7.5">
      Silahkan masuk untuk melanjutkan.
    </div>
    <div class="flex justify-center">
      <NuxtLink class="btn btn-primary" :to="{ name: 'auth-sign-in' }">
        Masuk
      </NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
import useUsers from '@/composables/user-management/users'

definePageMeta({
  layout: 'auth-layout',
  sanctum: {
    guestOnly: true,
  },
  title: 'Verify Email',
})

const route = useRoute()
const { checkVerifyEmail } = useUsers()

onBeforeMount(async () => {
  await checkVerifyEmail(route.query)
})
</script>
