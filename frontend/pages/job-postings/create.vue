<template>
  <FormJobPosting />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [{ title: 'Lowongan Pekerjaan', to: '/job-postings' }],
  authorize: ['job_posting_create'],
})
</script>
