<template>
  <FormFaq :id="route.params.id.toString()" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [{ title: 'FAQ', to: '/faq' }],
  authorize: ['faq_update'],
})

const route = useRoute()
</script>
