<template>
  <FormPost :id="route.params.id.toString()" type="blog" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [{ title: 'Blog', to: '/blog' }],
  authorize: ['post_update'],
})

const route = useRoute()
</script>
