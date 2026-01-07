<template>
  <FormMasterLocation :id="route.params.id.toString()" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [
    { title: 'Master' },
    { title: 'Lokasi', to: '/master/locations' },
  ],
  authorize: ['location_update'],
})

const route = useRoute()
</script>
