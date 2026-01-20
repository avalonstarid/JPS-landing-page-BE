<template>
  <FormMasterOrganisasi :id="route.params.id.toString()" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [
    { title: 'Master' },
    { title: 'Organisasi', to: '/master/organisasi' },
  ],
  authorize: ['organisasi_update'],
})

const route = useRoute()
</script>
