<template>
  <FormBusinessLine :id="route.params.id.toString()" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [{ title: 'Lini Bisnis', to: '/business-lines' }],
  authorize: ['business_line_update'],
})

const route = useRoute()
</script>
