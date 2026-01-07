<template>
  <FormTestimonial />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [{ title: 'Testimonial', to: '/testimonials' }],
  authorize: ['testimonial_create'],
})
</script>
