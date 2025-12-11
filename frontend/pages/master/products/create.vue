<template>
  <FormMasterProduct />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [
    { title: 'User Management' },
    { title: 'Permissions', to: '/user-management/permissions' },
  ],
  authorize: ['permission_create'],
})
</script>
