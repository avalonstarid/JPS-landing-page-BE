<template>
  <FormContact :id="route.params.id.toString()" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Detail',
  breadcrumbs: [{ title: 'Contact Us', to: '/contact-us' }],
  authorize: ['contact_us_update'],
})

const route = useRoute()
</script>
