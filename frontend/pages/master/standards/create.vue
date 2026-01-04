<template>
  <FormMasterStandard />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [
    { title: 'Master' },
    { title: 'Standar', to: '/master/standards' },
  ],
  authorize: ['standard_create'],
})
</script>
