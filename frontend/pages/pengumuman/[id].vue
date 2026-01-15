<template>
  <FormPost :id="route.params.id.toString()" type="announcement" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [{ title: 'Pengumuman', to: '/pengumuman' }],
  authorize: ['post_update'],
})

const route = useRoute()
</script>
