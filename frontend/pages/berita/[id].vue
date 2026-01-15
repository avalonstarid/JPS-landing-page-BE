<template>
  <FormPost :id="route.params.id.toString()" type="news" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [{ title: 'Berita', to: '/berita' }],
  authorize: ['post_update'],
})

const route = useRoute()
</script>
