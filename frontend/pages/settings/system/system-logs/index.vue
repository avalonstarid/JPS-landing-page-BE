<template>
  <iframe class="w-full h-full" src="/api/log-viewer"></iframe>
</template>

<script setup lang="ts">
definePageMeta({
  title: 'System Logs',
  breadcrumbs: [{ title: 'Settings' }],
  // authorize: ['audit_log_read'],
})
</script>
