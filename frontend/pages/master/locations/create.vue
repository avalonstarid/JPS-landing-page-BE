<template>
  <FormMasterLocation />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [
    { title: 'Master' },
    { title: 'Lokasi', to: '/master/locations' },
  ],
  authorize: ['location_create'],
})
</script>
