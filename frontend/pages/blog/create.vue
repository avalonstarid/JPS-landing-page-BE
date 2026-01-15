<template>
  <FormPost type="blog" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [{ title: 'Blog', to: '/blog' }],
  authorize: ['post_create'],
})
</script>
