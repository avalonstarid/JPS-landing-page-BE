<template>
  <FormMasterCategory :id="route.params.id.toString()" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [
    { title: 'Master', to: '/master' },
    { title: 'Kategori', to: '/master/categories' },
  ],
  authorize: ['category_update'],
})

const route = useRoute()
</script>
