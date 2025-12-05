<template>
  <FormUserManagementPermission :id="route.params.id.toString()" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [
    { title: 'User Management' },
    { title: 'Permissions', to: '/user-management/permissions' },
  ],
  authorize: ['permission_update'],
})

const route = useRoute()
</script>
