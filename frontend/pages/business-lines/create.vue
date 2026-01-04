<template>
  <FormBusinessLine />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [{ title: 'Lini Bisnis', to: '/business-lines' }],
  authorize: ['business_line_create'],
})
</script>
