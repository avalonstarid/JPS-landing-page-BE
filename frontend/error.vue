<template>
  <div class="flex items-center justify-center h-full grow">
    <div class="flex flex-col items-center">
      <div class="mb-16">
        <img
          class="dark:hidden max-h-[160px]"
          alt="image"
          src="/media/illustrations/19.svg"
        />
        <img
          class="light:hidden max-h-[160px]"
          alt="image"
          src="/media/illustrations/19-dark.svg"
        />
      </div>
      <span class="mb-3 badge badge-primary badge-outline">
        Oops! {{ props.error?.statusCode }}</span
      >
      <h3 class="text-2.5xl font-semibold text-gray-900 text-center mb-2">
        {{ props.error }}
      </h3>
      <div class="mb-10 text-center text-gray-700 text-md">
        The requested page is missing. Check the URL.
      </div>
      <button class="flex justify-center btn btn-primary" @click="handleError">
        Back to Dashboard
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps({
  error: Object,
})

const handleError = () => {
  clearError({ redirect: '/' })
}
</script>
