<template>
  <FormInvestorDocument
    :id="route.params.id.toString()"
    category="laporan-keberlanjutan"
  />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [
    { title: 'Keberlanjutan' },
    {
      title: 'Laporan Keberlanjutan',
      to: '/keberlanjutan/laporan-keberlanjutan',
    },
  ],
  authorize: ['document_investor_update'],
})

const route = useRoute()
</script>
