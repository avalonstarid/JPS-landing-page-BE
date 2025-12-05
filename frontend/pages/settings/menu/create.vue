<template>
  <FormSettingsMenu />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [
    { title: 'Settings' },
    { title: 'Menu Management', to: '/settings/menu' },
  ],
  authorize: ['menu_create'],
})
</script>
