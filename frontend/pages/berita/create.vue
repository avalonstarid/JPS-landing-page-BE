<template>
  <FormPost type="TPST3" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [{ title: 'Berita', to: '/berita' }],
  authorize: ['post_create'],
})
</script>
