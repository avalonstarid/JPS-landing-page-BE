<template>
  <FormPost type="TPST1" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [{ title: 'Pengumuman', to: '/pengumuman' }],
  authorize: ['post_create'],
})
</script>
