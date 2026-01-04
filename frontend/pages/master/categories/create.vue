<template>
  <FormMasterCategory />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [
    { title: 'Master', to: '/master' },
    { title: 'Kategori', to: '/master/categories' },
  ],
  authorize: ['category_create'],
})
</script>
