<template>
  <FormMasterStandard :id="route.params.id.toString()" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [
    { title: 'Master' },
    { title: 'Standar', to: '/master/standards' },
  ],
  authorize: ['standard_update'],
})

const route = useRoute()
</script>
