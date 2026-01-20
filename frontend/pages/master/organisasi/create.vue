<template>
  <FormMasterOrganisasi />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [
    { title: 'Master' },
    { title: 'Organisasi', to: '/master/organisasi' },
  ],
  authorize: ['organisasi_create'],
})
</script>
