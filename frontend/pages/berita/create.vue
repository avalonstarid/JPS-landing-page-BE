<template>
  <FormPost type="news" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [{ title: 'Berita', to: '/berita' }],
  authorize: ['post_create'],
})
</script>
