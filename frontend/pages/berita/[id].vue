<template>
  <FormPost :id="route.params.id.toString()" type="TPST3" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [{ title: 'Berita', to: '/berita' }],
  authorize: ['post_update'],
})

const route = useRoute()
</script>
