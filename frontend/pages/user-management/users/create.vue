<template>
  <FormUserManagementUser />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [
    { title: 'User Management' },
    { title: 'Users', to: '/user-management/users' },
  ],
  authorize: ['user_create'],
})
</script>
