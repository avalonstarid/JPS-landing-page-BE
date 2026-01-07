<template>
  <FormTestimonial :id="route.params.id.toString()" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [{ title: 'Testimonial', to: '/testimonials' }],
  authorize: ['testimonial_update'],
})

const route = useRoute()
</script>
