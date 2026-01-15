<template>
  <FormPost :id="route.params.id.toString()" type="TPST4" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [
    {
      title: 'Keberlanjutan',
    },
    {
      title: 'Pendekatan dan Kinerja Manajemen',
      to: '/keberlanjutan/pendekatan-kinerja',
    },
  ],
  authorize: ['post_update'],
})

const route = useRoute()
</script>
