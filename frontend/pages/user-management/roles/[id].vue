<template>
  <FormUserManagementRole :id="route.params.id.toString()" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [
    { title: 'User Management' },
    { title: 'Roles', to: '/user-management/roles' },
  ],
  authorize: ['role_update'],
})

const route = useRoute()
</script>
