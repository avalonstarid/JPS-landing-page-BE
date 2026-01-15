<template>
  <FormInvestorDocument category="laporan-keberlanjutan" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [
    { title: 'Keberlanjutan' },
    {
      title: 'Laporan Keberlanjutan',
      to: '/keberlanjutan/laporan-keberlanjutan',
    },
  ],
  authorize: ['document_investor_create'],
})
</script>
