<template>
  <FormFaq />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [{ title: 'FAQ', to: '/faq' }],
  authorize: ['faq_create'],
})
</script>
