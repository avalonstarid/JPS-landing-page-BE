<template>
  <FormMasterDewan />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [{ title: 'Master' }, { title: 'Dewan', to: '/master/dewan' }],
  authorize: ['dewan_create'],
})
</script>
