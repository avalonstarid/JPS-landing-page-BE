<template>
  <FormHistoricalTimeline :id="route.params.id.toString()" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [{ title: 'Linimasa Sejarah', to: '/historical-timelines' }],
  authorize: ['historical_timeline_update'],
})

const route = useRoute()
</script>
