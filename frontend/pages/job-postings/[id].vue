<template>
  <FormJobPosting :id="route.params.id.toString()" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [{ title: 'Lowongan Pekerjaan', to: '/job-postings' }],
  authorize: ['job_posting_update'],
})

const route = useRoute()
</script>
