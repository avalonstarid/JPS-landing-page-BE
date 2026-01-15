<template>
  <FormPost type="TPST4" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [
    {
      title: 'Keberlanjutan',
    },
    {
      title: 'Pendekatan dan Kinerja Manajemen',
      to: '/keberlanjutan/pendekatan-kinerja',
    },
  ],
  authorize: ['post_create'],
})
</script>
