<template>
  <FormMasterDewan :id="route.params.id.toString()" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [{ title: 'Master' }, { title: 'Dewan', to: '/master/dewan' }],
  authorize: ['dewan_update'],
})

const route = useRoute()
</script>
