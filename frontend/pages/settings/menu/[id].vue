<template>
  <FormSettingsMenu :id="route.params.id.toString()" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [
    { title: 'Settings' },
    { title: 'Menu Management', to: '/settings/menu' },
  ],
  authorize: ['menu_update'],
})

const route = useRoute()
</script>
