<template>
  <FormHistoricalTimeline />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [{ title: 'Linimasa Sejarah', to: '/historical-timelines' }],
  authorize: ['historical_timeline_create'],
})
</script>
