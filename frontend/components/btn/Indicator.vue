<template>
  <button class="btn btn-primary" :disabled="loading" type="submit">
    <div v-if="loading" class="flex align-items-center gap-1">
      <span><i class="ki-filled ki-loading animate-spin !text-sm"></i></span>
      Loading...
    </div>
    <slot v-else />
  </button>
</template>

<script setup lang="ts">
defineProps({
  loading: Boolean,
})
</script>
