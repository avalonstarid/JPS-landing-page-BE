<template>
  <FormUserManagementUser :id="route.params.id.toString()" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [
    { title: 'User Management' },
    { title: 'Users', to: '/user-management/users' },
  ],
  authorize: ['user_update'],
})

const route = useRoute()
</script>
