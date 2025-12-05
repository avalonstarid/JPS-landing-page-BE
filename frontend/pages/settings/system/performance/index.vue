<template>
  <iframe class="w-full h-full" src="/api/pulse"></iframe>
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Performance Monitoring',
  breadcrumbs: [{ title: 'Settings' }],
  // authorize: ['audit_log_read'],
})
</script>
