<template>
  <FormUserManagementRole />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [
    { title: 'User Management' },
    { title: 'Roles', to: '/user-management/roles' },
  ],
  authorize: ['role_create'],
})
</script>
